class ChangeColumnNamesForRegionAssignments < ActiveRecord::Migration[5.0]
  def change
      rename_column :region_assignments, :rid, :region_id
      rename_column :region_assignments, :sid, :image_id
  end
end
