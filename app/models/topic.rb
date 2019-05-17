class Topic < ActiveRecord::Base
  has_many :image_topic_assignments, dependent: :destroy
  has_many :images, through: :image_topic_assignments

  has_many :lesson_topic_assignments, dependent: :destroy
  has_many :lessons, through: :lesson_topic_assignments
end
