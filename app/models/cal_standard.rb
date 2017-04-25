class CalStandard < ActiveRecord::Base
    has_many :data_cal_standards
  def label
    grade_id = (self.grade_id == 0) ? "K" : self.grade_id

    return "#{grade_id}.#{self.standard_id} - #{self.description}"
  end
end
