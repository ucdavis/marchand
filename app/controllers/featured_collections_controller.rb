class FeaturedCollectionsController < ApplicationController
  before_action :set_featured_collection, only: [:update, :edit, :destroy]

  def index
    cache_key = Image.maximum(:updated_at).try(:utc).try(:to_s, :number).to_s

    @cards = Rails.cache.fetch("#{cache_key}/featured_collections", expires_in: 72.hours) do
      cards = []
      FeaturedCollection.where.not(id: nil).each do |featured_collection|
        cards << {
          image: Image.joins(:featured_collections_images)
                      .where(public: 1, 'featured_collections_images.featured_collection_id': featured_collection.id)
                      .order('RAND()')
                      .first,
          featured_collection: featured_collection
        }
      end
      cards
    end
  end

  def new
    @prompt = 'Create'
  end

  def edit
    @prompt = 'Update'
  end

  def update
    respond_to do |format|
      if @featured_collection.update(featured_collection_params)
        format.html { redirect_to fcadmin_path, notice: "Succesfully updated '#{@featured_collection.title}'" }
        format.json { head :no_content }
      else
        format.html { redirect_to fcadmin_path, error: "Failed to update '#{@featured_collection.title}'" }
        format.json { render json: @featured_collection.errors, status: :unprocessable_entity }
      end
    end
  end

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
    params.require(:featured_collection).permit(:id, :title, :public, :description)
  end
end
