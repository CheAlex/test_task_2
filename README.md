# Test task

### Install

0. Add to `/etc/hosts`:
```
127.0.0.1 content.dev
127.0.0.1 blog1.dev
127.0.0.1 blog2.dev
```
1. Install **[docker](https://docs.docker.com/engine/installation/linux/ubuntu/)**, **[docker-compose](https://docs.docker.com/compose/install/)**
2. Run:
```sh
docker-compose up
```
6. Run:
```sh
sh deploy.sh
```
7. Now you can visit: 

[blog1.dev:8080/page_1](http://blog1.dev:8080/page_1)

[blog1.dev:8080/page_2](http://blog1.dev:8080/page_2)

[blog2.dev:8080/page_1](http://blog2.dev:8080/page_1)

[blog2.dev:8080/page_2](http://blog2.dev:8080/page_2)

[blog1.dev:8080/cache/clear](http://blog1.dev:8080/cache/clear)

[blog2.dev:8080/cache/clear](http://blog2.dev:8080/cache/clear)
