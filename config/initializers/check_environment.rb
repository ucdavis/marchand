env_var = %w(ELASTICSEARCH_URL)

env_var.each do |env|
  unless ENV[env]
    STDERR.puts "'#{env}' environment variable is not set."
    exit(-1)
  end
end
