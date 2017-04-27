# README

This README would normally document whatever steps are necessary to get the
application up and running.

Things you may want to cover:

* Ruby version

* System dependencies
`brew install imagemagick@6 && brew link imagemagick@6 --force`
`bundle install`

* Configuration

* Database creation

* Database initialization

* How to run the test suite

* Services (job queues, cache servers, search engines, etc.)


* Deployment instructions
To import models to index, run:
`bundle exec rake environment elasticsearch:import:model CLASS='Image' FORCE=y`

For more information checkout the [code](https://github.com/elastic/elasticsearch-rails/blob/master/elasticsearch-rails/lib/elasticsearch/rails/tasks/import.rb#L24)
* ...
