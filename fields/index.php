<?php
session_start();

require_once __DIR__ . "/../app/util/api.php";
require_once __DIR__ . "/../templates/head.php";
require_once __DIR__ . "/../templates/body.php";
require_once __DIR__ . "/../config/files.php";


if(isset($_FILES["csv_file"]))
{
  $csv = $_FILES["csv_file"];
}
else {
  die();
}

$tmp = $csv["tmp_name"];
$dir = getFilesDir();
$new = tempnam($dir, "csv_");
move_uploaded_file($tmp, $new);

$_SESSION["csv_file_loc"] = $new;

/* Get file info */

$csv_f = fopen($new, "r");
$fields = fgetcsv($csv_f);
fclose($csv_f);

//$col = array("","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

function printSelectHTML($fields, $name)
{
?>
<select id="field_<?php print $name ?>" name="<?php print $name ?>">
  <option value="N/A" selected>Do Not Import</option>
<?php
  foreach ($fields as $key => $f) {
    ?>
      <option value="<?php print $key ?>"><?php print "($key) $f" ?></option>
    <?php
  }
?>
</select>
<?php
}


$questions = $_SESSION["survey_questions"];

body_open();
 ?>

 <form action="/import/" method="post">
   <div id="survey_table">
   <table class="table">
     <tr>
       <th>Luminate Survey Field</th>
       <th>Luminate Field Type</th>
       <th>Wufoo Import Field</th>
     </tr>
     <?php
     foreach ($questions as $key => $q) {
       ?>
      <tr>
        <td>
          <?php print $q["text"]; ?>
        </td>
        <td>
          <?php print $q["type"]; ?>
        </td>
        <td>
          <?php printSelectHTML($fields, $key); ?>
        </td>
      </tr>
      <?php } ?>
   </table>
 </div>
 <div id="field_submit">
   <button class="btn btn-default" type="submit">Start Import</button>
 </div>
 </form>

 <?php body_close(); ?>
