require 'rmagick'

module SiteHelper
    def convert_to_thumbnail(image_path)
        image = Magick::Image.read(image_path).first

        # Using 1.45 ratio to build thumbnail
        image.resize_to_fill(275, 190)
    end
end
