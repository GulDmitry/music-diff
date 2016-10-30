Music Difference Application
============================

## Inside
* Symfony Standard Application 3.1
* [docker-compose](https://docs.docker.com/compose/) >= 1.6.1

## Installation
* `wget https://getcomposer.org/composer.phar`
* `sudo chmod -R 777 var/*`
* `docker-compose up -d`
* `docker exec md-php php composer.phar install`
* `docker exec md-php php bin/console doctrine:schema:create`
* `docker exec md-php php bin/console doctrine:fixtures:load`
* Go to [http://localhost:8080](http://localhost:8080)
* PhpMyAdmin [http://localhost:8090](http://localhost:8090)

### Tests
* `docker exec md-php php vendor/bin/phpunit`

#### TODO
* Auth (FOSUser)
  * Local
  * Google
  * FB
  * VK
* Move from `JMS Serializer` to `symfony/serializer`.
* Webpack
* Bootstrap 3
* React + Redux + ReactRouter
* Admin Area
* API for [http://musicbrainz.org](http://musicbrainz.org)
* advanced search
