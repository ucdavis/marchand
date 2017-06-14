class Author < ApplicationRecord
    has_many :image_authors
    has_many :images, through: :image_authors
end
