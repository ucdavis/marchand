class Image < ActiveRecord::Base
    include Elasticsearch::Model
    include Elasticsearch::Model::Callbacks

    has_many :topic_assignments, dependent: :destroy
    has_many :region_assignments
    has_many :data_cal_standards
    has_many :data_nat_standards
    belongs_to :collection

    def topics
        topics = []
        self.topic_assignments.each do |topic_assignment|
            topics << topic_assignment.topic.title
        end
        topics.join("@delim@")
    end

    def regions
        regions = []
        self.region_assignments.each do |region_assignment|
            regions << region_assignment.region.title
        end
        regions.join("@delim@")
    end

    def cal_standards
        cal_standards = []
        self.data_cal_standards.each do |data|
            cal_standards << data.cal_standard.label
        end

        cal_standards.join("@delim@")
    end

    def nat_standards
        nat_standards = []
        self.data_nat_standards.each do |data|
            nat_standards << data.nat_standard.label
        end

        nat_standards.join("@delim@")
    end
end
