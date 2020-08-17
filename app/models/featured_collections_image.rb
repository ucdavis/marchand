class FeaturedCollectionsImage < ApplicationRecord
  include EsConcern

  belongs_to :featured_collection
  belongs_to :image

  validates_presence_of :featured_collection_id
  validates_presence_of :image_id
end
