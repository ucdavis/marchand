class ApplicationController < ActionController::Base
    protect_from_forgery with: :exception
    before_action :set_filters
    include SiteHelper

    def set_filters
        @regions = Region.select(:id, :title).uniq
        @collections = Collection.select(:id, :name).uniq
        @topics = Topic.select(:id, :title).uniq
        @ca_standards = CalStandard.select(:id, :grade_id, :standard_id, :description).order(:grade_id, :standard_id)
    end
end
