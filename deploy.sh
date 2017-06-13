#!/bin/sh

docker exec -i che_task.php_fpm_1 /bin/su docker deploy.sh
cat ./dump/dump.sql | docker exec -i che_task.mysql_1 /usr/bin/mysql -u root -proot phalcon
