<?php
// Endpoint for signup XHR submissions

header('Content-Type: application/json');
//header('AMP-Access-Control-Allow-Source-Origin: http://localhost');

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: https://theampbook-com.cdn.ampproject.org');
header('Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin');
header('AMP-Access-Control-Allow-Source-Origin: https://theampbook.com');

$email = isset($_REQUEST["email"])?$_REQUEST["email"]:"";

// Handle malformed email address
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header("HTTP/1.0 400 Conflict");
  $content = '{"email":"'.$email .'", "message":"The email address is not valid"}';
}

// Pretend it's already subscribed
else if($email=="subscribed@example.com") {
 header("HTTP/1.0 409 Conflict");
  $content = '{"email":"'.$email .'", "message":"The email address is already subscribed"}';
} 

else {
  // It's ok
  header("HTTP/1.0 200 Ok");
  $content = '{"email":"'.$email .'"}';
}

echo $content;
