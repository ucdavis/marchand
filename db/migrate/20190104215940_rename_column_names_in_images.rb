class RenameColumnNamesInImages < ActiveRecord::Migration[5.2]
  def change
	rename_column :images, :original, :original_old
	rename_column :images, :preview, :preview_old
	rename_column :images, :thumbnail, :thumbnail_old
  end
end
