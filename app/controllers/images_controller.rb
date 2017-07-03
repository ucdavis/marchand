class ImagesController < ApplicationController
  RESULTS_PER_PAGE = 20

  include ImagesHelper

  before_action :set_image, only: [:show, :edit, :update, :destroy]

  def index
    if params[:bestof].present? && params[:bestof]
      @images = Image.joins(:topics).where(featured: true, public: true, topics: { featured: true, id: params[:topic_id] }).paginate(page: params[:page], per_page: RESULTS_PER_PAGE)
    else
      @query, filter = es_query_from_params(params)

      @images = Image.search(@query, filter).paginate(page: params[:page], per_page: RESULTS_PER_PAGE).records
    end
  end

  def new
    @image = Image.new(image_params)
  end

  # POST /images
  def create
    new_params = image_params

    respond_to do |format|
      # Upload image & thumbnail to s3
      if new_params[:s3].present?
        filename = "#{Time.now.to_i}_#{image_params[:s3].original_filename}"

        uploaded_img = Magick::Image.from_blob(new_params[:s3].read).first
        new_params[:s3] = upload_image(filename, uploaded_img).public_url
        new_params[:thumbnail] = upload_new_thumbnail(new_params[:s3])
        new_params[:view] = uploaded_img.rows > uploaded_img.columns ? "portrait" : "landscape";
      end

      @image = Image.new(new_params)
      if @image.save
        format.html { redirect_to edit_image_path @image }
        format.json { head :no_content }
      else
        format.html { render action: :edit }
        format.json { render json: @image.errors, status: :unprocessable_entity }
      end
    end
  end

  # GET /images/:id/edit
  def edit
  end

  # PUT /images/:id
  # PATCH /images/:id
  def update
    respond_to do |format|
      update_params = image_params
      # Currently, form helpers can't set the value of the :include_blank field
      update_params[:collection_id] = 0 unless update_params[:collection_id].present?

      # Upload image & thumbnail to s3
      if update_params[:s3].present?
        filename = "#{Time.now.to_i}_#{image_params[:s3].original_filename}"
        uploaded_img = Magick::Image.from_blob(update_params[:s3].read).first
        update_params[:s3] = upload_image(filename, uploaded_img).public_url
        update_params[:thumbnail] = upload_new_thumbnail(update_params[:s3])
        update_params[:view] = uploaded_img.rows > uploaded_img.columns ? "portrait" : "landscape";
      end

      old_image = @image.s3
      old_thumbnail = @image.thumbnail
      if @image.update(update_params)
        # Remove old image & thumbnail from s3
        if update_params[:s3].present?
          remove_image(old_image.split("/").last)
          remove_image(old_thumbnail.split("/").last)
        end

        format.html { redirect_to build_search_url(@image) }
        format.json { head :no_content }
      else
        format.html { render action: :edit }
        format.json { render json: @image.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /images/:id
  def destroy
    # Remove images from S3
    remove_image(@image.s3.split("/").last) if @image.s3.present?
    remove_image(@image.thumbnail.split("/").last) if @image.thumbnail.present?

    @image.destroy
    respond_to do |format|
      format.html { redirect_to search_url }
      format.json { head :no_content }
    end
  end

  private

    def image_params
      params.require(:image).permit(:id, :title, :collection_id, :public, :card, :citation, :featured, :view, :thumbnail, :s3, :end_year, :start_year, {:topic_ids => []}, {:region_ids => []}, {:cal_standard_ids => []}, {:nat_standard_ids => []}, {:author_ids => []})
    end

    def set_image
      @image = Image.find(params[:id])
      @image.s3 = "https://thumb7.shutterstock.com/display_pic_with_logo/64260/405078397/stock-photo-business-architecture-building-construction-and-people-concept-close-up-of-architect-hands-405078397.jpg" unless @image.s3.present?
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

      params_region = params[:region].split(",") if params[:region].present?
      params_topic = params[:topic].split(",") if params[:topic].present?
      params_collection = params[:collection].split(",") if params[:collection].present?
      params_calstandard = params[:calstandard].split(",") if params[:calstandard].present?

      query << { query_string: as_query_string("region_assignments.region_id", params_region)} if params[:region].present?
      query << { query_string: as_query_string("topic_assignments.topic_id", params_topic)} if params[:topic].present?
      query << { query_string: as_query_string("collection_id", params_collection)} if params[:collection].present?
      query << { query_string: as_query_string("data_cal_standards.cal_standard_id", params_calstandard)} if params[:calstandard].present?

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
