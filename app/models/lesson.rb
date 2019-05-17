class Lesson < ApplicationRecord
  include Elasticsearch::Model
  include Elasticsearch::Model::Callbacks

  #Elasticsearch index name
  index_name 'marchand-lessons'

  after_update -> { __elasticsearch__.index_document }

  has_one_attached :pdf

  has_many :lesson_topic_assignments, dependent: :destroy
  has_many :topics, through: :lesson_topic_assignments

  has_many :lesson_region_assignments, dependent: :destroy
  has_many :regions, through: :lesson_region_assignments

  has_many :lesson_data_cal_standards, dependent: :destroy
  has_many :cal_standards, through: :lesson_data_cal_standards

  has_many :lesson_data_nat_standards, dependent: :destroy
  has_many :nat_standards, through: :lesson_data_nat_standards

  has_many :lesson_authors, dependent: :destroy
  has_many :authors, through: :lesson_authors

  has_many :lesson_images, dependent: :destroy
  has_many :images, through: :lesson_images

  # accepts_nested_attributes_for :images

  has_many :attachments, dependent: :destroy

  # @param query - text to search for
  # @param filter - array of hashes in the form of ElasticSearch's filter parameter for queryDSL
  def self.search(query, filter)
    q = {
      bool: {
        must: query
      }
    }

    q[:bool][:filter] = filter.split(',') unless filter.empty?

    puts "######################### INSIDE 'search' response from elastic search #{__elasticsearch__.search(query: q)}"
    __elasticsearch__.search(query: q)
  end

  def as_indexed_json(*)
    as_json(
      include: {
        lesson_topic_assignments: { only: :topic_id },
        lesson_region_assignments: { only: :region_id },
        lesson_data_cal_standards: { only: :cal_standard_id },
        lesson_data_nat_standards: { only: :nat_standard_id },
        lesson_authors: { only: :author_id }
      }
    )
  end
end
