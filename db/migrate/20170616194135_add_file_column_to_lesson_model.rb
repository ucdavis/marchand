class AddFileColumnToLessonModel < ActiveRecord::Migration[5.0]
  def change
      add_column :lessons, :pdf, :string
  end
end
