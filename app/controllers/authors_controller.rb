class AuthorsController < ApplicationController
    before_action :set_author, only: [:edit, :update, :destroy]
    def new
        @author = Author.new
        @prompt = "Add author"
    end

    def edit
        @prompt = "Update author"
    end

    def update
        respond_to do |format|
            if @author.update(author_params)
                format.html { redirect_to admin_path, :notice => "Successfully added '#{@author.name}'" }
                format.json { head :no_content }
            else
                format.html { redirect_to admin_path, :notice => "Failed to add '#{@author.name}'" }
                format.json { render json: @author.errors, status: :unprocessable_entity }
            end
        end
    end

    def destroy
        respond_to do |format|
            if @author.delete
                format.html { redirect_to :back, :notice => "Successfully removed '#{@author.name}'" }
                format.json { head :no_content }
            else
                format.html { redirect_to :back, :notice => "Failed to remove '#{@author.name}'" }
                format.json { render json: @author.errors, status: :unprocessable_entity }
            end
        end
    end

    def create
        respond_to do |format|
            @author = Author.new(author_params)
            if @author.save
                format.html { redirect_to :back, :notice => "Successfully added '#{@author.name}'" }
                format.json { head :no_content }
            else
                format.html { redirect_to :back, :notice => "Failed to add '#{@author.name}'" }
                format.json { render json: @author.errors, status: :unprocessable_entity }
            end
        end
    end

    private
    def set_author
        @author = Author.find(params[:id])
    end

    def author_params
        params.require(:author).permit(:id, :name)
    end

end
