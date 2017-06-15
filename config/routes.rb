Rails.application.routes.draw do
  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
	root 'site#index'
    get '/admin', to: 'site#admin'
    post '/admin', to: 'site#admin'
	get '/search', to: 'site#search'
	get '/lesson', to: 'site#lesson'
    get '/download/:key', to: 'site#download'

    get '/login', to: 'site#login'
    get '/logout', to: 'site#logout'

    resources :images
    resources :authors
    resources :topics
    resources :regions
    resources :cal_standards
    resources :nat_standards

end
