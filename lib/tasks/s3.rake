require 'rake'
require "#{Rails.root}/app/helpers/aws_helper"
require "#{Rails.root}/app/helpers/site_helper"
include SiteHelper

namespace :s3 do
	task :upload_thumbnails => :environment do
		imgs = Image.where(:thumbnail => "").where.not(:s3 => nil)
		i = 0
		count = imgs.size
		imgs.each do |img|
			puts "Uploading #{i}/#{count}"
			puts img.s3
			get_thumbnail img
			i = i + 1;
		end
	end
end
