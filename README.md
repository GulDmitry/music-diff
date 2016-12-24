Music Difference Application
============================

[![Build Status](https://travis-ci.org/GulDmitry/music-diff.svg?branch=master)](https://travis-ci.org/GulDmitry/music-diff)
[![codecov](https://codecov.io/gh/GulDmitry/music-diff/branch/master/graph/badge.svg)](https://codecov.io/gh/GulDmitry/music-diff)

## Inside
* [Symfony Standard Application](https://github.com/symfony/symfony-standard/) 3.1
* [docker-compose](https://docs.docker.com/compose/) >= 1.6.1
* [webpack](http://webpack.github.io/)
* [bootstrap](http://getbootstrap.com/) 3
* Redux + React
* Behat (PhantomJS)
* [Go! AOP](https://github.com/goaop/framework)

## Installation
* `wget https://getcomposer.org/composer.phar`
* `sudo chmod -R 777 var/cache var/logs var/sessions`
* `docker-compose up -d`
* `docker exec md-php php composer.phar install`
* `npm install` or `npm install --production`
* `docker exec md-php php bin/console doctrine:schema:create`
* `docker exec md-php php bin/console doctrine:fixtures:load`
* Go to [http://localhost:8080](http://localhost:8080)
* PhpMyAdmin [http://localhost:8090](http://localhost:8090)

> **Note**: Instead `docker exec md-php php ...` the entry point `bin\php` can be used.

### Webpack
* `./node_modules/.bin/webpack ` for building once for development.
* `ENV='prod' ./node_modules/.bin/webpack -p` for building once for production (minification).
* `./node_modules/.bin/webpack --watch` for continuous incremental build in development (fast!).
* `ENV='prod' ./node_modules/.bin/webpack -d` to include source maps.
* `./node_modules/.bin/webpack-dev-server --progress --colors --port 8081 --content-base=web/` for dev environment.

### Tests
* `docker exec md-php php bin/console doctrine:database:create -e=test --if-not-exists`
* `docker exec md-php php bin/console doctrine:schema:create -e=test`
* Unit
  * `docker exec md-php php vendor/bin/phpunit --testsuite Unit`
* Functional
  * `docker exec md-php php vendor/bin/phpunit --testsuite Functional`
* All PhpUnit
  * `docker exec md-php php vendor/bin/phpunit`
* Behat (API context uses the `prod` environment)
  * `bin/php bin/console cache:clear --env=prod`
  * `bin/php vendor/bin/behat`

### REST
* Versioning via header `X-Accept-Version:v1`

### Debugging
`xdebug.remote_autostart=on`

`xdebug.remote_enable=on`

`xdebug.remote_handler=dbgp`

`xdebug.remote_mode=req`

`xdebug.remote_port=9000`

`xdebug.remote_connect_back=on` if set the xdebug.remote_host is ignored.
#### Web
* PHPStorm -> Settings -> Languages & Frameworks -> PHP -> Servers
  * Host: `localhost`
  * Port: `80`
  * Debugger: `Xdebug`
  * [x] Use path mappings

File/Directory | Absolute path on the server
-------------- | ---------------------------
/var/www/music-diff | /var/www/html

#### PHPUnit via PHPStorm
* PHPStorm -> Languages & Frameworks -> PHP 
  * Set PHP interpreter as `bin/php` file.
  
> **Note**: Make sure that image name generated by `docker-compose up` is `musicdiff_php`.

* PHPStorm -> Languages & Frameworks -> PHP -> PHPUnit
  * User custom\Composer autoloader: `vendor/autoload.php` 
  * Default configuration file: `phpunit.xml.dist`

#### TODO
* Collections and subscriptions for users.
* MusicDiff frontend: 
  * exclude the same artists,
  * Delete artist.
  * Show album records.
* Move collection from SplObjectStorage to array. Add method hasAlbum()...
* Schedule band request, send results back via websocket.
* Tutorial.
* Admin Area.
* Move from `JMS Serializer` to `symfony/serializer`.
* Search music in file system (only Chrome?).
* Auth as a microservice (GO time!).
* DB indices.
