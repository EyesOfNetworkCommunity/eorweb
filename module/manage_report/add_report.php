<?php

include("../../include/config.php");

$response_array['status'] = 'success';

global $database_eonweb;
global $database_host;
global $database_username;
global $database_password;

$db = new mysqli($database_host, $database_username, $database_password, $database_eonweb);

if($db->connect_errno > 0){
    $response_array['status'] = 'error';
}

$rpt_name = $_POST['rpt_name'];
if (!isset($rpt_name)) {
      $response_array['status'] = 'error';
}

$rpt_filename = $_POST['rpt_filename'];
if (!isset($rpt_filename)) {
      $response_array['status'] = 'error';
}

$sql_add = "INSERT INTO reports values ('','".$rpt_name."','".$rpt_filename."','other');";

if(!$result_sql = $db->query($sql_add)){
  $response_array['status'] = 'error';
}
 	
$db->close();

header('Content-type: application/json');
echo json_encode($response_array);

?>
