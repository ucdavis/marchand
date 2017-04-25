class AddColumnsToDataStandards < ActiveRecord::Migration[5.0]
  def change
      add_column :data_cal_standards, :image_id, :integer
      add_column :data_nat_standards, :image_id, :integer
  end
end
