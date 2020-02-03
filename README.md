# Tasks
Task manager for tracking users personal tasks. Project created with Symfony + MySQL.
## Installation
You should have installed Docker Engine and Docker Compose to get started with defined in this repository environment.
### Clone
Clone this repository to your local machine using `https://github.com/volhabelausava/Tasks.git`
### Setup
> setup environment for the application with incuded docker-compose.yml
```shell
$ docker-compose build
$ docker-compose up -d
```
> now install all needed composer dependencies
```shell
$ composer install
```
> next step is to create the database tables with migrations
```shell
$ docker-compose run --rm tasks-php-cli php bin/console doctrine:migrations:migrate
```
> and finally enjoy the application in your browser at the given URL

URL: <a href="http://localhost:8080" target="_blank">http://localhost:8080</a> 

(first go to registration page to create your account).

> To get list of your tasks via API go to the following URL

URL: <a href="http://localhost:8080/api/tasks" target="_blank">http://localhost:8080/api/tasks</a> 


## Contacts
If you have any questions reach out to me by email: <a href="mailto:volha.belausava@gmail.com" target="_blank">volha.belausava@gmail.com</a> 
