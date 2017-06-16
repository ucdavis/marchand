class Lesson < ApplicationRecord
    has_many :lesson_authors, :dependent => :destroy
    has_many :authors, through: :lesson_authors

    has_many :lesson_images, :dependent => :destroy
    has_many :images, through: :lesson_images

    has_many :attachments, :dependent => :destroy
end
