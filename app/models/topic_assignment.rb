class TopicAssignment < ActiveRecord::Base
    include EsConcern
    validates_presence_of :topic_id, :image_id

    belongs_to :image
    belongs_to :topic
end
