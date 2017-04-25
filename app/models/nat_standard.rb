class NatStandard < ActiveRecord::Base
    validates_presence_of :era
    validates_presence_of :us_world
    validates_presence_of :title
    has_many :data_nat_standards
end
