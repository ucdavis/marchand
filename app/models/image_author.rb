class ImageAuthor < ApplicationRecord
    belongs_to :author
    belongs_to :image
end
