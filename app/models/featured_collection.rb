class FeaturedCollection < ActiveRecord::Base
  validates_presence_of :title

  has_many :featured_collections_images, dependent: :destroy
  has_many :images, through: :featured_collection_images
end
