
# Mini CRM API

*Lightweight REST API built with Symfony, designed as a foundation for a mini CRM system. Demonstrates authentication, authorization, event-driven architecture, email handling, and developer tooling.*


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
- nginx - web server
- phpmyadmin - database management panel
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
```json
{
    "email": "testemail@example.pl",
    "password": "12345678",
    "passwordConfirm": "12345678"
}
```

Example success response (HTTP 201)
```json
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

```json
{
    "token": "<TOKEN>"
}
```

#### USERS

```http
  GET /api/users
```

Returns a list of users in the application (HTTP 200)

Example response:

```json
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
        {"..."}
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
```json
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

```json
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

```json
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

```json
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

```json
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

```json
{
    "firstName": "John",
    "lastName": "Doe",
    "email": "emaail@email.com",
    "company": 68
}
```

Example response (HTTP 200)
```json
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

```json
{
    "firstName": "John",
    "lastName": "Doe",
    "email": "emaail@email.com",
    "company": null
}
```

Will return the result:

```json
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
```json
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

```json
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

```json
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
```json
{
    "name": "New company name",
    "vatNumber": "PL8668355726",
    "isActive": true
}
```

Example response (HTTP 200)
```json
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
```json
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

### Tasks

```http request
  GET /api/tasks
```
Returns a list of tasks in the app

Example response (HTTP 200)

```json
 {
            "id": 50,
            "name": "Modi suscipit et repudiandae rem reiciendis.",
            "description": "Sunt ea est amet non delectus. Sunt laborum quia et possimus sapiente et. Explicabo dolor nemo ad sint eum eos eveniet. Autem ad dolores ut et fuga eaque.",
            "dueDate": {
                "date": "2025-08-29 18:28:47.000000",
                "timezone_type": 3,
                "timezone": "UTC"
            },
            "createdAt": {
                "date": "2025-09-11 17:40:13.000000",
                "timezone_type": 3,
                "timezone": "UTC"
            },
            "updatedAt": null,
            "assignedTo": {
                "id": 77,
                "email": "kalinowski.olgierd@example.org",
                "roles": [
                    "ROLE_USER"
                ],
                "source": "faker"
            },
            "client": {
                "id": 53,
                "firstName": "Ewa",
                "lastName": "Wysocka",
                "dataSource": "faker",
                "company": {
                    "id": 65,
                    "name": "Borkowski i syn",
                    "email": "justyna.laskowska@example.net",
                    "source": "faker"
                },
                "email": "blaszczyk.jagoda@example.net",
                "phone": "+48 71 726 18 24"
            },
            "company": null,
            "status": "completed",
            "source": "faker"
        },
        {...}
    ],
    "pagination": {
        "page": 1,
        "limit": 20,
        "total": 13,
        "pages": 1
    }
}
```

Task creation

```http request
POST api/tasks
```
**By default, every new task has the status pending.**

| Parameter     | Type      | Description                                  |
|:--------------|:----------|:---------------------------------------------|
| `name`        | `string`  | **Required**                                 |
| `description` | `string`  |                                              |
| `dueDate`     | `string`  | **Required**   In format YYYY-MM-DD HH:MM:SS |
| `assignedTo`  | `integer` | existing user ID                             |
| `client`      | `integer` | existing client ID                           |
| `company`     | `integer` | existing company ID                          |
| `company`     | `integer` | existing company ID                          |

Example request

```json
{
    "name": "Test task",
    "dueDate": "2025-05-11 12:11:33",
}
```

Example response (HTTP 201)
```json
{
    "id": 52,
    "name": "Test task",
    "description": null,
    "dueDate": {
        "date": "2025-05-11 12:11:33.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "createdAt": {
        "date": "2025-09-16 18:05:00.089561",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "updatedAt": null,
    "assignedTo": null,
    "client": null,
    "company": null,
    "status": "pending",
    "source": "app"
}
```

Task details

```http request
GET api/tasks/{id}
```
Example response (HTTP 200)

```json
{
    "id": 52,
    "name": "Test task",
    "description": null,
    "dueDate": {
        "date": "2025-05-11 12:11:33.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "createdAt": {
        "date": "2025-09-16 18:05:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "updatedAt": null,
    "assignedTo": null,
    "client": null,
    "company": null,
    "status": "pending",
    "source": "app"
}
```

Update task

```http request
PATCH api/tasks/{id}
```


| Parameter    | Type      | Description                                                                            |
|:-------------|:----------|:---------------------------------------------------------------------------------------|
| `name`       | `string`  | It cannot be empty if it is sent to a request.                                         |
| `dueDate`    | `string`  | It cannot be empty if it is sent to a request.                                         |
| `status`     | `string`  | 'pending', 'in_progress', 'waiting_for_client', 'completed', 'cancelled', 'closed'     |
| `client`     | `integer` | if a null value or a non-existent resource is specified, a null value will be assigned |
| `company`    | `integer` | if a null value or a non-existent resource is specified, a null value will be assigned |
| `assignedTo` | `integer` | if a null value or a non-existent resource is specified, a null value will be assigned |

Example request

```json
{
    "name": "New name",
    "dueDate": "025-09-15 12:00:00",
    "assignedTo": 82,
    "client": 51,
    "company": 64,
    "status": "completed"
}
```

Example response (HTTP 200)

```json
{
    "id": 52,
    "name": "New name",
    "description": null,
    "dueDate": {
        "date": "2025-09-15 12:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "createdAt": {
        "date": "2025-09-16 18:05:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "updatedAt": {
        "date": "2025-09-16 18:23:52.053133",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "assignedTo": {
        "id": 82,
        "email": "dominika08@example.net",
        "roles": [
            "ROLE_USER"
        ],
        "source": "faker"
    },
    "client": {
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
    "company": {
        "id": 64,
        "name": "Kwiatkowski",
        "email": "jasinski.damian@example.com",
        "source": "faker"
    },
    "status": "completed",
    "source": "app"
}
```

Deleting task

```http request
DELETE api/tasks/{id}
```
Returns only response code 204


## Unit tests
Run inside the php8.4 container:

```
composer test
```

## Code style (CS Fixer)

Run inside the php8.4 container:

```
composer cs
```

## Symfony events

### Client created event
*Dispatched after successfully creating a client with:*
```http request
POST api/clients
```
This event sends an email, which can be viewed in Mailhog: http://localhost:8025

### User registered event
*Dispatched after successful user registration with:*
```http request
POST api/register
```
This event also sends an email, available in Mailhog.

## Symfony commands

### Create admin
In the php8.4 container

```http request
php bin/console app:create-admin-user
```
*This command starts an interactive wizard that guides you through creating a user with administrator privileges.*

### Start generating test data (Faker PHP)
In the php8.4 container

```http request
php bin/console app:add-fake-data
```
*This command launches a wizard that guides you through generating fake data for testing.*

**All resources created this way will include the property:**
```http request
source: "faker"
```
