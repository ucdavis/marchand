class FeaturedCollectionsImage < ApplicationRecord
  belongs_to :featured_collection
  belongs_to :image
end
