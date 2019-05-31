class GalleryController < ApplicationController
  before_action :set_filters

	private

  def set_filters
    @regions = Region.select(:id, :title).distinct
    @collections = Collection.select(:id, 'name as title').distinct
    @topics = Topic.select(:id, :title).distinct
    @cal_standards = CalStandard.select(:id, :grade_id, :standard_id, :description).order(:grade_id, :standard_id)
    @nat_standards = NatStandard.select(:id, :era, :us_world, :title).order(:era, :us_world)

    @filters = []
    @filters << @regions
    @filters << @collections
    @filters << @topics
    @filters << @cal_standards
    @filters << @nat_standards
  end
end
