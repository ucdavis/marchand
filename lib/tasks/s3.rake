require 'rake'
require "#{Rails.root}/app/helpers/aws_helper"
require "#{Rails.root}/app/helpers/site_helper"
include SiteHelper

namespace :s3 do
	task :upload_thumbnails => :environment do
		file = File.open("#{Rails.root}/s3_error.txt", "w")
		imgs = Image.where(:thumbnail => "").where.not(:s3 => nil).where.not(:s3 => "")
		i = 0
		count = imgs.size
		imgs.each do |img|
			begin
				puts "Uploading #{i}/#{count}"
				puts img.s3
				get_thumbnail img
			rescue => error
				file.puts "#{img.s3}\n"
				file.puts "#{error}\n"
			ensure
				i = i + 1
			end
		end

		file.close
	end
end
