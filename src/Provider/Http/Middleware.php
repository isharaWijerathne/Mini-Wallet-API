<?php

namespace Http;

use Http\IController;
use Http\MiddlewareResponce;

abstract class Middleware{

    //Templete Method
    public function Next(IController $controller){

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
}
