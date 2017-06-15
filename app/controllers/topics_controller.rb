class TopicsController < ApplicationController
    before_action :set_topic, only: [:update, :edit, :destroy]

    def new
    end

    def edit
    end

    def update
    end

    def destroy
        respond_to do |format|
            if @topic.destroy
                format.html { redirect_to admin_path, notice: "Succesfully removed '#{@topic.title}'" }
                format.json { head :no_content }
            else
                format.html { redirect_to admin_path, notice: "Failed to remove '#{@topic.title}'" }
                format.json { render json: @topic.errors, status: :unprocessable_entity }
            end
        end
    end

    def create
        respond_to do |format|
            @topic = Topic.new(topic_params)
            if @topic.save
                format.html { redirect_to :back, notice: "Successfully added '#{@topic.title}'" }
                format.json { head :no_content }
            else
                format.html { redirect_to :back, notice: "Failied to add '#{@topic.title}'" }
                format.json { render json: @topic.errors, status: :unprocessable_entity }
            end
        end
    end

    private
    def set_topic
        @topic = Topic.find(params[:id])
    end

    def topic_params
        params.require(:topic).permit(:id, :title, :collection, :code, :featured)
    end
end
