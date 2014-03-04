#!/usr/bin/env bash

echo "--- Good morning, master. Let's get to work. Installing now. ---"

echo "--- Updating packages list ---"
sudo apt-get update

echo "--- MySQL time ---"
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

echo "--- Installing base packages ---"
sudo apt-get install -y vim curl python-software-properties

echo "--- Updating packages list ---"
sudo apt-get update

echo "--- We want the bleeding edge of PHP, right master? ---"
sudo add-apt-repository -y ppa:ondrej/php5

echo "--- Updating packages list ---"
sudo apt-get update

echo "--- Installing PHP-specific packages ---"
sudo apt-get install -y php5 apache2 libapache2-mod-php5 php5-curl php5-gd php5-mcrypt mysql-server-5.5 php5-mysql git-core

echo "--- Installing and configuring Xdebug ---"
sudo apt-get install -y php5-xdebug

cat << EOF | sudo tee -a /etc/php5/mods-available/xdebug.ini
xdebug.scream=1
xdebug.cli_color=1
xdebug.show_local_vars=1
EOF

echo "--- Enabling mod-rewrite ---"
sudo a2enmod rewrite

echo "--- Setting document root ---"
sudo rm -rf /var/www
sudo ln -fs /vagrant/public /var/www


echo "--- What developer codes without errors turned on? Not you, master. ---"
sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php5/apache2/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php5/apache2/php.ini

sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

echo "--- Restarting Apache ---"
sudo service apache2 restart

echo "--- Composer is the future. But you knew that, did you master? Nice job. ---"
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Laravel stuff here, if you want

echo ">>> Installing phpMyAdmin"

# TO DO: Source user/pass or set variables
# within Vagrantfile?
# This is hard-coded twice now.
MYSQL_ROOT_PASS=root

# Add PPA for phpMyAdmin
sudo add-apt-repository -y ppa:nijel/phpmyadmin

# Update latest repos
sudo apt-get update

# Set needed configurations to run install via CLI
echo "phpmyadmin phpmyadmin/dbconfig-install boolean false" | debconf-set-selections
echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | debconf-set-selections
echo "phpmyadmin phpmyadmin/app-password-confirm password $MYSQL_ROOT_PASS" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/admin-pass password $MYSQL_ROOT_PASS" | debconf-set-selections
echo "phpmyadmin phpmyadmin/password-confirm password $MYSQL_ROOT_PASS" | debconf-set-selections
echo "phpmyadmin phpmyadmin/setup-password password $MYSQL_ROOT_PASS" | debconf-set-selections
echo "phpmyadmin phpmyadmin/database-type select mysql" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/app-pass password $MYSQL_ROOT_PASS" | debconf-set-selections
echo "dbconfig-common dbconfig-common/mysql/app-pass password $MYSQL_ROOT_PASS" | debconf-set-selections
echo "dbconfig-common dbconfig-common/mysql/app-pass password" | debconf-set-selections
echo "dbconfig-common dbconfig-common/password-confirm password $MYSQL_ROOT_PASS" | debconf-set-selections
echo "dbconfig-common dbconfig-common/app-password-confirm password $MYSQL_ROOT_PASS" | debconf-set-selections
echo "dbconfig-common dbconfig-common/app-password-confirm password $MYSQL_ROOT_PASS" | debconf-set-selections
echo "dbconfig-common dbconfig-common/password-confirm password $MYSQL_ROOT_PASS" | debconf-set-selections

# Install phpMyAdmin
sudo apt-get install -y phpmyadmin

echo "--- Restarting Apache ---"
sudo service apache2 restart

echo "--- All set to go! Would you like to play a game? ---"
