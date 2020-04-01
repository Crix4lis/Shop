> author: Michał Powała <br>
> source repository: [Shop](https://github.com/Crix4lis/Shop)

# README
1. For business requirements go to [Task](https://github.com/Crix4lis/Shop/blob/master/doc/Task.md)
1. For API Documentation go to [API Doc](https://github.com/Crix4lis/Shop/blob/master/doc/Documentation.md)
1. For Architecture/Design notes go to [Architecture](https://github.com/Crix4lis/Shop/blob/master/doc/Architecture.md)

## First time
1. Install [docker](https://docs.docker.com/install/) and [docker-composer](https://docs.docker.com/compose/install/)
1. Clone repository
1. Go to repository root directory
1. Build and run containers: `docker-composer up -d`
1. Get into cli docker container: `docker-composer exec cli bash`
1. Install dependencies: `composer install`
1. Generate schema: `php bin/console doctrine:schema:create`
1. Run unit tests: `bin/console/phpunit`
1. Start symfony server: `symfony server:start -d`
1. Play with API with some kind of a Client, address is: `http://127.0.0.1:8000`

## Not first time
1. Go to repository root directory
1. Run containers: `docker-composer up -d`
1. Get into cli docker container: `docker-composer exec cli bash`
1. Start symfony server: `symfony server:start -d`
1. Play with API with some kind of a Client, address is: `http://127.0.0.1:8000`
