class Topic < ActiveRecord::Base
  has_many :topic_assignments, dependent: :destroy
end
