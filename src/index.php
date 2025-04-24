<?php


require __DIR__ . '/vendor/autoload.php';

use Controller\AuthController\SignupController;
use Controller\UserController\UserCreateController;
use Controller\WalletController\DepositController;
use Http\HttpRequest;
use Middleware\AuthMiddleware;



$app = new HttpRequest($_SERVER["REQUEST_METHOD"],$_SERVER["REQUEST_URI"]);


$app->Post("/signup",SignupController::getInstance());
$app->Post("/create-user",UserCreateController::getInstance());

//Wallert Controller
$app->PostWithMiddleware("/deposit",AuthMiddleware::getInstance(),DepositController::getInstance());

