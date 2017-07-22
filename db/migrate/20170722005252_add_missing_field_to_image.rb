class AddMissingFieldToImage < ActiveRecord::Migration[5.0]
  def change
    add_column :images, :missing, :boolean, :default => false
  end
end
