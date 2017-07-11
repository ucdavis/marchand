# Installation

A standard Rails installation applies, or you can use the Dockerfile.

# Requires imagemagick 6 for RMagick
## RMagick (on macOS)
`brew install imagemagick@6 && brew link imagemagick@6 --force`
`bundle install`

# Configuration

## Database data
Though `rake db:schema:load` followed by `rake db:migrate` should work, it is recommended
that you use a copy of the production database.

## Environment variables
This project expects the following environment variables:

RAILS_ENV                 - Rails environment (by convention)
ELASTICSEARCH_URL         - URI for ElasticSearch instance
MARCHAND_DEV_DB           - Database name (if RAILS_ENV=development)
MARCHAND_DEV_DB_USER      - Database user (if RAILS_ENV=development)
MARCHAND_DEV_DB_PASSWORD  - Database password (if RAILS_ENV=development)
MARCHAND_PROD_DB          - Database name (if RAILS_ENV=production)
MARCHAND_PROD_DB_USER     - Database user (if RAILS_ENV=production)
MARCHAND_PROD_DB_PASSWORD - Database password (if RAILS_ENV=production)

## ElasticSearch Indexing
If you need to force a reindex, use the given `rake` tasks:

* `rake es:reindex_all`
* `rake es:reindex_lessons`
* `rake es:reindex_images`
