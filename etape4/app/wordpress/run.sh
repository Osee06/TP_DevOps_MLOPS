#!/bin/bash

# Démarrer le conteneur MySQL
docker run --name my-mysql -d -e MYSQL_ROOT_PASSWORD=rootpassword -e MYSQL_DATABASE=wordpress -e MYSQL_USER=user -e MYSQL_PASSWORD=password -p 3306:3306 mysql:5.7

# Construire l'image NGINX et PHP
docker build -t my-wordpress ./wordpress

# Démarrer le conteneur NGINX
docker run --name my-nginx -d -p 8080:80 --link my-mysql:mysql my-wordpress
