<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/../app/util/api.php";
require_once __DIR__ . "/../config/luminate.php";

header('Content-Type: application/json');

$id = $_SESSION["survey_id"];
$token = $_SESSION["sso_token"];

$params = array(
  "response_format"=>"json",
  "api_key"=>api_key(),
  "v"=>"1.0",
  "method"=>"submitSurvey",
  "survey_id"=>$id,
  "sso_auth_token" => $token
);

$post = array_merge($params, $_POST);



$url = "https://secure2.convio.net/cmp/site/CRSurveyAPI";
$return = sendAPI($url, $post, true);

print json_encode($return);


 ?>
