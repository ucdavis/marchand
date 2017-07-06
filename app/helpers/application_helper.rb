require 'csv'

module ApplicationHelper
  WHITELIST = %w[cthielen msdiez guilden kkipp22 sbgreer jeremy shangl2].freeze

  def admin?
    if session[:cas_user].present?
      return true if WHITELIST.include?(session[:cas_user])
    end

    false
  end
end
