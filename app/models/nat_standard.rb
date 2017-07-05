class NatStandard < ActiveRecord::Base
  validates_presence_of :era
  validates_presence_of :us_world
  validates_presence_of :title

  has_many :data_nat_standards

  def label
    prefix = us_world.zero? ? 'US' : 'World'

    "#{prefix} Era #{era} - #{title}"
  end

  def self.readable_class_name
    'National Standard'.freeze
  end
end
