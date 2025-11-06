require 'csv'

module ApplicationHelper
  # WHITELIST = %w[cthielen guilden kkipp22 sbgreer jeremy safutrel].freeze

  def admin?
    if cas_login.present?
      return true if User.find_by(loginid: cas_login).present?
    end

    false
  end

  def cas_login
    session.dig('cas', 'user')
  end
end
