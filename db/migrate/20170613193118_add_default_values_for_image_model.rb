class AddDefaultValuesForImageModel < ActiveRecord::Migration[5.0]
  def change
      change_column :images, :card, :string, :default => ""
      change_column :images, :citation, :string, :default => ""
      change_column :images, :notes, :string, :default => ""
  end
end
