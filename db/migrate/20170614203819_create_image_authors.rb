class CreateImageAuthors < ActiveRecord::Migration[5.0]
  def change
    create_table :image_authors do |t|
      t.integer :image_id
      t.integer :author_id

      t.timestamps
    end
  end
end
