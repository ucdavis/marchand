require 'rake'
require 'open-uri'
require 'digest'

namespace :images do
  # Generate thumbnails for S3 for all images, replacing ones which exist
  task regenerate_thumbnails: :environment do
    #images = Image.all
    images = Image.where(preview: nil).where.not(original: nil)

    count = images.size

    images.each_with_index do |img, i|
      puts "Processing #{i + 1} / #{count} ..."

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
        thumb = image.resize_to_fill(275, 190)
        img.thumbnail_width = thumb.columns
        img.thumbnail_height = thumb.rows

        # Preview is larger than a thumbnail and maintains the aspect ratio but
        # does not involve cropping. '500>x' means resize to a width of 500 only if
        # the image has a width larger than 500, maintaining aspect ratio.
        preview = image.change_geometry('500>x') do |cols, rows, passed_img|
          img.preview_width = cols
          img.preview_height = rows
          passed_img.resize_to_fill(cols, rows)
        end

        # Store both thumbnails in S3
        obj = S3Helper.upload_image("thumb_#{img.id}.#{file_ext}", thumb)
        img.thumbnail = obj.public_url

        obj = S3Helper.upload_image("preview_#{img.id}.#{file_ext}", preview)
        img.preview = obj.public_url

        # Update local record with new thumbnail paths and modified time
        img.save!
      else
        puts 'Could not read image for ID #{img.id}. Skipping ...'
      end
    end
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
