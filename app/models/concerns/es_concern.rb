module EsConcern
    extend ActiveSupport::Concern

    included do
        after_save :update_document
        after_destroy :update_document
        after_create :update_document
        after_update :update_document
    end


    # Touch image to update ES document
    def update_document
        Image.where(id: self.image.id).first.touch unless self.image.nil?
    end
end
