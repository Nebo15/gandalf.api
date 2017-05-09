# Ensure access to the log file
sudo touch /var/log/php-fpm.log
sudo chmod 777 /var/log/php-fpm.log

# Set custom config
sudo ln -s $PWD/.travis/conf/php-fpm.conf ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf
echo 'always_populate_raw_post_data = -1' >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
echo 'date.timezone = "Europe/Kiev"' >> ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/travis.ini
echo "extension = mongodb.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
echo "cgi.fix_pathinfo = 1" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini