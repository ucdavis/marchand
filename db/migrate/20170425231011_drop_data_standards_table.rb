class DropDataStandardsTable < ActiveRecord::Migration[5.0]
  def change
      drop_table :data_standards
  end
end
