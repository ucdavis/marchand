class TopicAssignment < ActiveRecord::Base
  validates_presence_of :topic_id, :image_id
  
  belongs_to :images
  belongs_to :topics
end
