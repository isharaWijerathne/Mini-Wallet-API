
# Mini Wallet API

This web API demonstrates how core PHP can be applied in REST API development. 
The application uses a custom-built framework to manage API requests. 
It runs on an Nginx server, and data storage is handled using JSON files in the filesystem.

## Run Application
Install my-project with npm

```bash
  docker compose up
```

## Http namespace

The Http namespace provides classes and abstract clasess that handle HTTP requests. 
The HttpMethod class handles HTTP GET and POST requests. 
The Controller abstract class is used to create controllers for routes. 
The Middleware abstract class is with a template method. 
The HTTP namespace uses the HttpRequest and MiddlewareResponse enums for type safety.

## Routes

#### /signup
This route requires user_id and password in JSON format.
Response with token,

#### /create-user
This route requires user_id and password in JSON format. 
The route creats user and wallet. 
Response with token,

#### /deposit
This route work with auth middleware and it requiers bearer token.
Route require user_id and amount.

#### /withdraw
This route work with auth middleware and it requiers bearer token.
Route require user_id and amount.

#### /balance
This route is requier url parameter user_id.
Responce with curent account balance

#### /transactions
This route is requier url parameter user_id.
Responce with account transcation histoy