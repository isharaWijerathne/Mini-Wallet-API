<?php 

namespace Helper;

class APIResponse {
    private function __construct() {}

    public static function Success($message){

        header("Content-Type: application/json");
        $response = ['status' => 'success',"message" => $message ];
        http_response_code(200);
        echo json_encode($response);
    }

    public static function Fail($message){

        header("Content-Type: application/json");
        $response = ['status' => 'fail',"message" => $message ];
        http_response_code(response_code: 404);
        echo json_encode($response);
    }
}