class RegionsController < ApplicationController
    before_action :set_region, only: [:edit, :update, :destroy]

    def new
        @prompt = "Create"
    end

    def edit
        @prompt = "Update"
    end

    def update
        respond_to do |format|
            if @region.update(region_params)
                format.html { redirect_to admin_path, notice: "Succesfully updated '#{@region.title}'"}
                format.json { head :no_content }
            else
                format.html { redirect_to admin_path, notice: "Failed to update '#{@region.title}'" }
                format.json { render json: @region.errors, status: :unprocessable_entity }
            end
        end
    end

    def destroy
        respond_to do |format|
            if @region.destroy
                format.html { redirect_to admin_path, notice: "Succesfully removed '#{@region.title}'" }
                format.json { head :no_content }
            else
                format.html { redirect_to admin_path, notice: "Failed to remove '#{@region.title}'" }
                format.json { render json: @region.errors, status: :unprocessable_entity }
            end
        end
    end

    def create
        respond_to do |format|
            @region = Region.new(region_params)
            if @region.save
                format.html { redirect_to :back, notice: "Succesfully created '#{@region.title}'"}
                format.json { head :no_content }
            else
                format.html { redirect_to :back, notice: "Failed to create '#{@region.title}'" }
                format.json { render json: @region.errors, status: :unprocessable_entity }
            end
        end
    end

    private
    def set_region
        @region = Region.find(params[:id])
    end

    def region_params
        params.require(:region).permit(:id, :title)
    end
end
