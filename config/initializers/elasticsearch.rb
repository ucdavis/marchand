require 'faraday_middleware/aws_signers_v4'

client = Elasticsearch::Client.new url: ENV['ELASTICSEARCH_URL'] do |f|
  f.request :aws_signers_v4,
            credentials: Aws::Credentials.new(ENV['ELASTICSEARCH_AWS_ACCESS_KEY'], ENV['ELASTICSEARCH_AWS_SECRET_KEY']),
            service_name: 'es',
            region: ENV['ELASTICSEARCH_AWS_REGION']
end
