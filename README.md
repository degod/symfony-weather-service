# INTRODUCTION

A simple weather service that will face several API consumers who will request weather temperature information.

## PRE-REQUISITE FOR SETUP

- Docker desktop
- Web browser (to simulate in UI)
- Terminal (git bash)

## HOW TO SETUP

- Make sure your docker desktop is up and running
- Launch you terminal and navigate to your working directory

```bash
cd ./working_dir
```

- Clone repository

```bash
git clone https://github.com/degod/symfony-weather-service.git
```

- Move into the project directory

```bash
cd symfony-weather-service/
```

- Copy env.example into .env

```bash
cp .env.example .env
```

- Build app using docker

```bash
docker compose up -d --build
```

- Log in to docker container bash

```bash
docker compose exec app bash
```

- Install composer

```bash
composer install
```

- Run database creation

```bash
php bin/console doctrine:database:create --if-not-exists
```

- Run database migration

```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

- Run automated test

```bash
php bin/phpunit
```

- Exit bash container

```bash
exit
```

## ACCESSING THE APPLICATION UI AND DATABASE

- To access UI for simulation, visit
  `http://localhost:7080/`

- To access application's database, visit
  `http://localhost:7081`

## NOTE
Feel free to tweak `WEATHER_PROVIDER` parameter in the env to simulate real or fake weather API calls.
