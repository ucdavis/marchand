class Topic < ActiveRecord::Base
  has_many :topic_assignments, dependent: :destroy
  has_many :images, through: :topic_assignments

  has_many :lesson_topic_assignments, dependent: :destroy
  has_many :lessons, through: :lesson_topic_assignments
end
