
## Requirements
The minimum requirement by this project template is that your Web server supports PHP 7.0 >.

### Clone repository
```
git clone https://github.com/MComScience/que-ud-municipality-h.git
```

## Docker installation
Testing on Centos7

to directory ``docker``
```
docker-compose up -d
```
## Docker FAQ
Documentation

https://docs.docker.com/

https://docs.docker.com/compose/overview/

docker compose command example.

``docker-compose ps`` check services running.

``docker-compose exec php sh`` Run a command in a running container #php

services running
```
     Name                    Command               State               Ports
-----------------------------------------------------------------------------------------
lemp_mariadb      docker-entrypoint.sh mysqld      Up      0.0.0.0:3306->3306/tcp
lemp_nginx        nginx -g daemon off;             Up      0.0.0.0:80->80/tcp
lemp_node         npm start                        Up      0.0.0.0:3000->3000/tcp
lemp_php-fpm      docker-php-entrypoint php-fpm    Up      9000/tcp
lemp_phpmyadmin   /run.sh supervisord -n           Up      0.0.0.0:8000->80/tcp, 9000/tcp
lemp_redis        docker-entrypoint.sh redis ...   Up      0.0.0.0:6379->6379/tcp
```

### Default user login
```
Login: admin
Password: 123456
```

### Database connection
```
- MYSQL_ROOT_PASSWORD=root_db
- MYSQL_DATABASE=queue-db
- MYSQL_USER=andaman
- MYSQL_PASSWORD=b8888888
```
