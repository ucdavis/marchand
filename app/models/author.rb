class Author < ApplicationRecord
    has_many :image_authors
    has_many :images, through: :image_authors

    # Alias for 'name' to be able to use _filter partial in forms
    def title
        self.name
    end

end
