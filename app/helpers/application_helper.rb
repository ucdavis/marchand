require 'csv'

module ApplicationHelper
    def to_csv(obj, attributes)
        CSV.generate(headers: true) do |csv|
            csv << attributes

            obj.all.each do |instance|
                csv << attributes.map{ |attr| instance.send(attr) }
            end
        end
    end

    def isAdmin?
        return false
        # Implement CAS check if user is whitelisted
    end
end
