class AddPublicToggleAndDescriptionToFeaturedCollections < ActiveRecord::Migration[5.2]
  def change
    add_column :featured_collections, :public, :boolean
    add_column :featured_collections, :description, :text
  end
end
