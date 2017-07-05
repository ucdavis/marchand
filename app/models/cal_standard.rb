class CalStandard < ActiveRecord::Base
  has_many :data_cal_standards
  NAME = 'California Standard'.freeze

  def label
    grade_id = self.grade_id.zero? ? 'K' : self.grade_id

    "#{grade_id}.#{standard_id} - #{description}"
  end

  # Calling this function 'name' breaks rails
  def self.readable_class_name
    NAME
  end

  alias_method :title, :label
end
