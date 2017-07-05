class DataCalStandard < ApplicationRecord
  include EsConcern

  validates_presence_of :image
  validates_presence_of :cal_standard

  belongs_to :image
  belongs_to :cal_standard
end
