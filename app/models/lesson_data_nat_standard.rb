class LessonDataNatStandard < ApplicationRecord
  validates_presence_of :lesson
  validates_presence_of :nat_standard

  belongs_to :lesson
  belongs_to :nat_standard
end
