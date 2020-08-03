class CreateFeaturedCollectionsImages < ActiveRecord::Migration[5.2]
  def change
    create_table :featured_collections_images do |t|
      t.integer :featured_collection_id
      t.integer :image_id

      t.timestamps
    end
  end
end
