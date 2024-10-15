# TDP_DevOps_MLOPS
TP #2
Etape 0:
Stopper et supprimer tous les containers existants
docker stop $(docker ps -aq)
docker rm $(docker ps -aq)

Créer un répertoire docker-tp2
mkdir docker-tp2
Initialiser un repository git dans ce répertoire
git init
Chaque étape doit être dans un sous-répertoire nommé “etapeX” (X étant le numéro de l’étape) Il peut être utile au fur et à mesure des étapes d’organiser les fichiers dans des sous-répertoires (config, src, ... par exemple).

Etape 1:
->Créer le répertoire pour l'étape 1
mkdir etape1

->Création des fichiers nécessaires
--->Dans le sous-répertoire app, création du fichier index.php
echo "#php phpinfo()"; >> index.php
->Créer dans le repertoire etape1 le fichier nginx.config contenant:

server {
    listen 80;
    server_name localhost;

    root /app;
    index index.php;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        root /app;
        fastcgi_pass script:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
Dans le terminal, lancer les commandes:
1-create a new network
docker network create etape1network

2-launch PHP-FPM

docker run -d --name script \
  --network etape1network \
  --network-alias script \
  -v $(pwd)/app:/app \
  php:7.4-fpm

3-launch nginx

docker run -d --name http \
  --network etape1network \
  -p 8080:80 \
  -v $(pwd)/app:/app \
  -v $(pwd)/nginx.conf:/etc/nginx/conf.d/default.conf \
  nginx



  etape2:
->Créer le répertoire pour l'étape 1
mkdir etape2

->Création des fichiers nécessaires
--->Dans le sous-répertoire app, création du fichier test_bdd.php
->Créer dans le repertoire etape2 le fichier nginx.config contenant:

server {
    listen 8080;

    location / {
        index test_bdd.php;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass script:9000;  # Connecte à PHP-FPM
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
Créer également le fichier Dockerfile
Step 1: Create a new Docker network
docker network create etape2network

Step 2: Build a new PHP image with MySQL driver
echo "Building PHP image with MySQL driver..."
docker build -t php-fpm-mysql .

Step 3: Launch MySQL data container
echo "Launching MySQL data container..."
docker run -d --name data \
  --network $NETWORK_NAME \
  -e MYSQL_ROOT_PASSWORD=rootpassword \
  -e MYSQL_DATABASE=test_db \
  -p 3306:3306 \
  mysql

Step 4: Launch PHP-FPM container with the newly built image
echo "Launching PHP-FPM container..."
docker run -d --name script \
  --network $NETWORK_NAME \
  --network-alias script \
  -v $(pwd)/app:/app \
  php-fpm-mysql

Step 5: Launch NGINX container
echo "Launching NGINX container..."
docker run -d --name http \
  --network $NETWORK_NAME \
  -p 8080:80 \
  -v $(pwd)/app:/app \
  -v $(pwd)/nginx.conf:/etc/nginx/conf.d/default.conf \
  nginx

Step 6: Initiate MySQL database
echo "Initializing MySQL database..."
sleep 5
docker exec -it data mysql -uroot -prootpassword -e "SHOW DATABASES;"

etape3:

Step 1: Create a new Docker network
docker network create etape3network

Step 2: Build a new PHP image with mysqli and pdo_mysql
echo "Building PHP image with mysqli and pdo_mysql..."
docker build -t php-fpm-mysql .

Step 3: Launch MySQL data container
docker run -d --name data \
  --network etape3network \
  -e MYSQL_ROOT_PASSWORD=rootpassword \
  -e MYSQL_DATABASE=wordpress \
  -e MYSQL_USER=wpuser \
  -e MYSQL_PASSWORD=wppassword \
  -p 3306:3306 \
  mysql

Step 4: Launch PHP-FPM container with the newly built image
docker run -d --name script \
  --network etape3network \
  --network-alias script \
  -v $(pwd)/app:/app \
  php-fpm-mysql

Step 5: Launch NGINX container
docker run -d --name http \
  --network etape3network \
  -p 8080:80 \
  -v $(pwd)/app:/app \
  -v $(pwd)/nginx.conf:/etc/nginx/conf.d/default.conf \
  nginx
