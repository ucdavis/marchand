class GalleryController < ApplicationController
  before_action :set_filters

	private

  def set_filters
    @regions = Region.select(:id, :title).distinct
    @collections = Collection.select(:id, 'name as title').distinct
    @topics = Topic.select(:id, :title).distinct
    @ca_standards = CalStandard.select(:id, :grade_id, :standard_id, :description).order(:grade_id, :standard_id)

    @filters = []
    @filters << @regions
    @filters << @collections
    @filters << @topics
    @filters << @ca_standards
  end
end
