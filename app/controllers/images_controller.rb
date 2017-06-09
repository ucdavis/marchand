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
            # Upload image & thumbnail to s3
            update_params = image_params
            if update_params[:s3].present?
                filename = "#{Time.now.to_i}_#{image_params[:s3].original_filename}"
                uploaded_img = Magick::Image.from_blob update_params[:s3].read
                update_params[:s3] = upload_image(filename, uploaded_img.first).public_url
                update_params[:thumbnail] = upload_new_thumbnail(update_params[:s3])
            end

            old_image = @image.s3
            old_thumbnail = @image.thumbnail
            if @image.update(update_params)
                # Remove old image & thumbnail from s3
                remove_image(old_image.split("/").last)
                remove_image(old_thumbnail.split("/").last)

                format.html { redirect_to build_search_url(@image) }
                format.json { head :no_content }
            else
                format.html { render action: :edit }
                format.json { render json: @image.errors, status: :unprocessable_entity }
            end
        end

    end

    def destroy
    end

    private
    def image_params
        params.require(:image).permit(:id, :title, :collection_id, :public, :card, :citation, :featured, :s3, {:topic_ids => []}, {:region_ids => []}, {:cal_standard_ids => []}, {:nat_standard_ids => []})
    end
    def set_image
      @image = Image.find(params[:id])
      @image.s3 = "https://thumb7.shutterstock.com/display_pic_with_logo/64260/405078397/stock-photo-business-architecture-building-construction-and-people-concept-close-up-of-architect-hands-405078397.jpg" unless @image.s3.present?
    end
end
