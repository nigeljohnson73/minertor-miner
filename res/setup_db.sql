CREATE DATABASE cc;
CREATE USER 'mtm_user'@'localhost' IDENTIFIED BY 'mtm_passwd';
GRANT ALL PRIVILEGES ON mtm.* TO 'mtm_user'@'localhost';
#ALTER USER 'mtm_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'mtm_passwd'; # Does not work on the pi - needed it for Mac development setup
FLUSH PRIVILEGES;
