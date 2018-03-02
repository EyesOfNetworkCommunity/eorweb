<?php

include("../../include/config.php");

$response_array['status'] = 'success';

global $database_eorweb;
global $database_host;
global $database_username;
global $database_password;

$db = new mysqli($database_host, $database_username, $database_password, $database_eorweb);

if($db->connect_errno > 0){
    $response_array['status'] = 'error';
}


if (isset($_POST['id'])) {
	$id = $_POST['id'];
} else {
	$response_array['status'] = 'error';
}

$sql_delete = "DELETE FROM reports WHERE report_id = '".$id."';";

if(!$result_sql = $db->query($sql_delete)){
  $response_array['status'] = 'error';
}
 	
$db->close();

header('Content-type: application/json');
echo json_encode($response_array);

?>