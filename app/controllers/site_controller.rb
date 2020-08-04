require 'casclient'
require 'casclient/frameworks/rails/filter'

class SiteController < ApplicationController
  before_action CASClient::Frameworks::Rails::Filter, only: [:login]

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
    redirect_to '/'
  end

  def logout
    CASClient::Frameworks::Rails::Filter.logout(self)
  end
end
