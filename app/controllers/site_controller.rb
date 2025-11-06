class SiteController < ApplicationController
  before_action :authenticate, only: [:login]

  def admin
    redirect_to root_url unless admin?

    @author = Author.new
    @topic = Topic.new
    @region = Region.new
    @cal_standard = CalStandard.new
    @nat_standard = NatStandard.new
  end

  def fcadmin
    redirect_to root_url unless admin?

    @featured_collection = FeaturedCollection.new
  end

  def login
    unless cas_login.present?
      head :unauthorized
      return
    end

    redirect_to '/'
  end

  private

  def authenticate
    return if cas_login.present?

    head :unauthorized
  end
end
