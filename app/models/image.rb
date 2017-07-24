class Image < ActiveRecord::Base
  include Elasticsearch::Model
  include Elasticsearch::Model::Callbacks

  after_update -> { __elasticsearch__.index_document }

  has_many :topic_assignments, dependent: :destroy
  has_many :topics, through: :topic_assignments

  has_many :region_assignments, dependent: :destroy
  has_many :regions, through: :region_assignments

  has_many :data_cal_standards, dependent: :destroy
  has_many :cal_standards, through: :data_cal_standards

  has_many :data_nat_standards, dependent: :destroy
  has_many :nat_standards, through: :data_nat_standards

  has_many :image_authors, dependent: :destroy
  has_many :authors, through: :image_authors

  belongs_to :collection

  validates_presence_of :title, :public, :featured, :collection

  # @param query - text to search for
  # @param filter - array of hashes in the form of ElasticSearch's filter parameter for queryDSL
  def self.search(query, filter)
    q = {
      bool: {
        must: query
      }
    }

    q[:bool][:filter] = filter.split(',') unless filter.empty?

    __elasticsearch__.search(query: q)
  end

  def as_indexed_json
    as_json(
      include: {
        topic_assignments: { only: :topic_id },
        region_assignments: { only: :region_id },
        data_cal_standards: { only: :cal_standard_id },
        data_nat_standards: { only: :nat_standard_id },
        image_authors: { only: :author_id }
      }
    )
  end

  def orientation
    original_width && original_width < original_height ? 'portrait' : 'landscape'
  end
end
