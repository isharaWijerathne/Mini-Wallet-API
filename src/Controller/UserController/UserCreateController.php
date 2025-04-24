<?php 

namespace Controller\UserController;

use Http\Controller;

class UserCreateController extends Controller{

    private static $instance;
    private function __construct() {}


    public static function getInstance(): Controller {
        if (self::$instance === null) {
           return self::$instance = new self();
        }
        return self::$instance;
    }

    public function Execute(){

        $get_json_input = file_get_contents("php://input");
        $input_filed = json_decode($get_json_input,true);

        //Expexted inputs
            //username,password

        //Input Filed Validator
        if(isset($input_filed['username']) && isset($input_filed['password'])){

            echo $input_filed['username'];
        }else{
            echo "Fail";
        }
        
    }
}