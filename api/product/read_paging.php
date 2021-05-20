<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/product.php';

// utilities
$utilities = new Utilities();

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$product = new Product($db);

//query product 
$stmt=$product->readpaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0)
{
   // products array
   $products_arr=array();
   $products_arr['product']=array();
   $products_arr['paginig']=array();

   while($row=$stmt->fetch(PDO::FETCH_ASSOC))
   {
    extract($row);
    $product_item=array();
    $product_item=array(
            "id"=>$id,
            "name"=>$name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
    );
    array_push($products_arr["product"], $product_item);
   }
    // include paging

    $total_rows=$product->count();
    $page_url="{$home_url}product/read_paging.php?";
    $paging=array();
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    array_push($products_arr["paginig"], $paging);
    
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($products_arr);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user products does not exist
    echo json_encode(
        array("message" => "No products found.")
    );   
}
?>