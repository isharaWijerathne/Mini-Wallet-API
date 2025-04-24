<?php


require __DIR__ . '/vendor/autoload.php';

use Controller\AuthController\SignupController;
use Controller\UserController\UserCreateController;
use Controller\WalletController\DepositController;
use Controller\WalletController\TransactionHistoryController;
use Controller\WalletController\WalletBalanceController;
use Controller\WalletController\WithdrawController;
use Http\HttpRequest;
use Middleware\AuthMiddleware;



$app = new HttpRequest($_SERVER["REQUEST_METHOD"],$_SERVER["REQUEST_URI"]);

// http request pipeline start
$app->Post("/signup",SignupController::getInstance());
$app->Post("/create-user",UserCreateController::getInstance());

//Wallert Controller
$app->PostWithMiddleware("/deposit",AuthMiddleware::getInstance(),DepositController::getInstance());
$app->PostWithMiddleware("/withdraw",AuthMiddleware::getInstance(),WithdrawController::getInstance());

$app->Get("/balance",WalletBalanceController::getInstance());
$app->Get("/transactions",TransactionHistoryController::getInstance());

//wildCard route
$app->WildCardRoute($_SERVER["REQUEST_URI"]);

// http request pipeline end