<?php 

namespace Middleware;

use Exception;
use Helper\Validator;
use Http\Middleware;
use Http\MiddlewareResponce;

 class AuthMiddleware extends Middleware {

    // $instance, getInstance() and private constructer Used to Make Class Static
    private static $instance;
    private function __construct() {}
    public static function getInstance(): Middleware {
        if (self::$instance === null) {
           return self::$instance = new self();
        }
        return self::$instance;
    }


    public  function MiddlewareFailResponce(){

        header("Content-Type: application/json");
        $response = ['status' => 'fail',"message" => "auth fail"];
        http_response_code(404);
        echo json_encode($response);
    }

    public  function MiddlewareAction() : MiddlewareResponce {


        $headers = explode(" ",$_SERVER["HTTP_AUTHORIZATION"]);
        echo $headers[1];



        try {

            //For This Miffleware user_id and bearer token is Required
            
            //Check Auth Header
            if(!isset($_SERVER["HTTP_AUTHORIZATION"])){
                throw new Exception("AUTH_FAIL");
            }

            $headers = explode(" ",$_SERVER["HTTP_AUTHORIZATION"]);
            $received_token = $headers[1];

            //Check Bear token
            if($headers[1] != "Bearer"){
                throw new Exception("AUTH_FAIL");
            }

            $get_json_input = file_get_contents("php://input");
            $input_filed = json_decode($get_json_input,true);

            //user_id Validate
            Validator::InputStringValidator(isset($input_filed['user_id']) ? $input_filed['user_id'] : null,"AUTH_FAIL");


            //Check token
            $is_token_valid = false;
            $user_collection =  $_SERVER['DOCUMENT_ROOT'] ."/database/userCollection.json";

            if(file_exists($user_collection)){
                $user_data_json = file_get_contents($user_collection);
                $user_data = json_decode($user_data_json,true);

                //Check Token
                if(!empty($user_data)){

                    foreach($user_data as $key => $value){

                        if($value["token"] === $received_token) {
                            
                            //Auth Success
                            $is_token_valid =true;
                        }
                    }
                }

                
            }
            
            if($is_token_valid) {
                return MiddlewareResponce::NEXT;
            }else {
                return MiddlewareResponce::FAIL;
            }


        } catch (Exception $th) {
           return MiddlewareResponce::FAIL; 
        }

    }
}