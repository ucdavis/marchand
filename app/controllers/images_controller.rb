class ImagesController < ApplicationController
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
    end

    def destroy
    end

    private
    def image_params
        # params.require(:image).permit(:developer_id, :email, :account_type)
    end
    def set_image
      @image = Image.find(params[:id])
      @image.s3 = "https://thumb7.shutterstock.com/display_pic_with_logo/64260/405078397/stock-photo-business-architecture-building-construction-and-people-concept-close-up-of-architect-hands-405078397.jpg" unless @image.s3.present?
    end
end
