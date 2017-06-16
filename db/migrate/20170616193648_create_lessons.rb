class CreateLessons < ActiveRecord::Migration[5.0]
  def change
    create_table :lessons do |t|
      t.string :grade
      t.string :title
      t.string :background

      t.timestamps
    end
  end
end
