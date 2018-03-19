require 'rake'
require 'open-uri'
require 'digest'

namespace :images do
  # Generate thumbnails for S3 for all images, replacing ones which exist
  task regenerate_thumbnails: :environment do
    images = Image.where(preview: nil).where.not(original: nil)

    count = images.size

    images.each_with_index do |img, i|
      puts "Processing #{i + 1} / #{count} (ID #{img.id}) ..."

      # Download original image from S3
      begin
        image = Magick::Image.read(img.original).first
      rescue Magick::ImageMagickError => e
        puts "Error while reading original image for ID #{img.id} at #{img.original}"
        STDERR.puts e
        puts 'Skipping ...'
        next
      end

      if image
        file_ext = img.original.split('.').last

        img.original_width = image.columns
        img.original_height = image.rows

        # Generate two thumbnails
        # Thumbnail is the smallest and is a 275x190 image where the original
        # is resized while maintaining aspect ratio and cropped to fit 275x190
        thumb_obj = S3Helper.upload_image_from_rmk_image(img.id, file_ext, image, :thumbnail)
        img.thumbnail = thumb_obj[:url]
        img.thumbnail_width = thumb_obj[:width]
        img.thumbnail_height = thumb_obj[:height]

        # Preview is larger than a thumbnail and maintains the aspect ratio but
        # does not involve cropping. '500>x' means resize to a width of 500 only if
        # the image has a width larger than 500, maintaining aspect ratio.
        preview_obj = S3Helper.upload_image_from_rmk_image(img.id, file_ext, image, :preview)
        img.preview = preview_obj[:url]
        img.preview_width = preview_obj[:width]
        img.preview_height = preview_obj[:height]

        # Update local record with new thumbnail paths and modified time
        img.save!
      else
        puts "Could not read image for ID #{img.id}. Skipping ..."
      end
    end
  end

  # Generate thumbnails for S3 for all images, replacing ones which exist
  task :replace_image, [:image_id, :filepath] => :environment do |_t, args|
    unless args[:image_id]
      puts 'You must specify an image ID to replace.'
      exit(-1)
    end

    unless args[:filepath]
      puts 'You must specify a filepath.'
      exit(-1)
    end

    img = Image.find_by_id(args[:image_id])
    unless img
      STDERR.puts "Could not find an image with ID #{args[:image_id]}"
      exit(-1)
    end

    # Read image from disk
    begin
      rmk_image = Magick::Image.read(args[:filepath]).first
    rescue Magick::ImageMagickError => e
      puts "Error while reading image at #{args[:filepath]}"
      STDERR.puts e
      exit(-1)
    end

    file_ext = args[:filepath].split('.').last

    original_obj = S3Helper.upload_image_from_rmk_image(img.id, file_ext, rmk_image)
    img.original = original_obj[:url]
    img.original_width = original_obj[:width]
    img.original_height = original_obj[:width]

    thumb_obj = S3Helper.upload_image_from_rmk_image(img.id, file_ext, rmk_image, :thumbnail)
    img.thumbnail = thumb_obj[:url]
    img.thumbnail_width = thumb_obj[:width]
    img.thumbnail_height = thumb_obj[:height]

    preview_obj = S3Helper.upload_image_from_rmk_image(img.id, file_ext, rmk_image, :preview)
    img.preview = preview_obj[:url]
    img.preview_width = preview_obj[:width]
    img.preview_height = preview_obj[:height]

    img.save!
  end

  # Checks if S3 files contain the same image stored in local according to Image.file
  # Outputs any error in #{Rails.root}/log.txt
  task check_integrity: :environment do
    file = File.open("#{Rails.root}/log.txt", 'w')

    imgs = Image.where.not(s3: nil).where.not(s3: '')
    i = 0
    cnt = imgs.size
    imgs.each do |img|
      puts "#{i} / #{cnt} "
      if img.file.nil? || img.file == ''
        file.puts "nofile #{img.id} #{img.s3}\n"
        puts "\nnofile #{img.id} #{img.s3}\n"
        i += 1
        next
      end

      begin
        s3 = open(img.s3).read
        hp = open("#{Rails.root}/marchandslides.bak/#{img.file}").read
        if Digest::MD5.hexdigest(s3) == Digest::MD5.hexdigest(hp)
          puts 'Good'
        else
          file.puts "diff #{img.id}\n"
          puts "\ndiff #{img.id}\n"
        end
      rescue => error
        file.puts "error #{img.id} #{img.s3} #{error}\n"
        puts "\nerror #{img.id} #{img.s3} #{error}\n"
      ensure
        i += 1
      end
    end

    file.close
  end
end
