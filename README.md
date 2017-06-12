# docker-symfony2or3-php7-mysql-redis

Docker skeleton with Nginx, MySQL, Redis and PHP7 prepared for Symfony2/3 usage.

#### Usage

1. Install **[docker](https://docs.docker.com/engine/installation/linux/ubuntu/)**, **[docker-compose](https://docs.docker.com/compose/install/)**
2. Copy `docker-compose.yml.dist` into `docker-compose.yml`
3. Edit your `docker-compose.yml` (it is not under VCS) as you prefer
4. Move Symfony2/3 application content into `./application` folder
5. Add to `/etc/hosts`:
```
127.0.0.1 symfony.dev
```
where `symfony.dev` can be changed in `docker/nginx/configs/symfony.conf`

6. Run `redeploy.sh`
7. Now you can visit running application through **[symfony.dev:8080/app_dev.php](http://symfony.dev:8080/app_dev.php)** or **[symfony.dev:8080](http://symfony.dev:8080)**

In application you can connect to database and redis using hosts `database_host` and `redis_host`.

#### Structure

```
├── application
│   └── web
│       └── app.php
├── database
├── docker
│   ├── nginx
│   │   ├── configs
│   │   │   └── symfony.conf
│   │   └── Dockerfile
│   └── php_fpm
│       ├── configs
│       │   └── ...
│       └── Dockerfile
├── logs
│   └── nginx
├── docker-compose.yml
└── redeploy.sh
```

- `application` is the directory for project files. Our Nginx config is pointing to `./application/web`, which can be changed in `docker/nginx/configs/symfony.conf`
- `database` is where MySQL will store the database files.
- `logs/nginx` is where nginx will store the logs.

P.S. Helpful util **[phpdocker.io/generator](https://phpdocker.io/generator)**
