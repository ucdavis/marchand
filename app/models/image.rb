class Image < ActiveRecord::Base
    include Elasticsearch::Model
    include Elasticsearch::Model::Callbacks

    has_many :topic_assignments, dependent: :destroy
    has_many :region_assignments
    belongs_to :collection

    def topics
        topics = []
        self.topic_assignments.each do |topic_assignment|
            topics << topic_assignment.topic.title
        end
        topics.join(",")
    end

    def regions
        regions = []
        self.region_assignments.each do |region_assignment|
            regions << region_assignment.region.title
        end
        regions.join(",")
    end

    def cal_standards
        cal_standards = ["TO", "DO"]

        cal_standards.join(",")
    end

    def nat_standards
        nat_standards = ["TO", "DO"]

        nat_standards.join(",")
    end

    def citations
        citations = ["TO", "DO"]
        citations.join(",")
    end
end
