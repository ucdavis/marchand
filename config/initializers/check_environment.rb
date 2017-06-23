env_var = %w(ELASTICSEARCH_URL)

env_var.each do |env|
  unless ENV[env]
    STDERR.puts "Warning: '#{env}' environment variable is not set."
  end
end
