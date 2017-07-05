class LessonImage < ApplicationRecord
  belongs_to :lesson
  belongs_to :image
end
