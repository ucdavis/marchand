class LessonsController < ApplicationController
  before_action :set_image, only: [:show, :edit, :update, :destroy]

  # GET /lessons
  # GET /lessons.json
  def index
    @lessons = Lesson.all
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
        format.html {
          flash[:success] = "Paper was successfully created."
          redirect_to @lesson
        }
        format.json { render :show, status: :created, location: @lesson }
      else
        format.html { render :new }
        format.json { render json: @lesson.errors, status: :unprocessable_entity }
      end
    end
  end

  def update
  end

  def lesson_params
    params.require(:lesson).permit(:title, :grade, :pdf, :background, :search, { author_ids: [] })
  end
end
