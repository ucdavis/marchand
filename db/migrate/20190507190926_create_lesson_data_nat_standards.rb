class CreateLessonDataNatStandards < ActiveRecord::Migration[5.2]
  def change
    create_table :lesson_data_nat_standards do |t|
      t.integer :lesson_id
      t.integer :nat_standard_id

      t.timestamps
    end
  end
end
