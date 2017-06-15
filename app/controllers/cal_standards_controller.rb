class CalStandardsController < ApplicationController
    before_action :set_cal_standard, only: [:update, :edit, :destroy]

    def new
        @prompt = "Create"
    end

    def edit
        @prompt = "Update"
    end

    def update
        respond_to do |format|
            if @cal_standard.update(cal_standard_params)
                format.html { redirect_to admin_path, notice: "Successfully updated '#{@cal_standard.label}'" }
                format.json { head :no_content }
            else
                format.html { redirect_to admin_path, error: "Failed to update '#{@cal_standard.label}'" }
                format.json { render json: @cal_standard.errors, status: :unprocessable_entity }
            end
        end
    end

    def destroy
        respond_to do |format|
            if @cal_standard.destroy
                format.html { redirect_to :back, notice: "Successfully removed '#{@cal_standard.label}'" }
                format.json { head :no_content }
            else
                format.html { redirect_to :back, error: "Failed to remove '#{@cal_standard.label}'" }
                format.json { render json: @cal_standard.errors, status: :unprocessable_entity }
            end
        end
    end

    def create
        respond_to do |format|
            @cal_standard = CalStandard.new(cal_standard_params)
            if @cal_standard.save
                format.html { redirect_to :back, notice: "Successfully created '#{@cal_standard.label}'" }
                format.json { head :no_content }
            else
                format.html { redirect_to :back, error: "Failed to create '#{@cal_standard.label}'" }
                format.json { render json: @cal_standard.errors, status: :unprocessable_entity }
            end
        end
    end

    private
    def set_cal_standard
        @cal_standard = CalStandard.find(params[:id])
    end

    def cal_standard_params
        params.require(:cal_standard).permit(:id, :grade_id, :standard_id, :description)
    end
end
