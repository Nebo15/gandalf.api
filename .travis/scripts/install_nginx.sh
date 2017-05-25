# Fix file permissions
sudo chown -R :www-data ./
sudo chown -R www-data:www-data /etc/nginx/sites-available
sudo chown -R www-data:www-data /etc/nginx/sites-enabled

# Create logs directory
sudo mkdir -p /var/log/www
sudo chown www-data:www-data /var/log/www

# Replace nginx config with custom one
sudo rm -f /etc/nginx/nginx.conf
sudo ln -s $PWD/.travis/conf/nginx.conf /etc/nginx/nginx.conf

# Link project config
sudo -Hu www-data ln -s $PWD/config/nginx/travis.conf /etc/nginx/sites-enabled/testserver.conf
