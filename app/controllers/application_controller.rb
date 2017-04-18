class ApplicationController < ActionController::Base
  protect_from_forgery with: :exception
  before_action :set_filters

  def set_filters
    @regions = Regions.select(:title).uniq
    @collections = Collection.select(:name).uniq
    @topics = Topic.select(:title).uniq
    @ca_standard = CalStandard.select(:id, :grade_id, :standard_id, :description).order(:grade_id, :standard_id)

  end
end
