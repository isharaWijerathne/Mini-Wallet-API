<?php 

namespace Controller\UserController;

use Exception;
use Helper\Validator;
use Http\Controller;

class UserCreateController extends Controller{

    // $instance, getInstance() and private constructer Used to Make Class Static

    private static $instance;
    private function __construct() {}


    public static function getInstance(): Controller {
        if (self::$instance === null) {
           return self::$instance = new self();
        }
        return self::$instance;
    }

    public function Execute(){

        try {

            $get_json_input = file_get_contents("php://input");
            $input_filed = json_decode($get_json_input,true);

            //Expected inputs
                //username,password
            
            //Input Validator

            Validator::InputStringValidator(isset($input_filed['username']) ? $input_filed['username'] : null,"username is required filed");
            Validator::InputStringValidator(isset($input_filed['password']) ? $input_filed['password'] : null,'password is required filed');
            Validator::PasswordValidator(isset($input_filed['password']) ? $input_filed['password'] : null);

            //HASH Password
            $hash_password = password_hash($input_filed['password'],PASSWORD_DEFAULT);


            //File Path
            $user_collection =  $_SERVER['DOCUMENT_ROOT'] ."/database/userCollection.json";

            
             $user_data = [];

            //DB Operation
            if(file_exists($user_collection)){
                $user_data_json = file_get_contents($user_collection);
                $user_data = json_decode($user_data_json,true);

                //Check Username Exist


                if(!empty($user_data)){

                    foreach($user_data as $key => $value){

                        if($value["username"] === $input_filed['username']) {
                            //Throw Error
                            throw new Exception("username already exist");
                        }
                    }
                }

                
            }
            else {
                $user_data = [];
            }

            //User Id
            //Default
            $user_id = 1;
            if( !empty($user_data) ){
                $user_id = count($user_data) +1;
            }

            //New User
            $new_user = [
                "user_id" => $user_id,
                "username" => $input_filed['username'],
                "password" => $hash_password,
                "token" => "",
            ];

            //Add New User To Collection
            $user_data[] = $new_user;
            
            //Save Database
            file_put_contents($user_collection, json_encode($user_data, JSON_PRETTY_PRINT));

            header("Content-Type: application/json");
            $response = ['status' => 'success',"message" => "User created Successfully"];
            http_response_code(200);
            echo json_encode($response);

        } catch (Exception $th) {
            
            header("Content-Type: application/json");
            $response = ['status' => 'fail',"message" => $th->getMessage()];
            http_response_code(404);
            echo json_encode($response);
        }
        
    }
}