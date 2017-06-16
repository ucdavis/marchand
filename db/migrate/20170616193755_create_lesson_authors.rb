class CreateLessonAuthors < ActiveRecord::Migration[5.0]
  def change
    create_table :lesson_authors do |t|
      t.integer :author_id
      t.integer :lesson_id

      t.timestamps
    end
  end
end
