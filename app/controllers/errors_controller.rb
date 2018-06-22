class ErrorsController < ApplicationController
  def error_404
    @requested_path = request.path
    render plain: "No such path #{@requested_path}", status: 404
  end
end
