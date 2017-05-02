require 'rake'
require 'open-uri'
require 'digest'
require "#{Rails.root}/app/helpers/aws_helper"
require "#{Rails.root}/app/helpers/site_helper"
include SiteHelper

namespace :s3 do
    # Uploads thumbnails to s3 for images without thumbnails
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

    # Update metadata of each object
    # http://www.mattboldt.com/updating-s3-object-metadata-in-ruby/
    task :update_metadata => :environment do
        # All possible options an object could have
        @all_options = [:multipart_copy, :content_length, :copy_source_client, :copy_source_region, :acl, :cache_control, :content_disposition, :content_encoding, :content_language, :content_type, :copy_source_if_match, :copy_source_if_modified_since, :copy_source_if_none_match, :copy_source_if_unmodified_since, :expires, :grant_full_control, :grant_read, :grant_read_acp, :grant_write_acp, :metadata, :metadata_directive, :tagging_directive, :server_side_encryption, :storage_class, :website_redirect_location, :sse_customer_algorithm, :sse_customer_key, :sse_customer_key_md5, :ssekms_key_id, :copy_source_sse_customer_algorithm, :copy_source_sse_customer_key, :copy_source_sse_customer_key_md5, :request_payer, :tagging, :use_accelerate_endpoint]

        begin
            AwsHelper::establish_connection
            s3 = Aws::S3::Resource.new
            bucket = s3.bucket(Rails.application.secrets.s3_bucket)
        rescue => e
            puts "Could not connect to S3 bucket"
            exit
        end

        images = Image.where.not(:s3 => nil).where.not(:s3 => "")
        max_count = images.size
        curr_count = 0

        images.each do |img|
            curr_count += 1

            begin
                # GET the current options of the object
                object_summary = bucket.object img.s3.split("/").last
                object = object_summary.get
                existing_options = object.to_h.slice(*@all_options)

                # Get dimensions of image the image
                r_img = Magick::Image.read(img.s3).first

                # Build new options
                options = {}.merge existing_options
                options.merge! ({
                    :acl => 'public-read',
                    :metadata_directive => 'REPLACE',
                    :metadata => get_metadata(r_img)
                })

                # multipart_copy is necessary if the object is 5GB+
                if object.size >= 5_000_000_000
                    options.merge!({multipart_copy: true})
                else
                    # Only used if multipart_copy is true
                    options.delete(:content_length)
                end

                # Save to the same location
                location = "#{bucket.name}/#{object_summary.key}"

                object_summary.copy_to(location, options)
                puts "Copied #{curr_count} of #{max_count}"
            rescue => e
                puts "Exception Raised: #{e}"
            end
        end
    end

    # Checks if S3 files contain the same image stored in local according to Image.file
    # Outputs any error in #{Rails.root}/log.txt
    task :check_integrity => :environment do
        file = File.open("#{Rails.root}/log.txt", "w")

        imgs = Image.where.not(:s3 => nil).where.not(:s3 => "")
        i = 0
        cnt = imgs.size
        imgs.each do |img|
            puts "#{i} / #{cnt} "
            if img.file == nil || img.file == ""
                file.puts "nofile #{img.id} #{img.s3}\n"
                puts "\nnofile #{img.id} #{img.s3}\n"
                i = i + 1
                next
            end

            begin
                s3 = open(img.s3).read
                hp = open("#{Rails.root}/marchandslides.bak/#{img.file}").read
                if Digest::MD5.hexdigest(s3) == Digest::MD5.hexdigest(hp)
                    puts "Good"
                else
                    file.puts "diff #{img.id}\n"
                    puts "\ndiff #{img.id}\n"
                end
            rescue => error
                file.puts "error #{img.id} #{img.s3} #{error}\n"
                puts "\nerror #{img.id} #{img.s3} #{error}\n"
            ensure
                i = i + 1
            end
        end
        file.close
    end
end
