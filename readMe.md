# Article parser

## Description
Parsing service from highload.today (https://highload.today/category/novosti/)

The service displays the list of downloaded news and a CLI command
to start parsing.

#### Parsing features:

- for each article the `title`, `description`, `picture` and `date added` are saved.
- if an article already exists in the db, it simply updates it and set a new `updated` date
- database queries are optimized for heavy load (indexes were created where appropriate).
- Parsing runs parallel processes (via rabbitMQ)
- Cron to run to run parser at every hour

#### Features of the page for viewing news from the database:

- The page is only viewed after authorization
- There are two users roles, admin and moderator. The admin can delete articles
- Articles are paginated (10 per page)

## Requirements

- php
- composer
- docker
- php amql extension
- symfony 6

## Installation

- `composer install` to install dependencies

- `docker-compose up -d` to start rabbitmq and mysql

- `symfony console doctrine:migrations:migrate` to create database

- `symfony console messenger:consume async -vv` to start consumer

## CLI commands

- `symfony console app:parse-highload.today front-end`
  - where "front-end" is the article category slug, default is 'novosti'

## Users

- admin
  - login: admin@highload.co
  - password: password
- moderator
  - login: moderator@highload.co
  - password: password
