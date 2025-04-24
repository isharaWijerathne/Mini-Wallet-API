<?php
namespace Controller\WalletController;

use Exception;
use Helper\APIResponse;
use Helper\Validator;
use Http\Controller;
class WalletBalanceController extends Controller
{

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
            $account_balance = 0;
            $is_balance_availabe = false;

            //DB Operation
            $wallet_collection = $_SERVER['DOCUMENT_ROOT'] . "/database/walletCollection.json";

            if (file_exists($wallet_collection)) {

                $wallet_data_json = file_get_contents($wallet_collection);
                $wallet_data = json_decode($wallet_data_json, true);

                if (!empty($wallet_data)) {

                    foreach ($wallet_data as $key => $value) {

                        //Find Id
                        if ($value["user_id"] == $user_id) {
                            $is_balance_availabe = true;
                            $account_balance = $value["balance"];
                        }

                    }

                } else {
                    throw new Exception("System error");

                }

            }


            if($is_balance_availabe){
               

                APIResponse::Success([
                    "user_id" => intval($user_id) ,
                    "balance" => $account_balance
                ]);

            }else {
                throw new Exception("Invalid user_id");
            }


        } catch (Exception $th) {
            APIResponse::Fail($th->getMessage());
        }
    }

}