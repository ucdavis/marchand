class DataNatStandard < ApplicationRecord
    validates_presence_of :image
    validates_presence_of :nat_standard

    belongs_to :image
    belongs_to :nat_standard
end
