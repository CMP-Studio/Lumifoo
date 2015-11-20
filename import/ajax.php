<?php
session_start();
require_once __DIR__ . "/../app/util/api.php";
header('Content-Type: application/json');


$token = $_SESSION["sso_token"];

$params = array(
  "response_format"=>"json",
  "api_key"=>api_key(),
  "v"=>"1.0",
  "method"=>"submitSurvey",
  "survey_id"=>$id,
  "sso_auth_token" => $token
);

$post = array_merge($params, $_POST)



$url = "https://secure2.convio.net/cmp/site/CRSurveyAPI";
$return = sendAPI($url, $post, true);

print json_encode($return);


 ?>
