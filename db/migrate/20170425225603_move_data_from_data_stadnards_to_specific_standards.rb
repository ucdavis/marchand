class MoveDataFromDataStadnardsToSpecificStandards < ActiveRecord::Migration[5.0]
  def change
    DataStandard.nat_standards.each do |ns|
        dns = DataNatStandard.new
        dns.image_id = ns.image_id
        dns.nat_standard_id = ns.sid

        dns.save!
    end

    DataStandard.cal_standards.each do |cs|
        dcs = DataCalStandard.new
        dcs.image_id = cs.image_id
        dcs.cal_standard_id = cs.sid

        dcs.save!
    end
  end
end
