class AddStartEndYearToImages < ActiveRecord::Migration[5.0]
  def change
	add_column :images, :start_year, :integer
	add_column :images, :end_year, :integer
  end
end
