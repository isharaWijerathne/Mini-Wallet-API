<?php

namespace Http;

use Http\Controller;
use Http\MiddlewareResponce;

abstract class Middleware{

    //Templete Method
    public  function  Next(Controller $controller){

        //Middlware Fun Run
        
        if($this->MiddlewareAction() === MiddlewareResponce::NEXT){
             //Execute Controller
            $controller->Execute();
        }else {
            
            //Send Fail Responce
            $this->MiddlewareFailResponce();
        }
       
    }

    //Middleware Fun
    abstract public function MiddlewareAction(): MiddlewareResponce;

    //Fail Responce
    abstract public function MiddlewareFailResponce();

    //For Make Middlware Static
    abstract static  function getInstance(): Middleware;
}
