class CreateLessonDataCalStandards < ActiveRecord::Migration[5.2]
  def change
    create_table :lesson_data_cal_standards do |t|
      t.integer :lesson_id
      t.integer :cal_standard_id

      t.timestamps
    end
  end
end
