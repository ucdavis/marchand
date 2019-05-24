class RemovePdfFromLessons < ActiveRecord::Migration[5.2]
  def change
    remove_column :lessons, :pdf, :string
  end
end
