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

include("/srv/eyesofnetwork/eorweb/include/config.php");
include("/srv/eyesofnetwork/eorweb/include/function.php");

global $database_thruk, $database_vanillabp;

$autocomplete=array();

if(isset($_GET['source_type'])){
	if($_GET['source_type'] == 'services'){
		$req="SELECT host_id FROM ".$_GET['source_name']."_host where host_name='".$_GET['source_host']."'";
		$ids = sqlrequest($database_thruk,$req);
		$id = mysqli_result($ids,0,"host_id");
		
		$requests="SELECT DISTINCT service_description FROM ".$_GET['source_name']."_service WHERE host_id=".$id;
	}else{
		$requests="SELECT DISTINCT host_name FROM ".$_GET['source_name']."_host";
	}
	$result = sqlrequest($database_thruk,$requests);
}else{
	$request="SELECT distinct thruk_idx FROM bp_sources";
	$result=sqlrequest($database_vanillabp,$request);
}

while ($line = mysqli_fetch_array($result)){
	if($line[0] != "NR"){
		$autocomplete[]=$line[0];
	}
}

$autocomplete= array_unique($autocomplete);
	
echo json_encode($autocomplete);

?>