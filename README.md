
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

Example success response (HTTP 201)
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


Example success response (HTTP 200)

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

Removes a user (sets tasks as unassigned if the user had any)

Returns only response code 204
```http
  DELETE /users/delete/{id}
```

#### CLIENTS

```http
  GET /api/clients
```

Returns a list of clients in the application (HTTP 200)

Example response:

```
{
    "data": [
             {
            "id": 49,
            "firstName": "Nataniel",
            "lastName": "Nowicki",
            "dataSource": "faker",
            "company": {
                "id": 64,
                "name": "Kwiatkowski",
                "email": "jasinski.damian@example.com",
                "source": "faker"
            },
            "email": "daria.kowalczyk@example.com",
            "phone": "0048 271 076 395"
        },
        {
            "id": 51,
            "firstName": "Bianka",
            "lastName": "Głowacka",
            "dataSource": "faker",
            "company": {
                "id": 67,
                "name": "Michalski sp. z o.o.",
                "email": "ada.szewczyk@example.com",
                "source": "faker"
            },
            "email": "karolina11@example.net",
            "phone": "0048 567 174 109"
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
  POST /api/clients
```

Creates a new client


| Parameter   | Type      | Description           |
|:------------|:----------|:----------------------|
| `firstName` | `string`  | **Required**          |
| `lastName`  | `string`  | **Required**          |
| `email`     | `string`  | **Required**          |
| `phone`     | `string`  |                       |
| `company`   | `integer` | existing company ID |

Example request:

```http request
{
    "firstName": "John",
    "lastName": "Doe",
    "email": "example@example.com",
    "phone": "+48 555444111",
    "company": 68
}
```

Example response (HTTP 201)

Company object is a special short type in this response

```http request
{
    "id": 55,
    "firstName": "John",
    "lastName": "Doe",
    "dataSource": "app",
    "company": {
        "id": 68,
        "name": "Przybylska sp. p.",
        "email": "olgierd93@example.net",
        "source": "faker"
    },
    "email": "example@example.com",
    "phone": "+48 555444111"
}
```

Client details
```http
  GET /api/clients
```

Example response (HTTP 200)

Company object is a special short type in this response

```http request
{
    "id": 55,
    "firstName": "John",
    "lastName": "Doe",
    "dataSource": "app",
    "company": {
        "id": 68,
        "name": "Przybylska sp. p.",
        "email": "olgierd93@example.net",
        "source": "faker"
    },
    "email": "example@example.com",
    "phone": "+48 555444111"
}
```

Client update
```http request
  PATCH /api/clients/{id}
```

Updates those properties that will be sent in the request


| Parameter   | Type      | Description           |
|:------------|:----------|:----------------------|
| `firstName` | `string`  | It cannot be empty if it is to be updated       |
| `lastName`  | `string`  | It cannot be empty if it is to be updated        |
| `phone`     | `string`  |                       |
| `company`   | `integer` |  |

Example request

```http request
{
    "firstName": "John",
    "lastName": "Doe",
    "email": "emaail@email.com",
    "company": 68
}
```

Example response (HTTP 200)
```http request
{
    "id": 1,
    "firstName": "John",
    "lastName": "Doe",
    "dataSource": "app",
    "company": {
        "id": 68,
        "name": "Przybylska sp. p.",
        "email": "olgierd93@example.net",
        "source": "faker"
    },
    "email": "emaail@email.com",
    "phone": "234234324"
}
```
If the company ID is provided as null, the client will not belong to the company

For example:

```http request
{
    "firstName": "John",
    "lastName": "Doe",
    "email": "emaail@email.com",
    "company": null
}
```

Will return the result:

```http request
{
    "id": 1,
    "firstName": "John",
    "lastName": "Doe",
    "dataSource": "app",
    "company": null,
    "email": "emaail@email.com",
    "phone": "234234324"
}
```

Deleting clients
```http request
  DELETE /api/clients/{id}
```
This request does not require administrator privileges.

Nothing returns (HTTP 204)


### Companies 

```http request
  GET /api/companies
```
Returns a list of companies in the app

Example response (HTTP 200)
```
{
    "data": [
        {
            "id": 66,
            "name": "Stowarzyszenie Krupa",
            "vatNumber": "PL9438355726",
            "nipNumber": "9438355726",
            "email": "franciszek92@example.com",
            "createdAt": {
                "date": "2025-09-11 17:40:11.000000",
                "timezone_type": 3,
                "timezone": "UTC"
            },
            "updatedAt": {
                "date": "2025-09-11 17:40:11.000000",
                "timezone_type": 3,
                "timezone": "UTC"
            },
            "notes": null,
            "isActive": true,
            "dataSource": "faker"
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
Creating a new company

```http request
  POST /api/companies
```


| Parameter   | Type      | Description                                     |
|:------------|:----------|:------------------------------------------------|
| `name`      | `string`  | **Required**                                    |
| `email`     | `string`  | **Required**                                    |
| `vatNumber` | `string`  |                                                 |
| `nipNumber` | `string`  |                                                 |
| `notes`     | `string`  |                                                 |
| `isActive`  | `boolean` | Always active by default                        |

Example request

```http request
{
    "name": "Firma 1",
    "email": "firma@firma.pl",
    "vatNumber": "PL9438355726",
    "nipNumber": "9438355726",
    "notes": "Notes",
    "isActive": false
}
```

Example response (HTTP 201)

```http request
{
    "id": 71,
    "name": "Firma 1",
    "vatNumber": "PL9438355726",
    "nipNumber": "9438355726",
    "email": "firma@firma.pl",
    "createdAt": {
        "date": "2025-09-15 17:43:24.065030",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "updatedAt": {
        "date": "2025-09-15 17:43:24.065030",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "notes": "Notes",
    "isActive": false,
    "dataSource": "app"
}
```

Update company

```http request
PATCH /api/companies/{id}
```

| Parameter   | Type      | Description                               |
|:------------|:----------|:------------------------------------------|
| `name`      | `string`  | It cannot be empty if it is to be updated |
| `vatNumber` | `string`  |                                           |
| `nipNumber` | `string`  |                                           |
| `notes`     | `string`  |                                           |
| `isActive`  | `boolean` |                                           |

Example request:
```http request
{
    "name": "New company name",
    "vatNumber": "PL8668355726",
    "isActive": true
}
```

Example response (HTTP 200)
```http request
{
    "id": 71,
    "name": "New company name",
    "vatNumber": "PL8668355726",
    "nipNumber": "9438355726",
    "email": "firma@firma.pl",
    "createdAt": {
        "date": "2025-09-15 17:43:24.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "updatedAt": {
        "date": "2025-09-15 17:50:44.815574",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "notes": "Notes",
    "isActive": true,
    "dataSource": "app"
}
```

Company detail

```http request
GET /api/companies/{id}
```
Example response (HTTP 200)
```http request
{
    "id": 71,
    "name": "New company name",
    "vatNumber": "PL8668355726",
    "nipNumber": "9438355726",
    "email": "firma@firma.pl",
    "createdAt": {
        "date": "2025-09-15 17:43:24.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "updatedAt": {
        "date": "2025-09-15 17:50:44.815574",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "notes": "Notes",
    "isActive": true,
    "dataSource": "app"
}
```

Deleting a company

```http request
DELETE /api/companies/{id}
```

This request does not require administrator privileges.

Nothing returns (HTTP 204)