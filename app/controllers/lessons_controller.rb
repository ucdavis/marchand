class LessonsController < ApplicationController
  before_action :set_lesson, only: [:show, :edit, :update, :destroy]

  RESULTS_PER_PAGE = 20

  # GET /lessons
  # GET /lessons.json
  def index
    @lessons = Lesson.paginate(page: params[:page], per_page: RESULTS_PER_PAGE)
    @num_pages = @lessons.total_pages
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
  end

  def send_lesson_mail
    @lesson = Lesson.find(params[:lesson_id])
    @customer_email = params[:email]

    UserMailer.lesson_request(@lesson, @customer_email).deliver

    redirect_to lessons_path, notice: 'Lesson request submitted successfully.'
  end

  def set_lesson
    @lesson = Lesson.find(params[:id])
  end

  def lesson_params
    params.require(:lesson).permit(:title, :grade, :pdf, :background, :search, image_ids: [])
  end
end
