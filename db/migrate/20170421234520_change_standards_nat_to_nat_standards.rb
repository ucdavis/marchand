class ChangeStandardsNatToNatStandards < ActiveRecord::Migration[5.0]
  def change
      rename_table :standards_nat, :nat_standards
  end
end
