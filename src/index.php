<?php


require __DIR__ . '/vendor/autoload.php';

use Http\HttpRequest;
use Http\IController;
use Http\Middleware;
use Http\MiddlewareResponce;


class TestCon implements IController {

    public function Execute(){

        $json = json_decode(file_get_contents('php://input'), true);
       
        header("Content-Type: application/json");
        $response = ['message' => $json];
          http_response_code(200);
          echo json_encode($response);
    }
}

    $testcon = new TestCon();

 $HttpRequest = new HttpRequest($_SERVER["REQUEST_METHOD"],$_SERVER["REQUEST_URI"]);
 $HttpRequest->Get("/apple",$testcon);


class MyM extends Middleware {

    public function MiddlewareFailResponce(){

        header("Content-Type: application/json");
        $response = ['message' => 'Success from route1 (GET)'];
          http_response_code(200);
          echo json_encode($response);
    }
    public function MiddlewareAction(): MiddlewareResponce {
        return MiddlewareResponce::NEXT;
    }
}

 



$mid = new MyM();
$HttpRequest->PostWithMiddleware("/bb",$mid,$testcon);



// header("Content-Type: application/json");

// $request_method = $_SERVER["REQUEST_METHOD"];
// $request_uri = $_SERVER["REQUEST_URI"];

// $response = [];

// if($request_method == "GET" && $request_uri == "/test"){
//     $response = ['message' => 'Success from route1 (GET)'];
//     http_response_code(200);
// }else {
//     $response = ['message' => 'Method Not Allowed'];
//         http_response_code(405);
// }

// echo json_encode($response);

