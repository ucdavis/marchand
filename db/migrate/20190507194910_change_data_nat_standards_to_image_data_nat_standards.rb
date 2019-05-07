class ChangeDataNatStandardsToImageDataNatStandards < ActiveRecord::Migration[5.2]
  def change
    rename_table :data_nat_standards, :image_data_nat_standards
  end
end
