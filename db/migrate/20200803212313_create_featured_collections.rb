class CreateFeaturedCollections < ActiveRecord::Migration[5.2]
  def change
    create_table :featured_collections do |t|
      t.string :title

      t.timestamps
    end
  end
end
