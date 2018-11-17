#!/bin/bash
docker exec -i lemp_mariadb mysql -uroot -proot_db --database=queue-db < database/queue-db.sql