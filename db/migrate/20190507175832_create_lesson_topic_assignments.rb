class CreateLessonTopicAssignments < ActiveRecord::Migration[5.2]
  def change
    create_table :lesson_topic_assignments do |t|
      t.integer :lesson_id
      t.integer :topic_id

      t.timestamps
    end
  end
end
