require 'aws-sdk'
require 'json'

module AwsHelper
  # Returns true if we created a resource to our S3 instance successfully
  def connected?
    !@s3resrc.nil? || !@s3client.nil?
  end

  # Establish a connection to S3 instance and sets @s3
  #
  def establish_connection
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
  # @param image_path - URL to an image
  #
  # @return - S3 Link to uploaded thumbnail
  # TODO: Handle errors such as unable to upload, could not connect to aws etc.
  def upload_new_thumbnail(image_path)
    # Connect and configure to aws if not already connected
    establish_connection unless connected?

    # Using 1.45 ratio to build thumbnail from image_path
    begin
      image = Magick::Image.read(image_path).first
      image = image.resize_to_fill(275, 190)
    rescue => error
      File.open("#{Rails.root}/site_error.txt", 'a+') { |f| f.write(error) }
      return nil
    end

    # Upload to s3 bucket
    filename = "thumb_#{image.filename.split('/').last}"
    obj = upload_image(filename, image)

    obj.public_url
  end

  # Get an object from s3 bucket
  # @param key - filename
  # @return - nil if object does not exist
  def get_object(key)
    establish_connection unless connected?

    @s3client.get_object(bucket: Rails.application.secrets.s3_bucket, key: key)
  end

  def upload_image(key, image)
    establish_connection unless connected?

    obj = @s3resrc.bucket(Rails.application.secrets.s3_bucket)
    obj = obj.object(key)
    obj.put(body: image.to_blob, acl: 'public-read')

    obj
  end

  def upload_file(key, file_blob)
    establish_connection unless connected?

    obj = @s3resrc.bucket(Rails.application.secrets.s3_bucket)
    obj = obj.object(key)
    obj.put(body: file_blob, acl: 'public-read')

    obj
  end

  def remove_image(key)
    establish_connection unless connected?

    @s3client.delete_object(bucket: Rails.application.secrets.s3_bucket, key: key)
  end
end
