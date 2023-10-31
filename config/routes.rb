Rails.application.routes.draw do
  # For details on the DSL available within this file, see https://guides.rubyonrails.org/routing.html

  root 'images#featured'

  get '/admin', to: 'site#admin'
  post '/admin', to: 'site#admin'

  get '/fcadmin', to: 'site#fcadmin'

  get '/login', to: 'site#login'
  get '/logout', to: 'site#logout'

  post '/authors/:id/edit', to: 'authors#edit'
  post '/topics/:id/edit', to: 'topics#edit'
  post '/featured_collections/:id/edit', to: 'featured_collections#edit'
  post '/regions/:id/edit', to: 'regions#edit'
  post '/cal_standards/:id/edit', to: 'cal_standards#edit'
  post '/nat_standards/:id/edit', to: 'nat_standards#edit'

  # get '/images/:id/manipulate', to: 'images#manipulate'
  resources :images do
    get 'manipulate'
  end

  resources :authors
  resources :topics
  resources :featured_collections
  resources :regions
  resources :cal_standards
  resources :nat_standards

  get '*all', to: 'application#mount', constraints: lambda { |req|
    req.path.exclude? 'rails/active_storage'
  }
  # # The above rule doesn't match the root, so add it
  match '/' => 'errors#error_404', via: :all
end
