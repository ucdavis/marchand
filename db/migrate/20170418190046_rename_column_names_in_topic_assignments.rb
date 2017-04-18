class RenameColumnNamesInTopicAssignments < ActiveRecord::Migration[5.0]
  def change
	rename_column :topic_assignments, :tid, :topic_id
	rename_column :topic_assignments, :sid, :image_id
  end
end
