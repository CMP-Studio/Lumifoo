<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/app/util/api.php";
require_once __DIR__ . "/templates/head.php";
require_once __DIR__ . "/templates/body.php";
require_once __DIR__ . "/config/luminate.php";

$url = "https://secure2.convio.net/cmp/site/CRSurveyAPI";
$param = array(
  "response_format"=>"json",
  "api_key"=>api_key(),
  "method"=>"listSurveys",
  "published_only"=>"true",
  "list_category_id"=>"1761",
  "list_page_size"=>"500",
  "v"=>"1.0"
);

$res = sendAPI($url,$param);


$surveys = $res->listSurveysResponse->surveys;

if(isset($surveys->surveyName))
{
  $surveys = array($surveys);
}

body_open();
 ?>

 <form action="upload/" method="post">
   <div id="survey_table">
   <table class="table">
     <tr>
       <th>Surveys</th>
     </tr>
     <?php
     foreach ($surveys as $key => $s) {
       ?>
      <tr>
        <td>
          <div class="radio">
          <label>
            <input type="radio" name="survey_id" id="sr_<?php print $s->surveyId; ?>" value="<?php print $s->surveyId; ?>">
            <?php
           print $s->surveyName;
           ?>
          </label>
        </div>
        </td>
      </tr>
      <?php } ?>
   </table>
 </div>
 <div id="survey_submit">
   <button class="btn btn-default" type="submit">Next</button>
 </div>
 </form>
 <?php body_close(); ?>
