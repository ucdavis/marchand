class SiteController < ApplicationController
    IMAGE_LIMIT = 24

    def index
        # Retrieve featured topics and images available to the public
        @cards = TopicAssignment.joins(:image).joins(:topic).where(:images => {:featured => 1, :public => 1}, :topics => {:featured => 1}).order("RANDOM()").limit(IMAGE_LIMIT)
    end

    def search
        # All text query in ES
        @query = params[:q].present? ? params[:q] : "*"
        filter = []
        filter << {
            term: {
                collection_id: 2
            }
        }

        filter << {
            term: {
                public: 1
            }
        }

        @images = Image.search(@query, filter).records

        # Filter using ORM
        # @images = @allDocuments.where(:collection_id => 2)
        # @images = @allDocuments.where(:collection_id => 4).or(@images)
    end

    def edit
    end

    def lesson
    end
end
