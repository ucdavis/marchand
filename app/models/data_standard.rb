class DataStandard < ActiveRecord::Base
    belongs_to :image
    belongs_to :cal_standard

    def self.cal_standards
        all.where(:stype => 0)
    end

    def self.nat_standards
        all.where(:stype => 1)
    end
end
