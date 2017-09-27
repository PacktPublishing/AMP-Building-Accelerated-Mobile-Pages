<?php
// Endpoint for product search, only returns results for "tshirt"

sleep(3);
header('Content-Type: application/json');
//header('AMP-Access-Control-Allow-Source-Origin: http://localhost');

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: https://theampbook-com.cdn.ampproject.org');
header('Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin');
header('AMP-Access-Control-Allow-Source-Origin: https://theampbook.com');

$keywords = isset($_REQUEST["keywords"])?$_REQUEST["keywords"]:"";

$keywords = strtolower(filter_var($keywords, FILTER_SANITIZE_STRING));
if(strpos("tshirt", $keywords)!==false) {
  header("HTTP/1.0 200 Ok");
  $content = '{"keywords":"'.$keywords .'",
"results":[
  {"name":"T-shirt: Super Pouvoir","price":"€9.99","description":"Super cool super power t-shirt","image":"img/tshirt-0-f-red-320.png"},
  {"name":"T-shirt: La Biere","price":"€10.99","description":"Beer t-shirt","image":"img/tshirt-10-f-black-320.png"},
  {"name":"T-shirt: Les Pizzas","price":"€7.99","description":"Je deteste courier, mais j\'aime trop pizza t-shirt","image":"img/tshirt-5-f-blue-320.png"},
  {"name":"T-shirt: Les Pizzas","price":"€7.99","description":"Don\'t judge me when I run t-shirt","image":"img/tshirt-6-f-green-320.png"}
  ]
}
';
}
else {
  header("HTTP/1.0 200 Ok");
  $content = '{"keywords":"'.$keywords .'","empty":"true"}';
}

echo $content;
