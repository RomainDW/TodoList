#  TodoList
Project 8 - PHP / Symfony Application Developer Path - OpenClassrooms

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

##  Installation
Clone :
```shell
git clone https://github.com/RomainDW/TodoList.git
```
Composer install :
```shell
composer install
``` 
This will create a parameters.yml file and you will be asked to enter some parameters :
```shell
Creating the "app/config/parameters.yml" file
Some parameters are missing. Please provide them.
database_host (127.0.0.1): 
database_port (null): 
database_name (symfony): todolist
database_user (root): 
database_password (null): 
mailer_transport (smtp): 
mailer_host (127.0.0.1): 
mailer_user (null): 
mailer_password (null): 
secret (ThisTokenIsNotSoSecretChangeIt): 
database_path ('%kernel.project_dir%/data.sqlite'): 
```
Provide the database information.

Then, let's create the database and load some fixtures :
```shell
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load
```

Fixtures provide you 2 users (1 admin & 1 user) and some user-related tasks.

Now you can run the server :
```shell
php bin/console server:start
```
Or
```shell
php bin/console server:run
```

You can log in with one of the user provided by the fixtures :  

| Email           | Password    | Role       |
| :-------------- | :---------- | :--------- |
| user@email.com  | password    | ROLE_USER  |
| admin@email.com | password    | ROLE_ADMIN |

##  Tests
To run the tests, you must first create the test database :
```shell
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:create --env=test
```

Now you can run PHPUnit tests with this command :
```shell
php vendor/bin/simple-phpunit
```
OR you can run Behat tests with this command :
```shell
php vendor/bin/behat
```

###  Test coverage
You can access the **PHPUnit code coverage** at [/coverage-phpunit/index.html](/web/coverage-phpunit/index.html) or the **Behat code coverage** at [/coverage-behat/index.html](/web/coverage-behat/index.html)