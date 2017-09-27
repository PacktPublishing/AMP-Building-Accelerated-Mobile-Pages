<?php

header('Content-Type: application/json');
//header('AMP-Access-Control-Allow-Source-Origin: http://localhost');

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: https://theampbook-com.cdn.ampproject.org');
header('Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin');
header('AMP-Access-Control-Allow-Source-Origin: https://theampbook.com');

include "CartFileStorage.php";

$client_id = isset($_REQUEST["client_id"])?$_REQUEST["client_id"]:"";
if(empty($client_id)) {
  //check cookie
  $client_id = isset($_COOKIE['cart'])?$_COOKIE['cart']:"";
}
$client_id = filter_var($client_id, FILTER_SANITIZE_STRING);

$del = isset($_REQUEST["del"])?$_REQUEST["del"]:"";
$del = filter_var($del, FILTER_SANITIZE_STRING);

$get_and_empty = isset($_REQUEST["get_and_empty"])?$_REQUEST["get_and_empty"]:"";
$get_and_empty = filter_var($get_and_empty, FILTER_SANITIZE_STRING);

if(!empty($get_and_empty)) {
  $cart_json = CartFileStorage::fetchThenEmptyCart($client_id);
}
else if(!empty($empty_cart)) {
  $cart_json = CartFileStorage::emptyCart($client_id);
}
else if(!empty($del)) {
  $cart_json = CartFileStorage::removeFromCart($client_id, $del);
}
else {
  $cart_json = CartFileStorage::getCart($client_id);
}

if($cart_json!=false) {
  header("HTTP/1.0 200 Ok");
  $content = $cart_json;
}

echo $content;