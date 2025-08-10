

# CHMOD temp folders
sudo chmod -R 775 /home/danielp/myapps/unasam/app/tmp

# Install Apache , PHP 
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install apache2
sudo apt install php5.6 libapache2-mod-php5.6 php5.6-mysql
sudo a2enmod php5.6
sudo a2enmod rewrite

# Restart Apache
sudo systemctl restart apache2

# MYSQL USER settings   test
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '1234';
FLUSH PRIVILEGES;

# MYSQL : change config to old auth
sudo vim /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
default_authentication_plugin = mysql_native_password

# Restart Mysql
sudo systemctl restart mysql

#  Create database
CREATE DATABASE unasam;
USE your_database_name;

#  IMPORT SQL
mysql -u root -p unasam < app/migrations/solucion_unasam2.sql

# DEMO USER WEB
demo / 1234