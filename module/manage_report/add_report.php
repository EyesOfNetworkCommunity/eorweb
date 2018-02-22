<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Jean-Philippe LEVY
# VERSION 2.0
# APPLICATION : eorweb for eyesofreport project
#
# LICENCE :
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
#########################################
*/

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
