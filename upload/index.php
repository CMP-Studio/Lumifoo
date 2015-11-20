<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . "/../app/util/api.php";
require_once __DIR__ . "/../templates/head.php";
require_once __DIR__ . "/../templates/body.php";
require_once __DIR__ . "/../config/luminate.php";



if(isset($_POST["survey_id"]))
{
  $id = $_POST["survey_id"];
  $_SESSION["survey_id"] = $id;
}
else {
  print "No ID";
  die();
}

$url = "https://secure2.convio.net/cmp/site/SRConsAPI";
$param = array(
  "response_format"=>"json",
  "api_key"=>api_key(),
  "v"=>"1.0",
  "method"=>"getSingleSignOnToken",
  "login_name"=>api_login(),
  "login_password"=>api_pass()
);

$res = sendAPI($url,$param, true);

if(isset($res->getSingleSignOnTokenResponse->token))
{
  $token = $res->getSingleSignOnTokenResponse->token;
  $_SESSION["sso_token"] = $token;
}
else {
  var_dump($res);
  die();
}


$url = "https://secure2.convio.net/cmp/site/CRSurveyAPI";
$param = array(
  "response_format"=>"json",
  "api_key"=>api_key(),
  "v"=>"1.0",
  "sso_auth_token"=>$token,
  "method"=>"getSurvey",
  "survey_id"=>$id
);

$res = sendAPI($url,$param);

if(isset($res->getSurveyResponse->survey->surveyQuestions))
{
    $Qs = $res->getSurveyResponse->survey->surveyQuestions;
}
else {
  die();
}

if(isset($Qs->surveyInstanceId))
{
  $Qs = array($Qs);
}

$questions = array();
foreach ($Qs as $key => $q)
{
  $questions[$q->questionId] = array();
  $questions[$q->questionId]["text"] = $q->questionText;
  $questions[$q->questionId]["type"] = $q->questionType;

}

$_SESSION["survey_questions"] = $questions;

body_open();
 ?>

 <form action="/fields/" method="POST" enctype="multipart/form-data">
   <label for="csvFileUpload">Upload Wufoo CSV</label>
   <input type="file" name="csv_file" id="csvFileUpload">
   <button class="btn btn-default" type="submit">Next</button>
</form>

<?php body_close(); ?>
