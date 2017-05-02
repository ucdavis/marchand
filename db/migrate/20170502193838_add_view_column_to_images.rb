class AddViewColumnToImages < ActiveRecord::Migration[5.0]
  def change
	add_column :images, :view, :string
  end
end
