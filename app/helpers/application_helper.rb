require 'csv'

module ApplicationHelper
    WHITELIST = %w(cthielen msdiez guilden kkipp22 sbgreer jeremy shangl2)

    def to_csv(obj, attributes)
        CSV.generate(headers: true) do |csv|
            csv << attributes

            obj.all.each do |instance|
                csv << attributes.map{ |attr| instance.send(attr) }
            end
        end
    end

    def isAdmin?
        if session[:cas_user].present?
            return true if WHITELIST.include?(session[:cas_user])
        end

        return false
    end
end
