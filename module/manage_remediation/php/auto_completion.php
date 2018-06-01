<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Bastien PUJOS
# VERSION : 2.0
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

include("../../../include/config.php");
include("../../../include/function.php");

global $database_thruk, $database_eorweb;

extract($_GET);
$autocomplete = array();
if ($source_type == "services") {
	array_push($autocomplete, "Hoststatus");
}
if (isset($source_name)) {
	$source_name = htmlspecialchars($source_name);
}

if($source_type == "services") {
	$req = "SELECT host_id FROM ".$source_name."_host where lower(host_name)=lower(?)";
	$ids = sqlrequest($database_thruk,$req,false,array("s",(string)$source_host));
	$id = mysqli_result($ids,0,"host_id");

	$requests = "SELECT DISTINCT service_description FROM ".$source_name."_service WHERE host_id=".$id." AND lower(service_description) like lower(?)";
	$result = sqlrequest($database_thruk,$requests,false,array("s","%$term%"));
} 
elseif ($source_type == "hosts") {
	$requests = "SELECT DISTINCT host_name FROM ".$source_name."_host WHERE lower(host_name) like lower(?)";
	$result = sqlrequest($database_thruk,$requests,false,array("s","%$term%"));
} 
else {
	if (isset($id)) {
		$requests = "SELECT description FROM remediation_group WHERE id NOT IN (SELECT id_group FROM remediation_action INNER JOIN remediation ON remediation.id = remediationID AND remediationID != ".$id.") AND description like ?";
	} else {
		$requests = "SELECT description FROM remediation_group WHERE id NOT IN (SELECT id_group FROM remediation_action INNER JOIN remediation ON remediation.id = remediationID) AND description like ?";
	}
	$result = sqlrequest($database_eorweb,$requests,false,array("s","%$term%"));
}

while ($line = mysqli_fetch_array($result)){
	if($line[0] != "NR"){
		$autocomplete[] = $line[0];
	}
}

echo json_encode($autocomplete);

?>