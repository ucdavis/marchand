require 'casclient'
require 'casclient/frameworks/rails/filter'

class SiteController < ApplicationController
  before_action :format_params
  before_action CASClient::Frameworks::Rails::Filter, only: [:login]

  FEATURED_IMAGE_LIMIT = 24

  def index
    # Retrieve featured topics and images available to the public
    @cards = TopicAssignment.joins(:image)
                            .joins(:topic)
                            .where(
                                :images => {:featured => 1, :public => 1},
                                :topics => {:featured => 1}
                            ).order("RANDOM()").limit(FEATURED_IMAGE_LIMIT)
  end

  def admin
    redirect_to root_url unless is_admin?

    @author = Author.new
    @topic = Topic.new
    @region = Region.new
    @cal_standard = CalStandard.new
    @nat_standard = NatStandard.new
  end

  def search
    if params[:bestof].present? && params[:bestof]
      @images = []
      TopicAssignment.joins(:image).joins(:topic).where(:images => {:featured => 1, :public => 1}, :topics => {:featured => 1, }, :topic_id => params[:topic_id]).each do |ta|
        @images << ta.image
      end
    else
      @query, filter = es_query_from_params(params)

      @images = Image.search(@query, filter).records
    end
  end

  def edit
  end

  def lesson
  end

  def download
    # Rails translate /file.jpg as key: file, format: jpg

    key = "#{params[:key]}.#{params[:format]}"

    file = get_object(key)

    send_data file.body.read,
              filename: key,
              type: file.content_type,
              disposition: 'attachment'
  end

  def login
    redirect_to "/"
  end

  def logout
    CASClient::Frameworks::Rails::Filter.logout(self)
  end

  private

    def format_params
      params[:region] = params[:region].split(",") if params[:region].present?
      params[:collection] = params[:collection].split(",") if params[:collection].present?
      params[:topic] = params[:topic].split(",") if params[:topic].present?
      params[:calstandard] = params[:calstandard].split(",") if params[:calstandard].present?
    end

    def as_query_string(field, values)
      return {
        fields: [field],
        query: values.join(" OR ")
      }
    end

    def es_query_from_params(params)
      # All text query in ES
      # ~1 indicates fuzzy matching. It suspects that the query might have been mispelled by 1 letter
      q = params[:q].present? ? "#{params[:q]}~1" : "*"
      
      query = [
        { query_string: { query: q }}
      ]

      query << { query_string: as_query_string("region_assignments.region_id", params[:region])} if params[:region].present?
      query << { query_string: as_query_string("topic_assignments.topic_id", params[:topic])} if params[:topic].present?
      query << { query_string: as_query_string("collection_id", params[:collection])} if params[:collection].present?
      query << { query_string: as_query_string("data_cal_standards.cal_standard_id", params[:calstandard])} if params[:calstandard].present?

      # Text search dates if only one is given
      if params[:start_year].present? ^ params[:end_year].present?
        query << { query_string:{ query: params[:start_year] }} if params[:start_year].present?
        query << { query_string:{ query: params[:end_year] }} if params[:end_year].present?
      end

      # Show everything if admin
      filter = []
      filter << {
        term: { public: 1 }
      } unless is_admin?

      # Filter dates if both are given
      if params[:start_year].present? && params[:end_year].present?
        filter << {
          range: {
            start_year: { gte: params[:start_year] }
          }
        }

        filter << {
          range: {
            end_year: { lte: params[:end_year] }
          }
        }
      end

      return query, filter
    end

end
