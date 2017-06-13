#!/bin/sh

docker exec -i che.php_fpm_1 /bin/su docker deploy.sh
cat ./dump/dump.sql | docker exec -i che.mysql_1 /usr/bin/mysql -u root -proot phalcon
