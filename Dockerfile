FROM ruby:3.1

RUN apt-get update -qq && apt-get install -y build-essential libpq-dev nodejs libmagickwand-dev

RUN mkdir /myapp
WORKDIR /myapp

COPY Gemfile /myapp/Gemfile
COPY Gemfile.lock /myapp/Gemfile.lock

RUN gem install bundler
RUN bundle install

COPY . /myapp

# Workaround for https://github.com/rails/rails/pull/35607
RUN mkdir /myapp/tmp
RUN rake assets:precompile

ENV PORT 3000

# create directory needed for puma pid file
RUN mkdir -p tmp/pids

CMD [ "bundle", "exec", "rails", "s", "-b", "0.0.0.0" ]
