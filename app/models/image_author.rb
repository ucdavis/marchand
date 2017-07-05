class ImageAuthor < ApplicationRecord
  include EsConcern

  belongs_to :author
  belongs_to :image
end
