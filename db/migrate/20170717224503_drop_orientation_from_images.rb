class DropOrientationFromImages < ActiveRecord::Migration[5.0]
  def change
    remove_column :images, :orientation
  end
end
