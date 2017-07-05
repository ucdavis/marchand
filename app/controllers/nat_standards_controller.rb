class NatStandardsController < ApplicationController
  before_action :set_nat_standard, only: [:update, :edit, :destroy]

  def new
    NatStandard.new
    @prompt = 'Create'
  end

  def edit
    @prompt = 'Update'
  end

  def update
    respond_to do |format|
      if @nat_standard.update(nat_standard_params)
        format.html do
          redirect_to admin_path,
                      notice: "Successfully updated '#{@nat_standard.label}'"
        end
        format.json { head :no_content }
      else
        format.html { redirect_to admin_path, error: "Failed to update '#{@nat_standard.label}'" }
        format.json { render json: @nat_standard.errors, status: :unprocessable_entity }
      end
    end
  end

  def destroy
    respond_to do |format|
      if @nat_standard.destroy
        format.html { redirect_to :back, notice: "Successfully removed '#{@nat_standard.label}'" }
        format.json { head :no_content }
      else
        format.html { redirect_to :back, error: "Failed to remove '#{@nat_standard.label}'" }
        format.json { render json: @nat_standard.errors, status: :unprocessable_entity }
      end
    end
  end

  def create
    respond_to do |format|
      @nat_standard = NatStandard.new(nat_standard_params)
      if @nat_standard.save
        format.html { redirect_to :back, notice: "Successfully created '#{@nat_standard.label}'" }
        format.json { head :no_content }
      else
        format.html { redirect_to :back, error: "Failed to create '#{@nat_standard.label}'" }
        format.json { render json: @nat_standard.errors, status: :unprocessable_entity }
      end
    end
  end

  private

  def set_nat_standard
    @nat_standard = NatStandard.find(params[:id])
  end

  def nat_standard_params
    params.require(:nat_standard).permit(:id, :era, :us_world, :title)
  end
end
