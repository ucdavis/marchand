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

  resources :authors
  resources :topics
  resources :regions
  resources :cal_standards
  resources :nat_standards

  # Leave this route at the end to capture 404s
  match '*path' => 'errors#error_404', via: :all
end
