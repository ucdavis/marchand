class AddThumbnailMetadata < ActiveRecord::Migration[5.0]
  def change
    # Remove unused columns
    remove_column :images, :start_year
    remove_column :images, :end_year
    remove_column :images, :views
    remove_column :images, :file

    # Add a 'preview' thumbnail, larger than the thumbnail but not as large as the original
    add_column :images, :preview, :string, :length => 128
    add_column :images, :preview_width, :integer
    add_column :images, :preview_height, :integer

    # Add w/h to the thumbnail
    add_column :images, :thumbnail_width, :integer
    add_column :images, :thumbnail_height, :integer

    # Rename 's3' to 'original'
    rename_column :images, :s3, :original
    add_column :images, :original_width, :integer
    add_column :images, :original_height, :integer

    # Clarify purpose by renaming 'view' column
    rename_column :images, :view, :orientation
  end
end
