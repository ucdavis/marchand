Rails.application.routes.draw do
  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
	root 'site#index'
    get '/admin', to: 'site#admin'
    post '/admin', to: 'site#admin'
	get '/search', to: 'site#search'
    get '/download/:key', to: 'site#download'

    get '/login', to: 'site#login'
    get '/logout', to: 'site#logout'

    post '/authors/:id/edit', to: 'authors#edit'
    post '/topics/:id/edit', to: 'topics#edit'
    post '/regions/:id/edit', to: 'regions#edit'
    post '/cal_standards/:id/edit', to: 'cal_standards#edit'
    post '/nat_standards/:id/edit', to: 'nat_standards#edit'

    resources :images
    resources :authors
    resources :topics
    resources :regions
    resources :cal_standards
    resources :nat_standards
    resources :lessons

end
