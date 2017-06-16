class LessonsController < ApplicationController
    before_action :set_lesson, only: [:show, :edit, :update, :destroy]

    def index
    end

    def show
    end

    def new
        @lesson = Lesson.new
    end

    def create
    end

    def edit
    end

    def update
    end

    # DELETE /images/:id
    def destroy
    end

    private
    def lesson_params
        params.require(:lesson)
    end

    def set_lesson
      @lesson = Lesson.find(params[:id])
    end
end
