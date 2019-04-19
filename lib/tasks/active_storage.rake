require 'uri'

namespace :active_storage do
  task migrate: :environment do
    migratable_image_count = Image.where.not(original_old: nil).count
    i = 1

    Image.where.not(original_old: nil).find_each do |image|
      # This step helps us catch any attachments we might have uploaded that
      # don't have an explicit file extension in the filename
      puts "Migrating #{i} / #{migratable_image_count} (ID #{image.id}) ..."

      url_path = URI.parse(image.original_old).path
      filename = File.basename(url_path)
      ext = File.extname(url_path)

      next if filename.blank?

      begin
        image.original.attach(
          io: open(image.original_old),
          filename: filename,
          content_type: ext
        )

        image.original_old = nil
        image.save(validate: false)
      rescue OpenURI::HTTPError => e
        STDERR.puts "Unable to download image with ID #{image.id} (#{e}), skipping ..."
      end

      i += 1
    end
  end
end
