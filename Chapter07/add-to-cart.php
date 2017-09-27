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

$cart_json = CartFileStorage::addToCart($client_id, $product_id, $product_name, $price, 1, $image, $size);

if($cart_json!=false) {
  header("HTTP/1.0 200 Ok");
  $content = $cart_json;
}

echo $content;
