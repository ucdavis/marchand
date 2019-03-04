# require 'faraday_middleware/aws_signers_v4'
#
# Elasticsearch::Model.client = Elasticsearch::Client.new url: Rails.application.secrets[:elasticsearch_url] do |f|
#   f.request :aws_signers_v4,
#             credentials: Aws::Credentials.new(Rails.application.secrets[:aws_access_key],
#                                               Rails.application.secrets[:aws_secret_key]),
#             service_name: 'es',
#             region: Rails.application.secrets[:elasticsearch_aws_region]
#   f.response :logger
#   f.adapter Faraday.default_adapter
# end

config = {
  host: Rails.application.secrets[:elasticsearch_url],
  transport_options: {
    request: { timeout: 5 }
  }
}

if File.exists?("config/elasticsearch.yml")
  config.merge!(YAML.load_file("config/elasticsearch.yml")[Rails.env].deep_symbolize_keys)
end

Elasticsearch::Model.client = Elasticsearch::Client.new(config)
