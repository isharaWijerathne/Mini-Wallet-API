<?php 

namespace Controller\AuthController;

use Exception;
use Helper\APIResponse;
use Http\Controller;
use Helper\Validator;

class SignupController extends Controller {

    // $instance, getInstance() and private constructer Used to Make Class Static

    private static $instance;
    private function __construct() {}

    public static function getInstance(): Controller {
        if (self::$instance === null) {
           return self::$instance = new self();
        }
        return self::$instance;
    }

    public function Execute() {
        
        try {
            
            $get_json_input = file_get_contents("php://input");
            $input_filed = json_decode($get_json_input,true);

            //Expected inputs
                //username,password
            
            //Input Validator

            Validator::InputStringValidator(isset($input_filed['username']) ? $input_filed['username'] : null,"username is required filed");
            Validator::InputStringValidator(isset($input_filed['password']) ? $input_filed['password'] : null,'password is required filed');


            
            //File Path
            $user_collection =  $_SERVER['DOCUMENT_ROOT'] ."/database/userCollection.json";

            $new_token = "";
            $is_auth_success = false;
            $user_data = [];

            if(file_exists($user_collection)){
                $user_data_json = file_get_contents($user_collection);
                $user_data = json_decode($user_data_json,true);

                //Check Username Exist

               

                if(!empty($user_data)){

                    foreach($user_data as $key => $value){

                        //username found
                        if($value["username"] == $input_filed['username']) {
                            
                            //Verify Password
                            if(password_verify($input_filed['password'],$value["password"])){
                                
                                //Auth Complete
                                //Generate token
                                $new_token = bin2hex(random_bytes(8));

                                //Assing token
                                $user_data[$key]['token'] = $new_token;

                                

                                //Auth Success
                                $is_auth_success = true;

                                break;
                            }
                            else {
                                //Auth Fail
                                throw new Exception("try again");
                            }

                        }

                        
                    }
                }

                
            }

                //Send Result
            if($is_auth_success){

                    //Save DB
                    file_put_contents($user_collection, json_encode($user_data, JSON_PRETTY_PRINT));

                    //Responce
                    APIResponse::Success([
                        "token" => $new_token
                    ]);
            }
            else {
                //Auth Fail
                throw new Exception("try again");
            }


        } catch (Exception $th) {
           APIResponse::Fail($th->getMessage());
        }
    }

}

