class ImagesController < ApplicationController
    include ImagesHelper

    before_action :set_image, only: [:show, :edit, :update, :destroy]

    def new
        @image = Image.new
    end

    def create
        @image = Image.new(image_params)
        # . . .
    end

    def edit
    end

    def update
        respond_to do |format|
            if @image.update(image_params)
                puts "UPDATE SUCCEEDED"
                format.html { redirect_to build_search_url(@image) }
                format.json { head :no_content }
            else
                puts "UPDATE FAILED"
                puts @image.errors.full_messages
                format.html { render action: :edit }
                format.json { render json: @image.errors, status: :unprocessable_entity }
            end
        end

    end

    def destroy
    end

    private
    def image_params
        params.require(:image).permit(:id, :title, :collection_id, :public, :card, :citation, :featured, {:topic_ids => []}, {:region_ids => []}, {:cal_standard_ids => []}, {:nat_standard_ids => []})
    end
    def set_image
      @image = Image.find(params[:id])
      @image.s3 = "https://thumb7.shutterstock.com/display_pic_with_logo/64260/405078397/stock-photo-business-architecture-building-construction-and-people-concept-close-up-of-architect-hands-405078397.jpg" unless @image.s3.present?
    end
end
