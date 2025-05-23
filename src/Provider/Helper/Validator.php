<?php 

namespace Helper;

use Exception;
class Validator {

    //This Class Uses To Validate Inputs

    public static function InputStringValidator($input_string,$error_message){
        if($input_string == "" || $input_string == null || !isset($input_string) ){
            
            throw new Exception($error_message);
        }
    }


    public static function PasswordValidator($password) {
        if( strlen($password) <= 8  ||  !preg_match('/[!@#$%^&*()]/', $password) ) {
            throw new Exception("Invalid password format");
        }
    }

    public static function FloatValidator($input,$error_message){
        if(!is_float($input)){
            throw new Exception($error_message);
        }
    }

    public static function IntValidator($input,$error_message){
        if(!is_int($input)){
            throw new Exception($error_message);
        }
    }

}