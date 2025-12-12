#!/bin/bash

# Script to fix storage permissions in Docker container
# This script can be run inside the container or as a docker exec command

echo "=== Fixing Storage Permissions ==="

# Create all necessary directories
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/framework/cache
mkdir -p /var/www/storage/framework/cache/data
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/testing
mkdir -p /var/www/storage/logs
mkdir -p /var/www/storage/app/public
mkdir -p /var/www/bootstrap/cache

# Set ownership
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache

# Set directory permissions (775)
find /var/www/storage -type d -exec chmod 775 {} \;
find /var/www/bootstrap/cache -type d -exec chmod 775 {} \;

# Set file permissions (664)
find /var/www/storage -type f -exec chmod 664 {} \;
find /var/www/bootstrap/cache -type f -exec chmod 664 {} \;

# Ensure views directory is writable
chmod -R 775 /var/www/storage/framework/views
chown -R www-data:www-data /var/www/storage/framework/views

echo "Permissions fixed!"
echo "Verifying storage/framework/views permissions:"
ls -la /var/www/storage/framework/views



