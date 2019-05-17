require 'elasticsearch/rails/tasks/import'

namespace :es do
  desc 'Reindex images in ElasticSearch'
  task reindex_images: :environment do
    index_name = Image.index_name

    Image.__elasticsearch__.create_index! force: true

    Image.all.find_in_batches(batch_size: 1000) do |group|
      group_for_bulk = group.map do |a|
        { index: { _id: a.id, data: a.as_indexed_json } }
      end

      Image.__elasticsearch__.client.bulk(
        index: index_name,
        type: 'image',
        body: group_for_bulk
      )
    end
  end

  desc "Reindex lessons in ElasticSearch"
    task :reindex_lessons => :environment do
      index_name = Lesson.index_name

      puts "############################################################################################################################################################################################################"
      puts "index name: #{index_name}"

      Lesson.__elasticsearch__.create_index! force: true

      # Image.all.find_in_batches(batch_size: 1000) do |group|
      #   group_for_bulk = group.map do |a|
      #     { index: { _id: a.id, data: a.as_indexed_json } }
      #   end
      # end

      # Image.order('id ASC').limit(1).map do |a|
      #   puts "Image: #{a.as_indexed_json}"
      #   { index: { _id: a.id, data: a.as_indexed_json } }
      # end

      # Lesson.order('id DESC').limit(1).map do |a|
      #   puts "Lesson: #{a.as_indexed_json}"
      #   { index: { _id: a.id, data: a.as_indexed_json } }
      # end

      Lesson.all.find_in_batches(batch_size: 1000) do |group|
        group_for_bulk = group.map do |a|
          # puts "#{a.as_indexed_json}"
          { index: { _id: a.id, data: a.as_indexed_json } }
        end

        puts "Type: #{Lesson.document_type}" #type = lesson
        puts "Client: #{Lesson.__elasticsearch__.client}" #type = lesson

        Lesson.__elasticsearch__.client.bulk(
          index: index_name,
          type: 'lesson',
          body: group_for_bulk
        )
    end
  end
end
