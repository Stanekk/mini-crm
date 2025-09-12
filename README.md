
# Mini CRM API

*Basic REST API written in symfony for mini crm or other purposes*


## Tech Stack

- PHP 8.2+
- Symfony 7.3
- Doctrine ORM
- Twig (emails templates)
- FakerPHP (example data)
- JWT Authentication (LexikJWTAuthenticationBundle)
- PHPUnit 12
- Code Quality PHP CS Fixer


## Installation

- In the project's root directory (where the **docker-compose.yml** and **Dockerfile** files are located)

```bash
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

Go to the PHP container
```aiignore
docker exec -it php8.4 bash
```

Install dependencies and run migrations
```aiignore
composer install  
php bin/console doctrine:migrations:migrate
```

Create admin user (optional)

```aiignore
php bin/console app:create-admin-user
```
## API Reference

#### The API uses JSON Web Tokens (JWT) for authentication and authorization. All endpoints under /api require a valid JWT token, except for /api/login and /api/register.

After logging in, the client receives a JWT token. The token must be included in the Authorization header with every request to protected endpoints.

```
Authorization: Bearer <TOKEN>
```


#### Register user

```http
  POST /api/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**|
| `password` | `string` | **Required**|
| `passwordConfirm` | `string` | **Required**|

Example request:
```
{
    "email": "testemail@example.pl",
    "password": "12345678",
    "passwordConfirm": "12345678"
}
```

Example sucess response (HTTP 201)
```
{
    "id": 84,
    "email": "testemail@example.pl",
    "roles": [
        "ROLE_USER"
    ],
    "source": "app"
}
```

#### Login user

```http
  POST /api/login
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `username`      | `string` | **Required**. User's email address |
| `password`      | `string` | **Required**. |


Example sucess response (HTTP 200)

```
{
    "token": <TOKEN>
}
```

#### USERS

```http
  GET /api/users
```

Returns a list of users in the application (HTTP 200)

Example response:

```
{
    "data": [
        {
            "id": 84,
            "email": "testemail@example.pl",
            "roles": [
                "ROLE_USER"
            ],
            "source": "app"
        },
        {...}
    ],
    "pagination": {
        "page": 1,
        "limit": 20,
        "total": 21,
        "pages": 2
    }
}
```
```http
  GET /api/me
```

Returns the currently logged-in user (HTTP 200)

Example response:
```aiignore
{
    "id": 9,
    "email": "admin@admin.pl",
    "roles": [
        "ROLE_ADMIN",
        "ROLE_USER"
    ],
    "source": "app"
}
```


```http
  DELETE /users/delete/{id}
```

