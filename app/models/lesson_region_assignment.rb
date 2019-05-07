class LessonRegionAssignment < ApplicationRecord
  belongs_to :lesson
  belongs_to :region

  validates_presence_of :lesson
  validates_presence_of :region
end
