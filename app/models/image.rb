class Image < ActiveRecord::Base
    include Elasticsearch::Model
    include Elasticsearch::Model::Callbacks

    has_many :topic_assignments, dependent: :destroy
    has_many :region_assignments
    has_many :data_cal_standards
    has_many :data_nat_standards
    belongs_to :collection

    # @param query - text to search for
    # @param filter - arrat of hashes in the form of ElasticSearch's filter parameter for queryDSL
    def self.search(query, filter)
        q = {
            bool: {
                must: query
            }
        }

        q[:bool][:filter] = filter.split(",") unless filter.empty?
        __elasticsearch__.search({ query: q })
    end

    def topics
        topics = []
        self.topic_assignments.each do |topic_assignment|
            topics << topic_assignment.topic.title if topic_assignment.topic.title.present?
        end
        topics.join("@delim@")
    end

    def regions
        regions = []
        self.region_assignments.each do |region_assignment|
            regions << region_assignment.region.title if region_assignment.region.title.present?
        end
        regions.join("@delim@")
    end

    def cal_standards
        cal_standards = []
        self.data_cal_standards.each do |data|
            cal_standards << data.cal_standard.label if data.cal_standard.label.present?
        end

        cal_standards.join("@delim@")
    end

    def nat_standards
        nat_standards = []
        self.data_nat_standards.each do |data|
            nat_standards << data.nat_standard.label if data.nat_standard.present?
        end

        nat_standards.join("@delim@")
    end
end
