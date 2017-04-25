class NatStandard < ActiveRecord::Base
    validates_presence_of :era
    validates_presence_of :us_world
    validates_presence_of :title

    has_many :data_nat_standards

    def label
        prefix = self.us_world == 0 ? "US" : "World"

        return "#{prefix} Era #{self.era} - #{self.title}"
    end
end
