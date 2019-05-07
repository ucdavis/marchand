class ChangeTopicAssignmentsToImageTopicAssignments < ActiveRecord::Migration[5.2]
  def change
    rename_table :topic_assignments, :image_topic_assignments
  end
end
