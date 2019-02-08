Rails.application.routes.draw do
  root 'images#featured'

  get '/admin', to: 'site#admin'
  post '/admin', to: 'site#admin'

  get '/login', to: 'site#login'
  get '/logout', to: 'site#logout'

  post '/authors/:id/edit', to: 'authors#edit'
  post '/topics/:id/edit', to: 'topics#edit'
  post '/regions/:id/edit', to: 'regions#edit'
  post '/cal_standards/:id/edit', to: 'cal_standards#edit'
  post '/nat_standards/:id/edit', to: 'nat_standards#edit'

  # get '/images/:id/manipulate', to: 'images#manipulate'
  resources :images do
    get 'manipulate'
  end

  get 'images/about'

  resources :authors
  resources :topics
  resources :regions
  resources :cal_standards
  resources :nat_standards

  get '*all', to: 'application#mount', constraints: lambda { |req|
    req.path.exclude? 'rails/active_storage'
  }
  # # The above rule doesn't match the root, so add it
  match '/' => 'errors#error_404', via: :all
end
