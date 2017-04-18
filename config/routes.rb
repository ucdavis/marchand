Rails.application.routes.draw do
  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
	root 'site#index'
  get '/edit', to: 'site#edit'
end
