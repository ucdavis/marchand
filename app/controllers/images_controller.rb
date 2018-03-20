class ImagesController < GalleryController
  include ImagesHelper
  require 's3_helper'

  before_action :set_image, only: [:edit, :update, :destroy]

  RESULTS_PER_PAGE = 20
  FEATURED_IMAGE_LIMIT = 24

  def featured
    cache_key = Image.maximum(:updated_at).try(:utc).try(:to_s, :number).to_s

    @cards = Rails.cache.fetch("#{cache_key}/featured", expires_in: 72.hours) do
      cards = []
      Topic.where(featured: true).each do |topic|
        cards << {
          image: Image.joins(:topics)
                      .where(public: 1, featured: 1, topics: { id: topic.id })
                      .order('RAND()')
                      .first,
          topic: topic
        }
      end
      cards
    end
  end

  def index
    if params[:bestof].present? && params[:bestof]
      @bestof = Topic.find_by_id(params[:topic_id]).title

      @images = Image.joins(:topics)
                     .where(featured: true, public: true,
                            topics: { featured: true, id: params[:topic_id] })
                     .paginate(page: params[:page], per_page: RESULTS_PER_PAGE)
      @num_results = @images.total_entries
      @num_pages = @images.total_pages
    elsif params[:q].present? && (params[:q].to_f.to_s == params[:q].to_s) || (params[:q].to_i.to_s == params[:q].to_s)
      @images = Image.where(id: params[:q].to_i)
      @num_results = @images.count
      @num_pages = (@images.count / RESULTS_PER_PAGE).to_f.ceil
    else
      @query, filter = es_query_from_params(params)

      @images = Image.search(@query, filter)
                      .paginate(page: params[:page], per_page: RESULTS_PER_PAGE).records
      @num_results = @images.total_entries
      @num_pages = @images.total_pages
    end
  end

  def new
    @image = Image.new
  end

  # POST /images
  # rubocop:disable Metrics/AbcSize
  def create
    @image = Image.new(image_params)

    respond_to do |format|
      if @image.save
        uploaded_file_path = image_params[:original]

        # Generate and add both thumbnails to S3
        if uploaded_file_path.present?
          rmk_image = Magick::Image.from_blob(image_params[:original].read).first
          file_ext = image_params[:original].original_filename.split('.').last

          original_obj = S3Helper.upload_image_from_rmk_image(@image.id, file_ext, rmk_image)
          @image.original = original_obj[:url]
          @image.original_width = original_obj[:width]
          @image.original_height = original_obj[:height]

          preview_obj = S3Helper.upload_image_from_rmk_image(@image.id, file_ext, rmk_image, :preview)
          @image.preview = preview_obj[:url]
          @image.preview_width = preview_obj[:width]
          @image.preview_height = preview_obj[:height]

          thumbnail_obj = S3Helper.upload_image_from_rmk_image(@image.id, file_ext, rmk_image, :thumbnail)
          @image.thumbnail = thumbnail_obj[:url]
          @image.thumbnail_width = thumbnail_obj[:width]
          @image.thumbnail_height = thumbnail_obj[:height]

          @image.save!
        end

        format.html { redirect_to edit_image_path @image }
        format.json { head :no_content }
      else
        format.html { render action: :edit }
        format.json { render json: @image.errors, status: :unprocessable_entity }
      end
    end
  end
  # rubocop:enable Metrics/AbcSize

  # GET /images/:id/edit
  def edit; end

  def manipulate
    @image = Image.find(params[:image_id])

    # Download and manipulate the image
    require 'open-uri'

    urlimage = open(@image.original)
    rmk_image = Magick::ImageList.new
    rmk_image.from_blob(urlimage.read)

    # Generate and add both thumbnails to S3
    old_original = @image.original
    old_thumbnail = @image.thumbnail
    old_preview = @image.preview

    file_ext = @image.original.split('.').last

    # Perform manipulation
    case params['edit_mode']
    when 'rotate_left'
      # counter-clockwise
      rmk_image.rotate!(-90)
    when 'rotate_right'
      # clockwise
      rmk_image.rotate!(90)
    when 'flip_horizontal'
      # horizontal flip ("flop")
      rmk_image.flop!
    when 'flip_vertical'
      # vertical flip
      rmk_image.flip!
    else
      Rails.logger.error 'Manipulate edit_mode not understood: ' + params['edit_mode']
    end

    original_obj = S3Helper.upload_image_from_rmk_image(@image.id, file_ext, rmk_image)
    @image.original = original_obj[:url]
    @image.original_width = original_obj[:width]
    @image.original_height = original_obj[:height]

    preview_obj = S3Helper.upload_image_from_rmk_image(@image.id, file_ext, rmk_image, :preview)
    @image.preview = preview_obj[:url]
    @image.preview_width = preview_obj[:width]
    @image.preview_height = preview_obj[:height]

    thumbnail_obj = S3Helper.upload_image_from_rmk_image(@image.id, file_ext, rmk_image, :thumbnail)
    @image.thumbnail = thumbnail_obj[:url]
    @image.thumbnail_width = thumbnail_obj[:width]
    @image.thumbnail_height = thumbnail_obj[:height]

    @image.save!

    # Remove old images from S3 only if we updated the keys. If we just uploaded using
    # the same keys, the images were replaced, and we don't need to worry about this.
    S3Helper.remove_image(old_original.split('/').last) unless old_original = @image.original
    S3Helper.remove_image(old_thumbnail.split('/').last) unless old_thumbnail = @image.thumbnail
    S3Helper.remove_image(old_preview.split('/').last) unless old_preview = @image.preview

    # send_data image.to_blob, type: 'image/jpeg', disposition: 'inline'
  end

  # PUT /images/:id
  # PATCH /images/:id
  # rubocop:disable Metrics/MethodLength
  # rubocop:disable Metrics/AbcSize
  def update
    respond_to do |format|
      if @image.update(image_params)
        # Update image if provided
        uploaded_file_path = image_params[:original]

        if uploaded_file_path.present?
          # Generate and add both thumbnails to S3
          old_original = @image.original
          old_thumbnail = @image.thumbnail
          old_preview = @image.preview

          rmk_image = Magick::Image.from_blob(image_params[:original].read).first
          file_ext = image_params[:original].original_filename.split('.').last

          original_obj = S3Helper.upload_image_from_rmk_image(@image.id, file_ext, rmk_image)
          @image.original = original_obj[:url]
          @image.original_width = original_obj[:width]
          @image.original_height = original_obj[:height]

          preview_obj = S3Helper.upload_image_from_rmk_image(@image.id, file_ext, rmk_image, :preview)
          @image.preview = preview_obj[:url]
          @image.preview_width = preview_obj[:width]
          @image.preview_height = preview_obj[:height]

          thumbnail_obj = S3Helper.upload_image_from_rmk_image(@image.id, file_ext, rmk_image, :thumbnail)
          @image.thumbnail = thumbnail_obj[:url]
          @image.thumbnail_width = thumbnail_obj[:width]
          @image.thumbnail_height = thumbnail_obj[:height]

          @image.save!

          # Remove old images from S3 only if we updated the keys. If we just uploaded using
          # the same keys, the images were replaced, and we don't need to worry about this.
          S3Helper.remove_image(old_original.split('/').last) unless old_original = @image.original
          S3Helper.remove_image(old_thumbnail.split('/').last) unless old_thumbnail = @image.thumbnail
          S3Helper.remove_image(old_preview.split('/').last) unless old_preview = @image.preview
        end

        format.html { redirect_to edit_image_url(@image) }
        format.json { head :no_content }
      else
        format.html { render action: :edit }
        format.json { render json: @image.errors, status: :unprocessable_entity }
      end
    end
  end
  # rubocop:enable Metrics/MethodLength
  # rubocop:enable Metrics/AbcSize

  # DELETE /images/:id
  def destroy
    @image.destroy

    # Remove images from S3
    S3Helper.remove_image(@image.original.split('/').last) if @image.original.present?
    S3Helper.remove_image(@image.preview.split('/').last) if @image.preview.present?
    S3Helper.remove_image(@image.thumbnail.split('/').last) if @image.thumbnail.present?

    respond_to do |format|
      format.html { redirect_to images_url }
      format.json { head :no_content }
    end
  end

  private

  # rubocop:disable Style/BracesAroundHashParameters
  def image_params
    params.require(:image).permit(:id, :title, :collection_id, :public, :card, :citation, :featured,
                                  :original, :missing,
                                  { topic_ids: [] }, { region_ids: [] }, { cal_standard_ids: [] },
                                  { nat_standard_ids: [] }, { author_ids: [] })
  end
  # rubocop:enable Style/BracesAroundHashParameters

  def set_image
    @image = Image.find(params[:id])
  end

  def as_query_string(field, values)
    {
      fields: [field],
      query: values.join(' OR ')
    }
  end

  # rubocop:disable Metrics/MethodLength
  # rubocop:disable Metrics/AbcSize
  # rubocop:disable Metrics/CyclomaticComplexity
  # rubocop:disable Metrics/PerceivedComplexity
  def es_query_from_params(params)
    # All text query in ES
    # ~1 indicates fuzzy matching. It suspects that the query might have been mispelled by 1 letter
    q = params[:q].present? ? "#{params[:q]}~1" : '*'

    query = [
      { query_string: { query: q } }
    ]

    params_region = params[:region].split(',') if params[:region].present?
    params_topic = params[:topic].split(',') if params[:topic].present?
    params_collection = params[:collection].split(',') if params[:collection].present?
    params_calstandard = params[:calstandard].split(',') if params[:calstandard].present?

    # rubocop:disable Metrics/LineLength
    query << { query_string: as_query_string('region_assignments.region_id', params_region) } if params[:region].present?
    query << { query_string: as_query_string('topic_assignments.topic_id', params_topic) } if params[:topic].present?
    query << { query_string: as_query_string('collection_id', params_collection) } if params[:collection].present?
    query << { query_string: as_query_string('data_cal_standards.cal_standard_id', params_calstandard) } if params[:calstandard].present?
    # rubocop:enable Metrics/LineLength

    # Text search dates if only one is given
    if params[:start_year].present? ^ params[:end_year].present?
      query << { query_string: { query: params[:start_year] } } if params[:start_year].present?
      query << { query_string: { query: params[:end_year] } } if params[:end_year].present?
    end

    # Show everything if admin
    filter = []
    unless admin?
      filter << {
        term: { public: 1 }
      }
    end

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

    return query, filter # rubocop:disable Style/RedundantReturn
  end
  # rubocop:enable Metrics/MethodLength
  # rubocop:enable Metrics/AbcSize
  # rubocop:enable Metrics/CyclomaticComplexity
  # rubocop:enable Metrics/PerceivedComplexity
end
