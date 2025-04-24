<?php

namespace Http;

use Helper\APIResponse;
use Http\HttpMethod;
use Http\Middleware;
use Http\Controller;

class HttpRequest {

    //HttpMethod
    private $http_method;

    //RequestURI
    private $request_uri;

    private $app_route = [];
    public function __construct($http_method,$request_uri){

        $this->http_method = $http_method;
        $this->request_uri = $request_uri;
    }

    //HttpGet Method With Controller
    public function Get($uri,Controller $controller){

        //AddRouteAray
        array_push($this->app_route,strtok($uri,"?"));

        //Allow Quary Strings
        $uri_valid_length = strpos($this->request_uri,"?");
        $pocess_uri = substr($this->request_uri,0,$uri_valid_length);

        //Sample URL -> /product (Allow)
        //Sample URL -> /product? (Allow)
        //Sample URL -> /product?value=pari (Allow)
        if(($this->http_method === HttpMethod::GET->value  && $pocess_uri === $uri)
            || ($this->http_method === HttpMethod::GET->value  && $this->request_uri === $uri)
        ){
            
            //Execute Controller
            $controller->Execute();
        }
    }



    //HttpGet Method With Controller Wiht Middlware

    public function GetWithMiddleware($uri,Middleware $middleware,Controller $controller){

         //AddRouteAray
         array_push($this->app_route,strtok($uri,"?"));


        //Allow Quary Strings
        $uri_valid_length = strpos($this->request_uri,"?");
        $pocess_uri = substr($this->request_uri,0,$uri_valid_length);

        //Sample URL -> /product (Allow)
        //Sample URL -> /product? (Allow)
        //Sample URL -> /product?value=pari (Allow)
        if(($this->http_method === HttpMethod::GET->value  && $pocess_uri === $uri)
            || ($this->http_method === HttpMethod::GET->value  && $this->request_uri === $uri)
        ){
            
            //Execute Middlware Then Controller
            $middleware->Next($controller);
        }
    }


    //HttpPost Method With Controller
    public function Post($uri,Controller $controller){

         //AddRouteAray
         array_push($this->app_route,strtok($uri,"?"));

        //Sample URL -> /product (Allow)
        if(
             ($this->http_method === HttpMethod::POST->value  && $this->request_uri === $uri)
        ){
            
            //Execute Controller
            $controller->Execute();
        }
    }


    //HttpPost Method With Controller Wiht Middlware

    public function PostWithMiddleware($uri,Middleware $middleware,Controller $controller){

         //AddRouteAray
         array_push($this->app_route,strtok($uri,"?"));

        //Sample URL -> /product (Allow)
        if(
             ($this->http_method === HttpMethod::POST->value  && $this->request_uri === $uri)
        ){
            
            //Execute Middlware Then Controller
            $middleware->Next($controller);
        }
    }
    

    //Other Route

    public function WildCardRoute($uri)   {

        $allow_uri = strtok($uri,"?");
        if(
         !in_array($allow_uri,$this->app_route))
        {
            APIResponse::Fail("Invalid route");
        }
    }



}