# Installation (without Docker)

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

RAILS_ENV - Rails environment (by convention)
ELASTICSEARCH_URL - URI for ElasticSearch instance
MARCHAND_DEV_DB - Database name (if RAILS_ENV=development)
MARCHAND_DEV_DB_USER - Database user (if RAILS_ENV=development)
MARCHAND_DEV_DB_PASSWORD - Database password (if RAILS_ENV=development)
MARCHAND_PROD_DB - Database name (if RAILS_ENV=production)
MARCHAND_PROD_DB_USER - Database user (if RAILS_ENV=production)
MARCHAND_PROD_DB_PASSWORD - Database password (if RAILS_ENV=production)

## ElasticSearch Indexing

If you need to force a reindex, use the given `rake` tasks:

- `rake es:reindex_all`
- `rake es:reindex_lessons`
- `rake es:reindex_images`

# Installation (with Docker)

1. First, build the main web application:

docker build -t marchand .

2. Duplicate web.env.example as web.env (or the filename of your choice) and set the variables
3. Run the image

docker run --env-file ./web.env -p 3000:3000 marchand

(Note: If MySQL is running outside of Docker, you likely need to specify 'host.docker.internal' as the hostname of your database instead of 'localhost', telling Docker that you
want to use the Docker host, not the Docker container itself, when connecting to the DB.)

4. Run the Rails database migrations

(Instructions assume MySQL running on localhost with a database for Marchand already created
and specified in web.env.)

docker exec -it container_id /bin/bash
(you can get container_id by running `docker ps`. It's usually a combination of letters and numbers.)

(Once Bash loads, create the database)
RAILS_ENV=development bin/rails db:schema:load (only if DB doesn't exist)
(ignore errors from SQLite ... not sure why its creating the test database when we specified 'development')
RAILS_ENV=development bin/rails db:migrate (each time you development in case another dev made schema changes)

5. Visit localhost:3000

Note Elasticsearch is not properly set up in the above. Presumably once you have a local Elasticsearch instance, you need to run:

- `rake es:reindex_all`
- `rake es:reindex_lessons`
- `rake es:reindex_images`

In order to get Elastichsearch' index work. Until this point, freeform text search and possibly other forms of search may not work properly.
