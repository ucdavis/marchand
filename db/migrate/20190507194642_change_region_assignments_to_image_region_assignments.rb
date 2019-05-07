class ChangeRegionAssignmentsToImageRegionAssignments < ActiveRecord::Migration[5.2]
  def change
    rename_table :region_assignments, :image_region_assignments
  end
end
