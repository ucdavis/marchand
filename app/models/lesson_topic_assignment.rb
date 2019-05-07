class LessonTopicAssignment < ApplicationRecord
  validates_presence_of :topic_id

  belongs_to :lesson
  belongs_to :topic
end
