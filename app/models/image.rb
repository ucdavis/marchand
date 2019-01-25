class Image < ActiveRecord::Base
  include Elasticsearch::Model
  include Elasticsearch::Model::Callbacks

  # Elasticsearch index name
  index_name ENV['ELASTICSEARCH_IMAGES_INDEX'] || 'marchand-images'

  after_update -> { __elasticsearch__.index_document }

  has_one_attached :original

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
  validate :original_type

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

  def preview
    orientation = self.orientation

    if orientation == "portrait"
      self.original.variant(combine_options: { resize: "x600" }) do |cols, rows, passed_img|
        return passed_img.variant(combine_options: { extent: "#{cols}x#{rows}", gravity: "center" }).processed
      end
    else
      self.original.variant(combine_options: { resize: "500>" }) do |cols, rows, passed_img|
        return passed_img.variant(combine_options: { extent: "#{cols}x#{rows}", gravity: "center" }).processed
      end
    end
  end

  def thumbnail
    return self.original.variant(combine_options: { resize: "275>", extent: "275x190", gravity: "center" }).processed
  end

  def as_indexed_json(*)
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
    original_width = self.original.metadata[:width].to_f
    original_height = self.original.metadata[:height].to_f

    original_width && original_width < original_height ? 'portrait' : 'landscape'
  end

  private
  def original_type
    if original.attached? == false
      errors.add(:image, 'must be attached')
    elsif original.byte_size > 25.megabytes
      errors.add(:image, 'size must be less than 25 MB')
    end
  end
end
