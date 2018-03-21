<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Jean-Philippe LEVY
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

if(isset($_GET['term']) && isset($_GET['source_name']) && $_GET['source_type']) {

	include("../../../include/config.php");

	global $database_thruk;
	global $database_vanillabp;
	global $database_username;
	global $database_password;

	// Mot tapé par l'utilisateur
	extract($_GET);
	$suggestions=array();

	try {
		$bdd = new PDO('mysql:host=localhost;dbname='.$database_vanillabp, $database_username, $database_password);
		$bdd_thruk = new PDO('mysql:host=localhost;dbname='.$database_thruk, $database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}

	// Find thruk idx
	$sql = "SELECT thruk_idx FROM bp_sources WHERE db_names=?";
	$req = $bdd->prepare($sql);
	$req->execute(array($source_name));
	$thruk_idx = $req->fetch()[0];
	
	// Find hosts
	if($source_type=="hosts") {
		$sql = "SELECT host_name as object FROM ".$thruk_idx."_host WHERE host_name like ? ORDER BY host_name";
		$req = $bdd_thruk->prepare($sql);
		$req->execute(array("%".$term."%"));
	// Find services
	} elseif($source_type=="services") {
		$suggestions[]="Hoststatus";
		$sql = "SELECT distinct service_description as object 
				FROM ".$thruk_idx."_service 
				INNER JOIN ".$thruk_idx."_host ON ".$thruk_idx."_host.host_id = ".$thruk_idx."_service.host_id
				WHERE ".$thruk_idx."_host.host_name = ?
				AND ".$thruk_idx."_service.service_description != 'Hoststatus'
				ORDER BY service_description";
		$req = $bdd_thruk->prepare($sql);
		$req->execute(array($term));
	}

	while ($row = $req->fetch()) {
		$suggestions[] = $row['object'];
	}

	echo json_encode($suggestions);

}

?>
