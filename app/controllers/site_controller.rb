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

  def download
    # Rails translate /file.jpg as key: file, format: jpg

    key = "#{params[:key]}.#{params[:format]}"

    file = get_object(key)

    send_data file.body.read,
              filename: key,
              type: file.content_type,
              disposition: 'attachment'
  end

  def login
    redirect_to '/'
  end

  def logout
    CASClient::Frameworks::Rails::Filter.logout(self)
  end
end
