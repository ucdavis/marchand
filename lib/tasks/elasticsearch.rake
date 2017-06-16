require 'elasticsearch/rails/tasks/import'

namespace :es do
	desc "Reindex all models"
	task :reindex_all => :environment do
		Rake::Task["es:reindex_images"].invoke
        Rake::Task["es:reindex_lessons"].invoke
	end

	desc "Reindex Image model"
	task :reindex_images => :environment do
		puts "Reindexing images..."
		Image.__elasticsearch__.create_index! :force => true
		puts "Importing image documents..."
		Image.__elasticsearch__.import
		puts "Done."
	end

    desc "Reindex Lesson model"
	task :reindex_lessons => :environment do
		puts "Reindexing images..."
		Lesson.__elasticsearch__.create_index! :force => true
		puts "Importing lesson documents..."
		Lesson.__elasticsearch__.import
		puts "Done."
	end
end
