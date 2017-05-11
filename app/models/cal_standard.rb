class CalStandard < ActiveRecord::Base
    has_many :data_cal_standards
    NAME = "California Standard"

  def label
    grade_id = (self.grade_id == 0) ? "K" : self.grade_id

    return "#{grade_id}.#{self.standard_id} - #{self.description}"
  end

  # Calling this function 'name' breaks rails
  def self.readable_class_name
      return "California Standard"
  end

  alias_method :title, :label
end
