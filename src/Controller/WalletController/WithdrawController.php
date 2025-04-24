<?php 
namespace Controller\WalletController;

use Helper\Validator;
 use Exception;
 use Http\Controller;

 class WithdrawController extends Controller {

      // $instance, getInstance() and private constructer Used to Make Class Static
      private static $instance;
      private function __construct() {}
  
  
      public static function getInstance(): Controller {
          if (self::$instance === null) {
              return self::$instance = new self();
          }
          return self::$instance;
      }


      public function Execute()  {

        try {
            
            //Expected Inputs
                // user_id, amount

            $get_json_input = file_get_contents("php://input");
            $input_filed = json_decode($get_json_input,true);
    
            //Validator
            Validator::IntValidator(isset($input_filed['user_id']) ? $input_filed['user_id'] : null,"Invalid user_id format");
            Validator::FloatValidator(isset($input_filed['amount']) ? $input_filed['amount'] : null,'Invalid amount format');


            //DB Operation
            $wallet_collection =  $_SERVER['DOCUMENT_ROOT'] ."/database/walletCollection.json";

            if(file_exists($wallet_collection)){

            $wallet_data_json = file_get_contents($wallet_collection);
            $wallet_data = json_decode($wallet_data_json,true);

            if(!empty($wallet_data)){

                foreach($wallet_data as $key => $value){

                    //Find Id
                    if($value["user_id"] == $input_filed['user_id'] ){
                        //depisit balance

                        $current_balance = $wallet_data[$key]['balance'];

                        if( $current_balance  >= $input_filed['amount'] ){
                            
                            $wallet_data[$key]['balance'] = $wallet_data[$key]['balance'] - $input_filed['amount'];
                        }
                        else {
                            throw new Exception("Insufficient balance");
                        }
                        

                        //Add Trasation History
                        //array_push($wallet_data["transaction_history"],[ date("Y-m-d H:i:s") => $input_filed['amount']." Deposit"]);
                        $wallet_data[$key]["transaction_history"][date("Y-m-d H:i:s")] = "WITHDRAW ".$input_filed['amount'];

                    }
                }  
            }else
            {
                throw new Exception("System error");
            }
            
            file_put_contents($wallet_collection, json_encode($wallet_data, JSON_PRETTY_PRINT));

            header("Content-Type: application/json");
            $response = ['status' => 'success',"message" => $input_filed['amount']." withdrawed succesfully"];
            http_response_code(200);
            echo json_encode($response);
        }




        } catch (Exception $th) {

            header("Content-Type: application/json");
            $response = ['status' => 'fail',"message" => $th->getMessage()];
            http_response_code(404);
            echo json_encode($response);
        }

    }
}