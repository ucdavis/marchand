FROM ruby:2.4

RUN apt-get update -qq && apt-get install -y build-essential libpq-dev nodejs libmagickwand-dev

WORKDIR /app

COPY Gemfile* ./

RUN bundle install

COPY . .

RUN rake assets:precompile

ENV PORT 3000

CMD [ "bundle", "exec", "rails", "s", "-b", "0.0.0.0" ]
