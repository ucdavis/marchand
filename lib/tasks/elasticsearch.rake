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
    task reindex_lessons: :environment do
      index_name = Lesson.index_name

      Lesson.__elasticsearch__.create_index! force: true

      Lesson.all.find_in_batches(batch_size: 1000) do |group|
        group_for_bulk = group.map do |a|
          { index: { _id: a.id, data: a.as_indexed_json } }
        end

        Lesson.__elasticsearch__.client.bulk(
          index: index_name,
          type: 'lesson',
          body: group_for_bulk
        )
    end
  end
end
