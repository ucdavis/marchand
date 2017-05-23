class SiteController < ApplicationController
    before_action :parse_param
    IMAGE_LIMIT = 24

    def index
        # Retrieve featured topics and images available to the public
        @cards = TopicAssignment.joins(:image).joins(:topic).where(:images => {:featured => 1, :public => 1}, :topics => {:featured => 1}).order("RANDOM()").limit(IMAGE_LIMIT)
    end

    def search
        # All text query in ES
        # ~1 indicates fuzzy matching. It suspects that the query might have been mispelled by 1 letter
        q = params[:q].present? ? "#{params[:q]}~1" : "*"
        @query = [
            { query_string: { query: q }}
        ]

        # @query << { query_string: get_query_string("region", params[:region])} if params[:region].present?
        # @query << { query_string: get_query_string("topic", params[:topic])} if params[:topic].present?
        @query << { query_string: get_query_string("collection_id", params[:collection])} if params[:collection].present?
        # @query << { query_string: get_query_string("calstandard", params[:calstandard])} if params[:calstandard].present?
        puts "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
        puts @query
        filter = []
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

    def get_query_string(field, values)
        {
                fields: [field],
                query: values.join(" OR ")
        }
    end

    def edit
    end

    def lesson
    end

    private
    def parse_param
        params[:region] = params[:region].split(",") if params[:region].present?
        params[:collection] = params[:collection].split(",") if params[:collection].present?
        params[:topic] = params[:topic].split(",") if params[:topic].present?
        params[:calstandard] = params[:calstandard].split(",") if params[:calstandard].present?
    end
end
