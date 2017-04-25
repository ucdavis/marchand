class RenameTableStandardsDataToDataStandards < ActiveRecord::Migration[5.0]
  def change
      rename_table :standards_data, :data_standards
  end
end
