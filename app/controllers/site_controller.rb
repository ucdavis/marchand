class SiteController < ApplicationController
	IMAGE_LIMIT = 24

	def index
		# Retrieve featured topics and images available to the public
		if params[:bestof].present?
			@search = true
			@cards = TopicAssignment.joins(:image).joins(:topic).where(:images => {:featured => 1, :public => 1}, :topics => {:featured => 1, }, :topic_id => params[:bestof])
		else
			@search = false
			@cards = TopicAssignment.joins(:image).joins(:topic).where(:images => {:featured => 1, :public => 1}, :topics => {:featured => 1}).order("RANDOM()").limit(IMAGE_LIMIT)
		end

	end

	def edit
	end
end
