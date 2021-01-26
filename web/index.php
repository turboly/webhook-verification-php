<?php

// Replace this with the token received from Support Team
define('TURBOLY_WEBHOOK_SECRET_TOKEN', '9qH/38ebho2LEs5FvHs81rPLu5peO1NE');

function verify_webhook($data, $hmac_header)
{
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, TURBOLY_WEBHOOK_SECRET_TOKEN, true));
  return hash_equals($hmac_header, $calculated_hmac);
}

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri  = $_SERVER['REQUEST_URI'];
error_log("$request_method $request_uri");

if ($request_method === 'POST') {

  $content_type_header = $_SERVER['CONTENT_TYPE'];
  $hmac_header = $_SERVER['HTTP_X_TURBOLY_HMAC_SHA256'];

  $request_body = file_get_contents('php://input');
  $json_body = json_decode($request_body, true);

  $verified = verify_webhook($request_body, $hmac_header);

  error_log("Content-Type: $content_type_header");
  error_log("X-Turboly-Hmac-SHA256: $hmac_header");
  error_log('Webhook signature verified: '.var_export($verified, true));
  error_log("Request Body: $request_body");
}


echo 'ok';

?>