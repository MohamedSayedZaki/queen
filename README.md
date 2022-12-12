# queen
#### is a logging system that user can log files from small files to large ones and user can login using [username/password] admin/admin
## Folder structue
```
- docker
- site
- docker-compose.yml
```
## use below block into docker-compose.yml file
```yaml
version: "2"
services:
  #PHP service
  app:
    build: ./docker/php/
    container_name: app-php
    working_dir: /var/www/site
    volumes:
      - ./site:/var/www/site
    networks:
      - app-network

  #Nginx service
  nginx:
    image: nginx:alpine
    container_name: app-nginx
    working_dir: /var/www/site
    ports:
      - 8000:80
    volumes:
      - ./site:/var/www/site
      - ./docker/nginx/conf.d/:/etc/nginx/conf.d/
    networks:
      - app-network

networks:
  app-network:
    driver: bridge
```

### docker folder structue
```
- nginx
  - conf.d
    - app.conf 
- php
  - Dockerfile
```
### nginx docker file contents
```
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/app.error.log;
    access_log /var/log/nginx/app.access.log;

    root /var/www/site/public;

    location ~ \.php$ {
        fastcgi_buffer_size 32k;
       fastcgi_buffers 4 32k;
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
	fastcgi_connect_timeout 300s;
        fastcgi_send_timeout 60;
        fastcgi_read_timeout 60;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
```
### php docker filer contents

``` text
FROM php:fpm-alpine

RUN docker-php-ext-install pdo_mysql

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps

COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer

CMD ["php-fpm"]

EXPOSE 9000

```

- build docker containers
  ```bash
  docker-compose up -d --build 
  ```
- you can enter app conatiner using 
  ```bash
  docker exec -it app-php sh
  ```
- run tests
  ```bash
  php bin/phpunit tests
  ```

## Now you can access the web app 
- [queen app](http://localhost:8000/)