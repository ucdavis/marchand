class CalStandard < ActiveRecord::Base
  has_many :image_data_cal_standards
  has_many :lesson_data_cal_standards
  NAME = 'California Standard'.freeze

  def label
    grade_id = self.grade_id.zero? ? 'K' : self.grade_id

    "#{grade_id}.#{standard_id} - #{description}"
  end

  # Calling this function 'name' breaks rails
  def self.readable_class_name
    NAME
  end

  alias title label
end
