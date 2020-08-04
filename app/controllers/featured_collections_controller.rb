class FeaturedCollectionsController < ApplicationController
  before_action :set_featured_collection, only: [:update, :edit, :destroy]

  def new
    @prompt = 'Create'
  end

  def edit
    @prompt = 'Update'
  end

  # def update
  #   respond_to do |format|
  #     if @topic.update(topic_params)
  #       format.html { redirect_to admin_path, notice: "Succesfully updated '#{@topic.title}'" }
  #       format.json { head :no_content }
  #     else
  #       format.html { redirect_to admin_path, error: "Failed to update '#{@topic.title}'" }
  #       format.json { render json: @topic.errors, status: :unprocessable_entity }
  #     end
  #   end
  # end

  def destroy
    respond_to do |format|
      if @featured_collection.destroy
        format.html { redirect_to fcadmin_path, notice: "Succesfully removed '#{@featured_collection.title}'" }
        format.json { head :no_content }
      else
        format.html { redirect_to fcadmin_path, error: "Failed to remove '#{@featured_collection.title}'" }
        format.json { render json: @featured_collection.errors, status: :unprocessable_entity }
      end
    end
  end

  def create
    respond_to do |format|
      @featured_collection = FeaturedCollection.new(featured_collection_params)

      if @featured_collection.save
        format.html { redirect_to fcadmin_path, notice: "Successfully added '#{@featured_collection.title}'" }
        format.json { head :no_content }
      else
        format.html { redirect_to fcadmin_path, error: "Failied to add '#{@featured_collection.title}'" }
        format.json { render json: @featured_collection.errors, status: :unprocessable_entity }
      end
    end
  end

  private

  def set_featured_collection
    @featured_collection = FeaturedCollection.find(params[:id])
  end

  def featured_collection_params
    params.require(:featured_collection).permit(:id, :title)
  end
end
