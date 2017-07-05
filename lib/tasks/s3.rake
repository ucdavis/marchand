require 'rake'
require 'open-uri'
require 'digest'
require "#{Rails.root}/app/helpers/aws_helper"
require "#{Rails.root}/app/helpers/site_helper"
include SiteHelper

namespace :s3 do
  # Uploads thumbnails to s3 for images without thumbnails
  task upload_thumbnails: :environment do
    file = File.open("#{Rails.root}/s3_error.txt", 'w')
    imgs = Image.where(thumbnail: '').where.not(s3: nil).where.not(s3: '')
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
        i += 1
      end
    end

    file.close
  end

  # Checks if S3 files contain the same image stored in local according to Image.file
  # Outputs any error in #{Rails.root}/log.txt
  task check_integrity: :environment do
    file = File.open("#{Rails.root}/log.txt", 'w')

    imgs = Image.where.not(s3: nil).where.not(s3: '')
    i = 0
    cnt = imgs.size
    imgs.each do |img|
      puts "#{i} / #{cnt} "
      if img.file.nil? || img.file == ''
        file.puts "nofile #{img.id} #{img.s3}\n"
        puts "\nnofile #{img.id} #{img.s3}\n"
        i += 1
        next
      end

      begin
        s3 = open(img.s3).read
        hp = open("#{Rails.root}/marchandslides.bak/#{img.file}").read
        if Digest::MD5.hexdigest(s3) == Digest::MD5.hexdigest(hp)
          puts 'Good'
        else
          file.puts "diff #{img.id}\n"
          puts "\ndiff #{img.id}\n"
        end
      rescue => error
        file.puts "error #{img.id} #{img.s3} #{error}\n"
        puts "\nerror #{img.id} #{img.s3} #{error}\n"
      ensure
        i += 1
      end
    end

    file.close
  end
end
