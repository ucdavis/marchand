class Image < ActiveRecord::Base
    include Elasticsearch::Model
    include Elasticsearch::Model::Callbacks

    has_many :topic_assignments, dependent: :destroy
    belongs_to :collection

    def topics
        topics = []
        self.topic_assignments.each do |topic_assignment|
            topics << topic_assignment.topic.title
        end
        topics.join(",")
    end
end
