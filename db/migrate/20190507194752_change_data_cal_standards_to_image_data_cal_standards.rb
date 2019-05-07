class ChangeDataCalStandardsToImageDataCalStandards < ActiveRecord::Migration[5.2]
  def change
    rename_table :data_cal_standards, :image_data_cal_standards
  end
end
