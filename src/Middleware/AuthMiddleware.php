<?php 

namespace Middleware;

use Http\Middleware;
use Http\MiddlewareResponce;

 class AuthMiddleware extends Middleware {

    // 
    private static $instance;
    private function __construct() {}
    public static function getInstance(): Middleware {
        if (self::$instance === null) {
           return self::$instance = new self();
        }
        return self::$instance;
    }


    public  function MiddlewareFailResponce(){

            echo "This is Data";
    }

    public  function MiddlewareAction() : MiddlewareResponce {
        return MiddlewareResponce::NEXT;
    }
}