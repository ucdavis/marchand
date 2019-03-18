require 'faraday_middleware'
require 'faraday_middleware/aws_sigv4'

# Configure for Amazon ES
Elasticsearch::Model.client = Elasticsearch::Client.new url: Rails.application.secrets[:elasticsearch_url] do |f|
  f.request :aws_sigv4,
            access_key_id: Rails.application.secrets[:aws_access_key],
            secret_access_key: Rails.application.secrets[:aws_secret_key],
            service: 'es',
            region: Rails.application.secrets[:elasticsearch_aws_region]
  f.response :logger
  f.adapter Faraday.default_adapter
end

# Configure for local Elasticsearch
# config = {
#   host: Rails.application.secrets[:elasticsearch_url],
#   transport_options: {
#     request: { timeout: 5 }
#   }
# }

# if File.exists?("config/elasticsearch.yml")
#   config.merge!(YAML.load_file("config/elasticsearch.yml")[Rails.env].deep_symbolize_keys)
# end

# Elasticsearch::Model.client = Elasticsearch::Client.new(config)
