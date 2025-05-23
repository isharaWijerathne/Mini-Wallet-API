<?php 

namespace Controller\UserController;

use Exception;
use Helper\APIResponse;
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
            
            //Save Database user
            file_put_contents($user_collection, json_encode($user_data, JSON_PRETTY_PRINT));

            //Create Wallet and DB Operation
            $wallet_collection =  $_SERVER['DOCUMENT_ROOT'] ."/database/walletCollection.json";

            $new_wallet = [
                "user_id" => $user_id,
                "balance" => 0,
                "transaction_history" => [
                    date("Y-m-d H:i:s") => "Account Created"
                ],
                
            ];


            $wallet_data = [];
            if(file_exists($wallet_collection)){

                $wallet_data_json = file_get_contents($wallet_collection);
                $wallet_data = json_decode($wallet_data_json,true);

            }

            $wallet_data[] = $new_wallet;

            file_put_contents($wallet_collection, json_encode($wallet_data, JSON_PRETTY_PRINT));

            APIResponse::Success("User and wallet created Successfully");

        } catch (Exception $th) {
            
            APIResponse::Fail($th->getMessage());
        }
        
    }
}