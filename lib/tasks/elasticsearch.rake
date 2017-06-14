require 'elasticsearch/rails/tasks/import'

namespace :es do
	desc "Reindex all models"
	task :reindex_all => :environment do
		Rake::Task["es:reindex_images"].invoke
	end

	desc "Reindex Image model"
	task :reindex_images => :environment do
		puts "Reindexing images..."
		Image.__elasticsearch__.create_index! :force => true
		puts "Importing image documents..."
		Image.__elasticsearch__.import
		puts "Done."
	end
end
