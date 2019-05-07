class LessonDataCalStandard < ApplicationRecord
  validates_presence_of :lesson
  validates_presence_of :cal_standard

  belongs_to :lesson
  belongs_to :cal_standard
end
