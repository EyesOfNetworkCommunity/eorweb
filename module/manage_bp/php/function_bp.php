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

include("../../../include/config.php");

global $database_vanillabp;
global $database_username;
global $database_password;

$action = isset($_GET['action']) ? $_GET['action'] : false;
$bp_name = isset($_GET['bp_name']) ? $_GET['bp_name'] : false;
$host_name = isset($_GET['host_name']) ? $_GET['host_name'] : false;
$service = isset($_GET['service']) ? $_GET['service'] : false;
$new_services = isset($_GET['new_services']) ? $_GET['new_services'] : false;
$uniq_name = isset($_GET['uniq_name']) ? $_GET['uniq_name'] : false;
$uniq_name_orig = isset($_GET['uniq_name_orig']) ? $_GET['uniq_name_orig'] : false;
$process_name = isset($_GET['process_name']) ? $_GET['process_name'] : false;
$display = isset($_GET['display']) ? $_GET['display'] : false;
$url = isset($_GET['url']) ? $_GET['url'] : false;
$command = isset($_GET['command']) ? $_GET['command'] : false;
$type = isset($_GET['type']) ? $_GET['type'] : false;
$min_value = isset($_GET['min_value']) ? $_GET['min_value'] : false;
$source_name = isset($_GET['source_name']) ? $_GET['source_name'] : $database_vanillabp;
$source_type = isset($_GET['source_type']) ? $_GET['source_type'] : false;

try {
	$bdd = new PDO('mysql:host=localhost;dbname='.$source_name, $database_username, $database_password);
} catch(Exception $e) {
	echo "Connection failed: " . $e->getMessage();
	exit('Impossible de se connecter à la base de données.');
}

try {
	$bdd_global = new PDO('mysql:host=localhost;dbname='.$database_vanillabp, $database_username, $database_password);
} catch(Exception $e) {
	echo "Connection failed: " . $e->getMessage();
	exit('Impossible de se connecter à la base de données.');
}

if($action == 'verify_services'){
    verify_services($bp_name,$host_name,$bdd);
}

elseif($action == 'delete_bp'){
	delete_bp($bp_name,$source_name,$bdd,$bdd_global);
}

elseif($action == 'list_services'){
    list_services($host_name);
}

elseif($action == 'list_process'){
	list_process($bp_name,$display,$bdd);
}

elseif ($action == 'add_services'){
	add_services($bp_name,$new_services,$bdd);
}

elseif ($action == 'add_process'){
    add_process($bp_name,$new_services,$bdd);
}

elseif ($action == 'add_application'){
	add_application($uniq_name_orig,$uniq_name,$process_name,$display,$url,$command,$type,$min_value,$source_name,$source_type);
}

elseif ($action == 'build_file'){
	build_file($bdd);
}

elseif ($action == 'info_application'){
	info_application($bp_name,$bdd);
}

elseif ($action == 'check_app_exists'){
	check_app_exists($uniq_name, $bdd);
}

function verify_services($bp,$host,$bdd) {
	$sql = "SELECT COUNT(*),service FROM bp_services WHERE bp_name = '" . $bp . "' AND host = '". $host . "'";
	$req = $bdd->query($sql);
	$informations = $req->fetch();
	$number_services = intval($informations['COUNT(*)']);
	$service = $informations['service'];
	echo $bp . "::" . $host . "::" . $number_services . "::" . $service;
}

function add_application($uniq_name_orig,$uniq_name,$process_name,$display,$url,$command,$type,$min_value,$source_name,$source_type,$escape=false) {
	
	global $database_vanillabp;
	global $bdd, $bdd_global;
	
	if($type != 'MIN'){
		$min_value = "";
	}
	$sql = "SELECT count(*) FROM bp WHERE name = ?;";
	$req = $bdd->prepare($sql);
	$req->execute(array($uniq_name));
	$bp_exist = $req->fetch();
	
	// add
	if($bp_exist[0] == 0 and empty($uniq_name_orig)){
		$sql = "INSERT INTO bp (name,description,priority,type,command,url,min_value) VALUES(?,?,?,?,?,?,?)";
		$req = $bdd->prepare($sql);
		$req->execute(array($uniq_name,$process_name,$display,$type,$command,$url,$min_value));
	
		// if application create CI and CA
		if($source_type == "app") {
			
			// set app defined
			$sql = "UPDATE bp set is_define=1 where name=?";
			$req = $bdd->prepare($sql);
			$req->execute(array($uniq_name));
			
			// Create CI & CA
			$sql = "INSERT INTO bp (name,description,priority,type,command,url,min_value) VALUES(?,?,?,?,?,?,?)";
			$req = $bdd->prepare($sql);
			$ci=$uniq_name."_CI";
			$ca=$uniq_name."_CA";
			$req->execute(array($ci,$ci,2,$type,$command,$url,$min_value));
			$req->execute(array($ca,$ca,2,$type,$command,$url,$min_value));
			$sql = "INSERT INTO bp_category (bp_source,bp_name,category) VALUES(?,?,?)";
			$req = $bdd->prepare($sql);
			$req->execute(array("global",$ci,"Core Infrastructure"));
			$req->execute(array("global",$ca,"Customer Access"));
			
			// Link CI & CA to app
			$sql = "INSERT INTO bp_links (bp_name,bp_link,bp_source) VALUES(?,?,?)";
			$req = $bdd->prepare($sql);
			$req->execute(array($uniq_name,$ci,"global"));
			$req->execute(array($uniq_name,$ca,"global"));
		}
	}
	// uniq name modification
	elseif($uniq_name_orig != $uniq_name) {
		if($bp_exist[0] != 0){
			// TODO QUENTIN
		} else {
			$sql = "UPDATE bp set name = ?,description = ?,priority = ?,type = ?,command = ?,url = ?,min_value = ? WHERE name = ?";
			$req = $bdd->prepare($sql);
			$req->execute(array($uniq_name,$process_name,$display,$type,$command,$url,$min_value,$uniq_name_orig));
			$sql = "UPDATE bp_links set bp_name = ? WHERE bp_name = ?";
			$req = $bdd->prepare($sql);
			$req->execute(array($uniq_name,$uniq_name_orig));	
			$sql = "UPDATE bp_links set bp_link = ? WHERE bp_link = ?";
			$req = $bdd->prepare($sql);
			$req->execute(array($uniq_name,$uniq_name_orig));
			$sql = "UPDATE bp_services set bp_name = ? WHERE bp_name = ?";
			$req = $bdd->prepare($sql);
			$req->execute(array($uniq_name,$uniq_name_orig));
			
			// Update links in global
			if($source_name != $database_vanillabp) {
				$sql = "UPDATE bp_links set bp_link = ? WHERE bp_link = ? and bp_source = ?";
				$req = $bdd_global->prepare($sql);
				$req->execute(array($uniq_name,$uniq_name_orig,substr($source_name,0,-9)));
			}
			// Update global 
			else {
				if(!$escape) {
					// Contracts
					$sql = "UPDATE contract_context_application SET APPLICATION_NAME = ? WHERE APPLICATION_NAME = ? AND APPLICATION_SOURCE = ?";
					$req = $bdd_global->prepare($sql);
					$req->execute(array($uniq_name,$uniq_name_orig,$source_name));
				
					// Category
					$sql = "SELECT count(*) FROM bp_category WHERE (bp_name = ? OR bp_name = ?);";
					$req = $bdd_global->prepare($sql);
					$req->execute(array($uniq_name_orig."_CI",$uniq_name_orig."_CI"));
					$bp_cat = $req->fetch();
					
					// If category
					if($bp_cat[0]!=0) {
						add_application($uniq_name_orig."_CI",$uniq_name."_CI",$uniq_name."_CI",2,$url,$command,$type,$min_value,$source_name,"bp",true);
						add_application($uniq_name_orig."_CA",$uniq_name."_CA",$uniq_name."_CA",2,$url,$command,$type,$min_value,$source_name,"bp",true);
					}
				} else {
					$sql = "UPDATE bp_category set bp_name = ? WHERE bp_name = ?";
					$req = $bdd_global->prepare($sql);
					$req->execute(array($uniq_name,$uniq_name_orig));
				}
			}
		}
	}	
	// modification
	else{
		$sql = "UPDATE bp set name = ?,description = ?,priority = ?,type = ?,command = ?,url = ?,min_value = ? WHERE name = ?";
		$req = $bdd->prepare($sql);
		$req->execute(array($uniq_name,$process_name,$display,$type,$command,$url,$min_value,$uniq_name));
	}
}

function delete_bp($bp,$bp_source,$bdd,$bdd_global,$escape=false) {
	
	global $database_vanillabp;
		
	$sql = "DELETE FROM bp WHERE name = ?";
	$req = $bdd->prepare($sql);
	$req->execute(array($bp));

	$sql = "DELETE FROM bp_services WHERE bp_name = ?";
	$req = $bdd->prepare($sql);
	$req->execute(array($bp));

	$sql = "DELETE FROM bp_links WHERE bp_name = ?";
	$req = $bdd->prepare($sql);
	$req->execute(array($bp));
	
	$sql = "DELETE FROM bp_links WHERE bp_link = ?";
	$req = $bdd->prepare($sql);
	$req->execute(array($bp));

	// Delete links in global
	if($bp_source != $database_vanillabp) {
		$sql = "DELETE FROM bp_links WHERE bp_link = ? and bp_source = ?";
		$req = $bdd_global->prepare($sql);
		$req->execute(array($bp, substr($bp_source,0,-9)));
	} 
	// Delete contracts contexts links
	else {
		
		$sql = "DELETE FROM bp_category WHERE bp_name = ?";
		$req = $bdd_global->prepare($sql);
		$req->execute(array($bp));
		
		if(!$escape) {
			// Contracts
			$sql = "DELETE FROM contract_context_application WHERE APPLICATION_NAME = ? and APPLICATION_SOURCE = ?";
			$req = $bdd_global->prepare($sql);
			$req->execute(array($bp, $bp_source));
		
			// Category
			$sql = "SELECT count(*) FROM bp_category WHERE (bp_name = ? OR bp_name = ?) ;";
			$req = $bdd_global->prepare($sql);
			$req->execute(array($bp."_CI",$bp."_CA"));
			$bp_cat = $req->fetch();
			
			if($bp_cat[0]!=0) {
				delete_bp($bp."_CA",$bp_source,$bdd,$bdd_global,true);
				delete_bp($bp."_CI",$bp_source,$bdd,$bdd_global,true); 
			}
		}
	}
	
}

function list_services($host_name) {
        $path_nagios_ser = "/srv/eyesofnetwork/nagios/etc/objects/services.cfg";
 
	$tabServices = array() ;
        $tabServices['service'] = array() ;
	$lignes = file($path_nagios_ser);
        $hasMatch = 0;
	$pattern = "/^$host_name$/"; /**Modification BVI 15/03/2017 */
                
    foreach( $lignes as $ligne) {
 	/**Modification BVI 15/03/2017 
        if ( preg_match("/$host_name$/", trim($ligne), $match)) {  //Get Host name
            $hasMatch = 1;
        }*/
	$host_ligne = trim(str_replace("host_name", " ", $ligne));
        if ( preg_match($pattern, trim($host_ligne), $match)) {  //Get Host name
            $hasMatch = 1;
        }
        elseif ( preg_match("#^service_description#", trim($ligne))) {
            //$service = preg_split("/[\s]+/", trim($ligne));
            $service = trim(str_replace("service_description", " ", $ligne));
            //Modification BVI //$service = preg_split("/[\s]+/", trim($ligne));
            if ($hasMatch)
                $tabServices['service'][] = $service;
                //Modification BVI $tabServices['service'][] = $service[1];
            $hasMatch = 0;
        }
    }
    natcasesort($tabServices['service']);
    array_unshift($tabServices['service'],"Hoststatus");
    echo json_encode($tabServices);
}

// List sources informations
function list_sources($bp_source=false) {
	
	global $bdd_global;
	
	if($bp_source) {
		$sql = "SELECT db_names,nick_name,thruk_idx FROM bp_sources where db_names = ?";
		$req = $bdd_global->prepare($sql);
		$req->execute(array($bp_source));
	} else {
		$sql = "SELECT db_names,nick_name,thruk_idx FROM bp_sources";
		$req = $bdd_global->prepare($sql);
		$req->execute();
	}
	
	$sources = $req->fetchall();
	return $sources;
	
}

// List BPs
function list_process($bp,$display,$bdd) {
	
	global $database_vanillabp, $source_name, $bdd_global;
	
	// If global
	if($source_name == $database_vanillabp) {
				
		$sources = list_sources();
		$sql="";
		$prepare=array();
		
		foreach($sources as $source) {
			$app="";
			if($source["db_names"] == $database_vanillabp) {
				$app="AND name not in(select distinct LEFT(bp_name,LOCATE('_C',bp_name) - 1) from bp_category)";
			}
			$sql.="SELECT name,'".$source["db_names"]."' as source_name FROM ".$source["db_names"].".bp WHERE name!=? AND priority = ? $app
					AND name not in(select bp_name from ".$database_vanillabp.".bp_links where bp_link=?) 
					AND name not in(select bp_link from ".$database_vanillabp.".bp_links where bp_name=?) UNION ";
			$prepare[]=$bp;
			$prepare[]=$display;
			$prepare[]=$bp;
			$prepare[]=$bp;
		}
		$sql=substr($sql,0,-7);
		
		$req = $bdd->prepare($sql);
		$req->execute($prepare);
		$process = $req->fetchall();
		
	}
	else {
		$sql = "SELECT name,'".$source_name."' as source_name FROM bp WHERE name!=? AND priority = ?
				AND name not in(select bp_name from bp_links where bp_link=?) 
				AND name not in(select bp_link from bp_links where bp_name=?)";
		$req = $bdd->prepare($sql);
		$req->execute(array($bp,$display,$bp,$bp));
		$process = $req->fetchall();
	}

    echo json_encode($process);
}

function add_services($bp,$services,$bdd) {

	$list_services = array();
	$old_list_services = array();
		
	if(is_array($services)) {
		foreach($services as $values){
			$value = explode("::", $values);
			$service = $value[1];
			$list_services[] = $service;
		}
	} else {
		$services = array();
	}

	$sql = "DELETE FROM bp_services WHERE bp_name = ?";
	$req = $bdd->prepare($sql);
	$req->execute(array($bp));

	if(count($services) > 0){
		$sql = "UPDATE bp set is_define = 1 WHERE name = ?";
		$req = $bdd->prepare($sql);
		$req->execute(array($bp));
	}
	else{
		$sql = "UPDATE bp set is_define = 0 WHERE name = ?";
		$req = $bdd->prepare($sql);
		$req->execute(array($bp));
    }

	if(is_array($services)) {
		foreach($services as $values){
			$value = explode("::", $values);
			$host = $value[0];
			$service = $value[1];
			echo $service;
			$sql = "INSERT INTO bp_services (bp_name,host,service) VALUES(?,?,?)";
			$req = $bdd->prepare($sql);
			$req->execute(array(trim($bp),$host,$service));
		}
	}
}

function add_process($bp,$process,$bdd) {
		
	global $source_name, $database_vanillabp;
	
	$sql = "DELETE FROM bp_links WHERE bp_name = ?";
	$req = $bdd->prepare($sql);
	$req->execute(array($bp));
	$sql = "UPDATE bp set is_define = 0 WHERE name = ?";
	$req = $bdd->prepare($sql);
	$req->execute(array($bp));

    if(count($process) > 0 AND is_array($process)){
		$sql = "UPDATE bp set is_define = 1 WHERE name = ?";
		$req = $bdd->prepare($sql);
		$req->execute(array($bp));
		
		foreach($process as $values){
			$value = explode("::", $values);
			$bp_infos = explode("||", $value[1]);
			$bp_link = $bp_infos[0];
			$bp_source = $bp_infos[1];
			
			if($source_name==$database_vanillabp) {
				$sql = "INSERT INTO bp_links (bp_name,bp_link,bp_source) VALUES(?,?,?)";
				$req = $bdd->prepare($sql);
				$req->execute(array($bp,$bp_link,$bp_source));
			} else {
				$sql = "INSERT INTO bp_links (bp_name,bp_link) VALUES(?,?)";
				$req = $bdd->prepare($sql);
				$req->execute(array($bp,$bp_link));
			}
		}	
	}
}

// Check app exists in all sources
function check_app_exists($uniq_name, $bdd) {
	
	$sources = list_sources();
	$sql = "";
	$prepare = array();
	foreach($sources as $source) {
		$sql.="SELECT name FROM ".$source["db_names"].".bp WHERE name=? UNION ";
		$prepare[]=$uniq_name;
	}
	$sql=substr($sql,0,-7);

	$req = $bdd->prepare($sql);
	$req->execute($prepare);
	$bp_exist = $req->rowCount();

	if($bp_exist == 1){
		echo "true";
	} else {
		echo "false";
	}
}

function info_application($bp_name, $bdd) {
	$sql = "SELECT * FROM bp WHERE name = ?";
	$req = $bdd->prepare($sql);
	$req->execute(array($bp_name));
	$info = $req->fetch();
	echo json_encode($info);
}

?>
