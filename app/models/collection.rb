class Collection < ActiveRecord::Base
  validates_presence_of :name, :code

  has_many :images
end
