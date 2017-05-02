class SetValueToViewAsBasedOnPictureDimensions < ActiveRecord::Migration[5.0]
    def change
        imgs = Image.where.not(:s3 => nil).where.not(:s3 => "").where.not(:file => nil).where.not(:file => "").where(:view => nil)
        i = 0
        cnt = imgs.size
        ActiveRecord::Base.transaction do
            imgs.each do |img|
                puts "#{i} / #{cnt} "
                begin
                    hp = Magick::Image.read("#{Rails.root}/marchandslides.bak/#{img.file}")
                    hp = hp.first
                    img.view = hp.rows > hp.columns ? "portrait" : "landscape"
                    img.save!
                rescue => error
                    puts "\nerror #{img.id} #{img.s3} #{error}\n"
                ensure
                    i = i + 1
                end
            end
        end
    end
end
