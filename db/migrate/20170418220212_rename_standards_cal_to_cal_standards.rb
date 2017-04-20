class RenameStandardsCalToCalStandards < ActiveRecord::Migration[5.0]
  def change
	rename_table :standards_cal, :cal_standards
  end
end
