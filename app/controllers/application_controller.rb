class ApplicationController < ActionController::Base
  protect_from_forgery with: :exception
  include ApplicationHelper
  rescue_from ActionController::UnknownFormat, with: :bad_request

  protected

  def bad_request
    render plain: 'Bad request', status: 400
  end
end
