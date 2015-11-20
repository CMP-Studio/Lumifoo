<?php
session_start();

require_once __DIR__ . "/../app/util/api.php";
require_once __DIR__ . "/../templates/head.php";
require_once __DIR__ . "/templates/body.php";
require_once __DIR__ . "/../config/luminate.php";

$csvPath = $_SESSION["csv_file_loc"];

$csv = array_map('str_getcsv', file($csvPath));

$fieldMap = array();
foreach ($_POST as $lum => $wuf) {
  if($wuf != "N/A")
  {
    $fieldMap[$lum] = intval($wuf);
  }
}

//var_dump($csv);

//Generate API urls
$postVars = array();


$id = $_SESSION["survey_id"];





foreach ($csv as $rNum => $row)
{
  if($rNum == 0) continue; //skip header row
  $quest = array();
  foreach ($fieldMap as $lum => $wuf)
  {
    $quest["question_$lum"] = $row[$wuf];
  }
  $t_params = array_merge($params, $quest);
  if(!isset($t_params)) continue;
  $postVars[] = $t_params;
}
$count = count($postVars);

$data = array("rows"=>$count,"data"=>$postVars);

$vars = json_encode($data);



body_open();
 ?>
 <h2>Importing Responses</h2>
 <h3>Successfully Imported</h3>
 <div class="progress">
   <div id="api_prog" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
   </div>
 </div>
 <h3>Failures</h3>
 <div class="progress">
   <div id="api_err" class="progress-bar progress-bar-striped progress-bar-danger active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
   </div>
 </div>

 <script>
  $( document ).ready()
  {
    var total;
    var complete = 0;
    var error = 0;
    runImport();

    function runImport()
    {
      var data = getData();
      var postVars = data.data;
      var count = data.rows;
      total = count;
      for (var i = 0; i < count; i++)
      {
          $.post("ajax.php", postVars[i])
         .done(function(result)
        {
          if (typeof result.submitSurveyResponse != 'undefined') {
            if(result.submitSurveyResponse.success)
            {
              complete++;
              updateProgress(complete, total);
              return;
            }
          }
          error++;
          console.log(result);
          updateErrors(error,total);
        });
      }

    }
    function updateProgress(val, total)
    {
      var width = (val / total) * 100;
      $("#api_prog").css("width", width + "%");
    }

    function updateErrors(val, total)
    {
      var width = (val / total) * 100;
      $("#api_err").css("width", width + "%");
    }

    function getData()
    {
      var data = <?php print $vars; ?>;
      return data;
    }





  }
 </script>
 <?php body_close(); ?>
