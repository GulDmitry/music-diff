Music Difference Application
============================

## Inside
* Symfony Standard Application 3.1
* [docker-compose](https://docs.docker.com/compose/) >= 1.6.1
* [webpack](http://webpack.github.io/)

## Installation
* `wget https://getcomposer.org/composer.phar`
* `sudo chmod -R 777 var/*`
* `docker-compose up -d`
* `docker exec md-php php composer.phar install`
* `npm install`
* `docker exec md-php php bin/console doctrine:schema:create`
* `docker exec md-php php bin/console doctrine:fixtures:load`
* Go to [http://localhost:8080](http://localhost:8080)
* PhpMyAdmin [http://localhost:8090](http://localhost:8090)

### Webpack
* `./node_modules/.bin/webpack ` for building once for development.
* `./node_modules/.bin/webpack -p` for building once for production (minification).
* `./node_modules/.bin/webpack --watch` for continuous incremental build in development (fast!).
* `./node_modules/.bin/webpack -d` to include source maps.
* `./node_modules/.bin/webpack-dev-server --progress --colors --port 8081 --content-base=web/` 

### Tests
* `docker exec md-php php vendor/bin/phpunit`

### REST
* Versioning via `X-Accept-Version:v1`

#### TODO
* Auth (FOSUser)
  * Local
  * Google
  * FB
  * VK
* Move from `JMS Serializer` to `symfony/serializer`.
* Bootstrap 3
* React + Redux + ReactRouter
* Admin Area
* API for [http://musicbrainz.org](http://musicbrainz.org)
* advanced search (elastic)
