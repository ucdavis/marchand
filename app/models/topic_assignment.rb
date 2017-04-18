class TopicAssignment < ActiveRecord::Base
  belongs_to :images
  belongs_to :topics
end
