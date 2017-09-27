<?php

header('Content-Type: application/json');
//header('AMP-Access-Control-Allow-Source-Origin: http://localhost');

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: https://theampbook-com.cdn.ampproject.org');
header('Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin');
header('AMP-Access-Control-Allow-Source-Origin: https://theampbook.com');

include "CartFileStorage.php";

$product_id = isset($_REQUEST["product_id"])?$_REQUEST["product_id"]:"";
$product_id = filter_var($product_id, FILTER_SANITIZE_STRING);

$product_name = isset($_REQUEST["product_name"])?$_REQUEST["product_name"]:"";
$product_name = filter_var($product_name, FILTER_SANITIZE_STRING);

$image = isset($_REQUEST["image"])?$_REQUEST["image"]:"";
$image = filter_var($image, FILTER_SANITIZE_STRING);

$price = isset($_REQUEST["price"])?$_REQUEST["price"]:"";
$price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

$size = isset($_REQUEST["size"])?$_REQUEST["size"]:"";
$size = filter_var($size, FILTER_SANITIZE_STRING);

$client_id = isset($_REQUEST["client_id"])?$_REQUEST["client_id"]:"";
$client_id = filter_var($client_id, FILTER_SANITIZE_STRING);
// if(!empty($client_id)) {
//   setcookie("amp_cid", $client_id, time() + (86400 * 30), "/");
// }

$cart_json = CartFileStorage::addToCart($client_id, $product_id, $product_name, $price, 1, $image, $size);

if($cart_json!=false) {

// $keywords = strtolower(filter_var($keywords, FILTER_SANITIZE_STRING));
// if(strpos("tshirt", $keywords)!==false) {
  header("HTTP/1.0 200 Ok");
  $content = $cart_json;
}



//   $content = '{
//   "cart_total_price": "19.98",
//   "cart_items": [
//     {
//       "product_id": "tshirt-1",
//       "product_name": "T-Shirt: Super Pouvoir",
//       "product_price": "9.99",
//       "quantity": "1"
//     },
//     {
//       "product_id": "tshirt-1",
//       "product_name": "T-Shirt: Super Pouvoir",
//       "product_price": "9.99",
//       "quantity": "1"
//     }
//   ]
// }
// ';
// }
// else {
//   header("HTTP/1.0 200 Ok");
//   $content = '{"keywords":"'.$keywords .'","empty":"true"}';
// }

// Handle malformed email address
// if(!filter_var($keywords, FILTER_VALIDATE_EMAIL)) {
//   header("HTTP/1.0 400 Conflict");
//   $content = '{"email":"'.$email .'", "message":"The email address is not valid"}';
// }

// Pretend it's already subscribed
// else if($email=="subscribed@example.com") {
//  header("HTTP/1.0 409 Conflict");
//   $content = '{"email":"'.$email .'", "message":"The email address is already subscribed"}';
// } 

// else {
//   // It's ok
//   header("HTTP/1.0 200 Ok");
//   $content = '{"email":"'.$email .'"}';
// }

echo $content;
