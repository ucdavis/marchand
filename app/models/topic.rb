class Topic < ActiveRecord::Base
  has_many :topic_assignments, dependent: :destroy
  has_many :images, through: :topic_assignments
end
