class User < ApplicationRecord
  validates_uniqueness_of :loginid, allow_nil: false
end
