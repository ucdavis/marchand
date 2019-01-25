require 'uri'

namespace :active_storage do
  task migrate: :environment do
    Image.where.not(original_old: nil).find_each do |image|
      # This step helps us catch any attachments we might have uploaded that
      # don't have an explicit file extension in the filename

      original_old_url = URI.parse(image.original_old)
      url_path = original_old_url.path
      filename = File.basename(url_path)
      ext = File.extname(url_path)

      next if filename.blank?

      # this url pattern can be changed to reflect whatever service you use
      url = "https://s3-us-west-1.amazonaws.com/marchand/#{filename}"

      image.original.attach(
        io: open(url),
        filename: filename,
        content_type: ext
      )
    end
  end
end
