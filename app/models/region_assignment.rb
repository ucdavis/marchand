class RegionAssignment < ActiveRecord::Base
  include EsConcern

  belongs_to :image
  belongs_to :region

  validates_presence_of :image
  validates_presence_of :region
end
