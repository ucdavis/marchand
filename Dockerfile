FROM ubuntu:latest

# Update
apt-get update

# Install LAMP stack
apt-get install apache2
apt-get install php7.0 
# No Database -- handled by AWS S3

/etc/init.d/apache2 restart


