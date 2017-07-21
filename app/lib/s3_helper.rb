require 'aws-sdk'
require 'json'

module S3Helper
  # Returns true if we created a resource to our S3 instance successfully
  def self.connected?
    !@s3resrc.nil? || !@s3client.nil?
  end

  # Establish a connection to S3 instance and sets @s3
  #
  def self.establish_connection
    # Configure AWS
    Aws.config.update(
      region: Rails.application.secrets.s3_region,
      credentials: Aws::Credentials.new(Rails.application.secrets.aws_key, Rails.application.secrets.aws_secret)
    )

    # Setup S3
    @s3resrc = Aws::S3::Resource.new
    @s3client = Aws::S3::Client.new
  end

  # Upload a new thumbnail of the image from image_path to S3
  # @param image_id   - ID to use when naming bucket key
  # @param image_path - URL to an image
  # @param size       - (optional) :original, :preview, or :thumbnail
  #                     Resizes image to specified 'size' before uploading
  #
  # @return           - Object with S3 URL, image width, image height
  #
  # TODO: Handle errors such as unable to upload, could not connect to aws etc.
  # was upload_new_thumbnail
  def self.upload_image_from_path(image_id, image_path, size = :original)
    begin
      rmk_image = Magick::Image.read(image_path).first
    rescue Magick::ImageMagickError => e
      puts "Error while reading image at URL #{image_path}"
      STDERR.puts e
      return nil
    end

    file_ext = image_path.split('.').last

    upload_image_from_rmk_image(image_id, file_ext, rmk_image, size)
  end

  def self.upload_image_from_rmk_image(image_id, file_ext, rmk_image, size = :original)
    case size
    when :original
      filename = "original_#{image_id}.#{file_ext}"
    when :preview
      filename = "preview_#{image_id}.#{file_ext}"
      rmk_image = rmk_image.change_geometry('500>x') do |cols, rows, passed_img|
        passed_img.resize_to_fill(cols, rows)
      end
    when :thumbnail
      filename = "thumb_#{image_id}.#{file_ext}"
      rmk_image = rmk_image.resize_to_fill(275, 190)
    else
      STDERR.puts "Requested 'size' not understood."
      return nil
    end

    # Upload to S3 bucket
    obj = upload_rmagick_image(filename, rmk_image)

    { url: obj.public_url, width: rmk_image.columns, height: rmk_image.rows }
  end

  # Get an object from s3 bucket
  # @param key - filename
  # @return - nil if object does not exist
  def self.get_object(key)
    establish_connection unless connected?

    @s3client.get_object(bucket: Rails.application.secrets.s3_bucket, key: key)
  end

  def self.upload_rmagick_image(key, rmk_image)
    establish_connection unless connected?

    obj = @s3resrc.bucket(Rails.application.secrets.s3_bucket)
    obj = obj.object(key)
    obj.put(body: rmk_image.to_blob, acl: 'public-read')

    obj
  end

  def self.remove_image(key)
    establish_connection unless connected?

    @s3client.delete_object(bucket: Rails.application.secrets.s3_bucket, key: key)
  end
end
