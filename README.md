# queen
#### is a logging system that user can log files from small files to large ones and user can login using [username/password] admin/admin
## Folder structue
### - docker
### - site
### - docker-compose.yml

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
## once docker containers built
- dont forget to update this line within docker/nginx/conf.d/app.conf 
  #### root /var/www/site/   ==>   root /var/www/site/public
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