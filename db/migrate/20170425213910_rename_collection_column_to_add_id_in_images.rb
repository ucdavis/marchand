class RenameCollectionColumnToAddIdInImages < ActiveRecord::Migration[5.0]
  def change
	rename_column :images, :collection, :collection_id
  end
end
