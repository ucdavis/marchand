class LessonsController < ApplicationController
    before_action :set_lesson, only: [:edit, :update, :destroy]

    def index
    end

    def new
        @lesson = Lesson.new
        @prompt = "Create"
    end


    def edit
        @prompt = "Update"
    end

    def create
        respond_to do |format|
            @lesson = Lesson.new(lesson_params)
            if @lesson.save
                format.html { redirect_to lessons_path, notice: "Succesfully created lesson" }
                format.json { head :no_content }
            else
                format.html { render action: :edit, error: "Failed to create lesson" }
                format.json { render json: @lesson.errors, status: :unprocessable_entity }
            end
        end
    end

    def update
        respond_to do |format|
            if @lesson.update(lesson_params)
                format.html { redirect_to lessons_path, notice: "Succesfully updated lesson" }
                format.json { head :no_content }
            else
                format.html { render action: :edit, error: "Failed to update lesson" }
                format.json { render json: @lesson.errors, status: :unprocessable_entity }
            end
        end
    end

    # DELETE /images/:id
    def destroy
        respond_to do |format|
            if @lesson.destroy
                format.html { redirect_to lessons_path, notice: "Succesfully deleted lesson" }
                format.json { head :no_content }
            else
                format.html { redirect_to lessons_path, error: "Failed to delete lesson" }
                format.json { render json: @lesson.errors, status: :unprocessable_entity }
            end
        end
    end

    private
    def lesson_params
        params.require(:lesson).permit(:id, :grade, :title, :background, :pdf, :author_ids => [], :image_ids => [], :attachment_ids => [])
    end

    def set_lesson
      @lesson = Lesson.find(params[:id])
    end
end
