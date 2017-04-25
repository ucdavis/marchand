class Region < ActiveRecord::Base
  validates_presence_of :title

  has_many :region_assignments
end
