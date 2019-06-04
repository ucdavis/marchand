class LessonsController < GalleryController
  before_action :set_lesson, only: [:show, :edit, :update, :destroy]

  RESULTS_PER_PAGE = 20

  # GET /lessons
  # GET /lessons.json
  def index
    if params[:q].present? && (params[:q].to_f.to_s == params[:q].to_s) || (params[:q].to_i.to_s == params[:q].to_s)
      @lessons = Lesson.where(id: params[:q].to_i)
      @num_results = @lessons.count
      @num_pages = (@lessons.count / RESULTS_PER_PAGE).to_f.ceil
    else
      @query = es_query_from_params(params)

      @lessons = Lesson.search(@query)
                      .paginate(page: params[:page], per_page: RESULTS_PER_PAGE).records

      @num_results = @lessons.total_entries
      @num_pages = @lessons.total_pages
    end
  end

  def show
    render plain: 'No such path', status: 404
  end

  # GET /lessons/new
  def new
    @lesson = Lesson.new
  end

  # GET /lessons/1/edit
  def edit
  end

  # POST /lessons
  # POST /lessons.json
  def create
    @lesson = Lesson.new(lesson_params)

    respond_to do |format|
      if @lesson.save
        @lesson.save!
        format.html { redirect_to edit_lesson_path @lesson }
        format.json { head :no_content }
      else
        format.html { render action: :edit }
        format.json { render json: @lesson.errors, status: :unprocessable_entity }
      end
    end
  end

  def update
    respond_to do |format|
      if @lesson.update(lesson_params)
        format.html { redirect_to edit_lesson_url(@lesson) }
        format.json { head :no_content }
      else
        format.html { render action: :edit }
        format.json { render json: @lesson.errors, status: :unprocessable_entity }
      end
    end
  end

  def set_lesson
    @lesson = Lesson.find(params[:id])
  end

  def lesson_params
    params.require(:lesson).permit(:id, :title, :grade, :pdf, :background, :search,
                                  { image_ids: [] }, { topic_ids: [] }, { region_ids: [] }, { cal_standard_ids: [] },
                                  { nat_standard_ids: [] }, { author_ids: [] })
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

    params_region = params[:region].split(',').select { |i| i == i.to_i.to_s } if params[:region].present?
    params_topic = params[:topic].split(',').select { |i| i == i.to_i.to_s } if params[:topic].present?
    params_calstandard = params[:calstandard].split(',').select { |i| i == i.to_i.to_s } if params[:calstandard].present?
    params_natstandard = params[:natstandard].split(',').select { |i| i == i.to_i.to_s } if params[:natstandard].present?

    # rubocop:disable Metrics/LineLength
    query << { query_string: as_query_string('lesson_region_assignments.region_id', params_region) } if params[:region].present?
    query << { query_string: as_query_string('lesson_topic_assignments.topic_id', params_topic) } if params[:topic].present?
    query << { query_string: as_query_string('lesson_data_cal_standards.cal_standard_id', params_calstandard) } if params[:calstandard].present?
    query << { query_string: as_query_string('lesson_data_nat_standards.nat_standard_id', params_natstandard) } if params[:natstandard].present?
    # rubocop:enable Metrics/LineLength

    return query # rubocop:disable Style/RedundantReturn
  end
end
