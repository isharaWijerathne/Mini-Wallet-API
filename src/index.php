<?php


require __DIR__ . '/vendor/autoload.php';

use Controller\UserController\UserCreateController;
use Http\HttpRequest;
use Middleware\AuthMiddleware;



$app = new HttpRequest($_SERVER["REQUEST_METHOD"],$_SERVER["REQUEST_URI"]);




$app->Post("/create-user",UserCreateController::getInstance());