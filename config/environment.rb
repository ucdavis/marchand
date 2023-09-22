# Load the Rails application.
require_relative 'application'

# Initialize the Rails application.
Rails.application.initialize!

# Set up UCD CAS support
CASClient::Frameworks::Rails::Filter.configure(
  cas_base_url: ENV['CAS_URL'] || Rails.application.secrets[:cas_url] || 'https://cas.changeme.local',
  enable_single_sign_out: true,
  ticket_store: :active_record_ticket_store
)
