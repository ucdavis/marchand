# Installation

docker-compose build
docker-compose up

# Requires imagemagick 6 for RMagick
## RMagick
`brew install imagemagick@6 && brew link imagemagick@6 --force`
`bundle install`

# Configuration

## ElasticSearch
1. Set up `ELASTICSEARCH_URL` environment variable as your elastcisearch end point
2. Index / Reindex the models in elasticsearch when you switch ES servers or when you update the model
    - Run `rake es:reindex_all` or `rake es:reindex_lessons` or `rake es:reindex_images`

## AWS
1. Set up config/secrets.yml based on the information provided by config/secrets.example.yml
