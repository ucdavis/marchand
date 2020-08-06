class FeaturedCollectionsImage < ApplicationRecord
  validates_presence_of :featured_collection_id
  validates_presence_of :image_id

  belongs_to :featured_collection
  belongs_to :image
end
