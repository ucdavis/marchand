class Lesson < ApplicationRecord
  include Elasticsearch::Model
  include Elasticsearch::Model::Callbacks

  #Elasticsearch index name
  index_name 'marchand-lessons'

  after_update -> { __elasticsearch__.index_document }

  has_one_attached :pdf

  has_many :lesson_authors, dependent: :destroy
  has_many :authors, through: :lesson_authors

  has_many :lesson_images, dependent: :destroy
  has_many :images, through: :lesson_images

  accepts_nested_attributes_for :images

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
    __elasticsearch__.search(query: q)
  end

  def as_indexed_json(_)
    as_json(
      include: {
        lesson_authors: { only: :author_id },
        lesson_images: { only: :image_id }
      }
    )
  end
end
