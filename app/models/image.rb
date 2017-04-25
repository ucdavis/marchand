class Image < ActiveRecord::Base
    include Elasticsearch::Model
    include Elasticsearch::Model::Callbacks

    has_many :topic_assignments, dependent: :destroy
end
