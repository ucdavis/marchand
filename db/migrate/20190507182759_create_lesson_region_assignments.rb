class CreateLessonRegionAssignments < ActiveRecord::Migration[5.2]
  def change
    create_table :lesson_region_assignments do |t|
      t.integer :lesson_id
      t.integer :region_id

      t.timestamps
    end
  end
end
