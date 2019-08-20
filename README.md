# phalcon-microservices
Little demo project that build with two Phalcon modules and docker compose.

## Installation Guide
1. Run `composer install` in folders of every project. Best practise is not pushing `composer` in the docker image, so you should run it manually.
2. Then run `docker-compose up --build`
3. Project will be available at `127.0.0.1:80`
