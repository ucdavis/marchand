require 'rmagick'

module SiteHelper
    include AwsHelper

    # Return a thumbnail of the image provided
    # @param image - An object of the local Image class
    # TODO: Once errrors are caught, just return the s3 link on error
    #
    # @return string url path to thumbnail image
    def get_thumbnail(image)
        return image.thumbnail if image.thumbnail.present?

        # Create, upload, and save link to
        thumbnail = upload_new_thumbnail(image.s3)
        image.update( {:thumbnail => thumbnail } )

        return thumbnail
    end
end
