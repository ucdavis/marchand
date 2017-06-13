module ImagesHelper
    # Returns the search url that shows this image
    # @param image - an Image object
    def build_search_url(image)
        topics = image.topics.ids
        topics = topics.join(",")

        regions = image.regions.ids
        regions = regions.join(",")

        calstandards = image.cal_standards.ids
        calstandards = calstandards.join(",")

        return "/search?topic=#{topics}&region=#{regions}&calstandard=#{calstandards}&q=#{image.title}"
    end
end
