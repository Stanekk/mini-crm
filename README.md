# Mini CRM API 

*Basic REST API written in symfony for mini crm or other purposes*

## Tech Stack
- PHP 8.2 +
- Symfony 7.3
- Doctrine ORM
- Twig (emails templates)
- FakerPHP (example data)
- JWT Authentication (LexikJWTAuthenticationBundle)
- PHPUnit 12 

## Code Quality
- PHP CS Fixer
````
composer cs
````

### Docker Setup
### Requirements
- Docker (20+)
- Docker Composer (1.29+)

# How to run
- In the project's root directory (where the **docker-compose.yml** and **Dockerfile** files are located)
```
docker-compose up -d --build
```
- Check installed containers
```
docker-compose ps
```
- mysql8.0.39 - database
- php8.4 - PHP-FPM
- nginx - www server
- phpmyadmin - database panel
- mailhog - SMTP test server

- enter the php container
```aiignore
docker exec -it php8.4 bash
```
- Install dependencies and run migrations

```aiignore
composer install  
php bin/console doctrine:migrations:migrate
```
- Create admin
```aiignore
php bin/console app:create-admin-user
```
