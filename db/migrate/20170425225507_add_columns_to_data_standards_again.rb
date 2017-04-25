class AddColumnsToDataStandardsAgain < ActiveRecord::Migration[5.0]
  def change
      add_column :data_cal_standards, :cal_standard_id, :integer
      add_column :data_nat_standards, :nat_standard_id, :integer
  end
end
