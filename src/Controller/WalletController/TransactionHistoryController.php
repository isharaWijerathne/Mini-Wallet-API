<?php 
namespace Controller\WalletController;

use Exception;
use Http\Controller;

class TransactionHistoryController extends Controller {

    // $instance, getInstance() and private constructer Used to Make Class Static
    private static $instance;
    private function __construct()
    {
    }

    public static function getInstance(): Controller
    {
        if (self::$instance === null) {
            return self::$instance = new self();
        }
        return self::$instance;
    }


    public function Execute()
    {
        try {

            //URI Params Validation
            if (!isset($_GET['user_id'])) {
                throw new Exception("required uri params missing");
            }

            $user_id = $_GET['user_id'];
            $transaction_histoy = [];
            $is_transaction_histoy_availabe = false;

            //DB Operation
            $wallet_collection = $_SERVER['DOCUMENT_ROOT'] . "/database/walletCollection.json";

            if (file_exists($wallet_collection)) {

                $wallet_data_json = file_get_contents($wallet_collection);
                $wallet_data = json_decode($wallet_data_json, true);

                if (!empty($wallet_data)) {

                    foreach ($wallet_data as $key => $value) {

                        //Find Id
                        if ($value["user_id"] == $user_id) {
                            $is_transaction_histoy_availabe = true;
                            $transaction_histoy = $value["transaction_history"];
                        }

                    }

                } else {
                    throw new Exception("System error");

                }

            }


            if($is_transaction_histoy_availabe){
                header("Content-Type: application/json");
                $response = ['status' => 'success',"message" => [
                    "user_id" => intval($user_id) ,
                    "transaction_history" => $transaction_histoy
                ]];
                http_response_code(200);
                echo json_encode($response);
            }else {
                throw new Exception("Invalid user_id");
            }


        } catch (Exception $th) {
            header("Content-Type: application/json");
            $response = ['status' => 'fail', "message" => $th->getMessage()];
            http_response_code(404);
            echo json_encode($response);
        }
    }

}