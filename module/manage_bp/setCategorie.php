<?php

include("../../include/config.php");

$response_array['status'] = 'success';

global $database_vanillabp;
global $database_host;
global $database_username;
global $database_password;

$categorie_id = array();
$message_unit = array();
$message_id = array();
$stack = array();

$db = new mysqli($database_host, $database_username, $database_password, $database_vanillabp);

if($db->connect_errno > 0){
    $response_array['status'] = 'error';
}

$cat = $_POST['cat'];
if (!isset($cat)) {
      $response_array['status'] = 'error';
}

$messages = $_POST['message'];
if (!isset($messages)) {
      $response_array['status'] = 'error';
}

$message_array = explode("|", $messages);

$sql_delete = "DELETE FROM bp_category WHERE category = '".$cat."';";


if(!$result_sql = $db->query($sql_delete)){
  $response_array['status'] = 'error';
}

foreach ($message_array as $single_message) {

	$single_message_array = explode(">", $single_message);
  $single_message_array[0]=substr($single_message_array[0], 0, -2); // remove -- at the end
  $single_message_array[0] = trim(preg_replace('/\s\s+/', ' ', $single_message_array[0])); //remove double linefeed if exist
  $single_message_array[0] = trim(preg_replace('/\s+/', ' ', $single_message_array[0])); //remove linefeed if exist
 
/* DEBUG */
//file_put_contents($file_0, $single_message_array[0]."::".$single_message_array[1]."::".$cat."\n", FILE_APPEND);
/***********/

  if (isset($single_message_array[0]) && isset($single_message_array[1])) {
    if (($single_message_array[0] != '') && ($single_message_array[1] != '')){
      $sql_insert = "INSERT INTO bp_category (bp_source, bp_name, category) VALUES('".$single_message_array[0]."','".$single_message_array[1]."','".$cat."');";
      /* DEBUG */
      //file_put_contents($file_0, "\n".$sql_insert."\n", FILE_APPEND);
      /***********/
      if(!$result_sql = $db->query($sql_insert)){
     	  $response_array['status'] = 'error';
      }
    }
  }

}
 	
$db->close();

header('Content-type: application/json');
echo json_encode($response_array);

?>
