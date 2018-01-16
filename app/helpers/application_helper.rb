require 'csv'

module ApplicationHelper
  # WHITELIST = %w[cthielen guilden kkipp22 sbgreer jeremy safutrel].freeze

  def admin?
    if session[:cas_user].present?
      return true if User.find_by(loginid: session[:cas_user]).present?
    end

    false
  end
end
