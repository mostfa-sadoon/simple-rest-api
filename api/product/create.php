<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';
  
// instantiate product object
include_once '../objects/product.php';
$database = new Database();
$db = $database->getConnection();
$product=new product($db);

// get posted data
$data =json_decode(file_get_contents("php://input"));
// make sure data not empty
 if(
   !empty($data->name)&&
   !empty($data->description)&&
   !empty($data->price)&&
   !empty($data->category_id)
    )
    {
      // set product property values
      $product->name=$data->name;
      $product->description=$data->description;
      $product->price=$data->price;
      $product->category_id=$data->category_id;
      $product->created=date('Y-m-d H:i:s');
      
        if($product->create())
        {
            // set response code - 201 created
            http_response_code(201);
            //tell the user
            echo json_encode(array("massage"=>"enable to create product"));
        }else{//if unable to creat product,tell user
         // set response code - 503 service unavailable
         http_response_code(503);
         echo json_encode(array("masage"=>"unable to create product"));
        }

    }
    //tell user data is incompleted
    else{ 
       // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
    }
?>