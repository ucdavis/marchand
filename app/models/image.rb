class Image < ActiveRecord::Base
    include Elasticsearch::Model
    include Elasticsearch::Model::Callbacks

    has_many :topic_assignments, dependent: :destroy
    has_many :topics, through: :topic_assignments

    has_many :region_assignments, dependent: :destroy
    has_many :regions, through: :region_assignments

    has_many :data_cal_standards, dependent: :destroy
    has_many :cal_standards, through: :data_cal_standards

    has_many :data_nat_standards, dependent: :destroy
    has_many :nat_standards, through: :data_nat_standards

    belongs_to :collection

    # accepts_nested_attributes_for :topic_assignments
    # accepts_nested_attributes_for :region_assignments
    # accepts_nested_attributes_for :data_cal_standards
    # accepts_nested_attributes_for :data_nat_standards

    # @param query - text to search for
    # @param filter - array of hashes in the form of ElasticSearch's filter parameter for queryDSL
    def self.search(query, filter)
        q = {
            bool: {
                must: query
            }
        }

        q[:bool][:filter] = filter.split(",") unless filter.empty?
        __elasticsearch__.search({ query: q })
    end

    def as_indexed_json(options={})
        self.as_json(
            include: { topic_assignments: { only: :topic_id},
            region_assignments: { only: :region_id },
            data_cal_standards: { only: :cal_standard_id },
            data_nat_standards: { only: :nat_standard_id }
        })
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
