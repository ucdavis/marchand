module ImagesHelper
    # Returns the search url that shows this image
    # @param image - an Image object
    def build_search_url(image)
        delim = "@delim"

        topics = []
        image.topic_assignments.each do |ta|
            topics << ta.topic_id
        end
        topics = topics.join(",")

        regions = []
        image.region_assignments.each do |ra|
            regions << ra.region_id
        end
        regions = regions.join(",")

        calstandards = []
        image.data_cal_standards.each do |dcs|
            calstandards << dcs.cal_standard_id
        end
        calstandards = calstandards.join(",")

        return "/search?topic=#{topics}&region=#{regions}&calstandard=#{calstandards}&q=#{@image.title}"
    end
end
