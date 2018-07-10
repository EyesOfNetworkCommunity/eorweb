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

include("../../header.php");
include("../../side.php");

global $database_eorweb;
global $database_thruk;

// Init action
$action=null;
if(isset($_GET["action"])) {
	$action=$_GET["action"];
} ?>

<div id="page-wrapper">
	<?php
	if(isset($_POST["actions"])) {
		$actions=$_POST["actions"];
	} else {
		$actions="";
	}
	
	$remediation_action_selected = retrieve_form_data("remediation_action_selected",null);
	$remediation_selected = retrieve_form_data("remediation_selected",null);

	?>
	<div class="row">
		<div class="col-lg-12">
			<?php
			if($action == null or $action == "remediation") {
				echo "<h1 class=\"page-header\">".getLabel("label.manage_remediation.list_remediations")."</h1>";
			} elseif($action == "remediation_action") {
				echo "<h1 class=\"page-header\">".getLabel("label.manage_remediation.list_remediations_action")."</h1>";
			} ?>
		</div>
	</div>
	
	<?php
	// define if the user is validator or not 
	$req = "SELECT validator FROM groups WHERE group_id = ?";
	$validator_bool = sqlrequest($database_eorweb,$req,false,array("i",(int)$_COOKIE['group_id']));
	$validator = mysqli_result($validator_bool,0,"validator");
	

	switch($actions){
		case "del_method":
			if (isset($remediation_selected[0])) {
				$total_remediation = "";
				for ($i = 0; $i < sizeof($remediation_selected); $i++) {
					// Get remediation_action name
					$user_res = sqlrequest($database_eorweb,"SELECT name FROM remediation WHERE id = '$remediation_selected[$i]'");
					$remediation = mysqli_result($user_res,0,"name");
					$total_remediation .= $remediation.", ";

					// Delete group
					$group = sqlrequest($database_eorweb, "SELECT id_group FROM remediation_action WHERE remediationID = '$remediation_selected[$i]'");
					while ($line = mysqli_fetch_array($group)){
						sqlrequest($database_eorweb, "DELETE FROM remediation_group WHERE id = '$line[0]'");
					}
					
					// Delete 
					sqlrequest($database_eorweb,"DELETE FROM remediation WHERE id = '$remediation_selected[$i]'");
					sqlrequest($database_eorweb,"DELETE FROM remediation_action WHERE remediationID = '$remediation_selected[$i]'");
					
					// Logging action
					logging("manage_remediation","DELETE : $remediation_selected[$i]");
				}
				$total_remediation = substr($total_remediation,0,-2);
				message(6," : ".getLabel("message.manage_remediation.request_delete")." ( $total_remediation )",'ok');
			}
			break;

		case "del_method_remediation":
			if(isset($remediation_action_selected[0])) {
				$total_remediation_action = "";
				for ($i = 0; $i < sizeof($remediation_action_selected); $i++) {
					// Get remediation_action name
					$user_res = sqlrequest($database_eorweb,"SELECT description FROM remediation_action WHERE id_group='$remediation_action_selected[$i]'");
					$remediation_action = mysqli_result($user_res,0,"description");
					
					// Delete remediation action
					sqlrequest($database_eorweb,"DELETE FROM remediation_action WHERE id_group = '$remediation_action_selected[$i]'");
					$sql_group = sqlrequest($database_eorweb, "SELECT description FROM remediation_group WHERE id = '$remediation_action_selected[$i]'");
					$remediation_group_name = mysqli_result($sql_group,0,"description");
					sqlrequest($database_eorweb,"DELETE FROM remediation_group WHERE id = '$remediation_action_selected[$i]'");
					
					// Logging action
					logging("manage_remediation","DELETE : $remediation_action_selected[$i]");
					$total_remediation_action .= $remediation_action.", ";
				}
				$total_remediation_action = substr($total_remediation_action,0,-2);
				message(6," : ".getLabel("message.manage_remediation.action_delete")." ( $total_remediation_action )",'ok');
			}
			break;

		case "validation":
			if(isset($remediation_selected[0])) {
				$total_validate = "";
				$total_not_validate = "";
				$validate = false;
				$not_validate = false;

				for ($i = 0; $i < sizeof($remediation_selected); $i++) {
					$req = sqlrequest($database_eorweb, "SELECT state,name FROM remediation WHERE id='$remediation_selected[$i]'");
					$state = mysqli_result($req,0,"state");
					$name = mysqli_result($req,0,"name");

					// Update remediations state
					if ($validator) {
						$validate = true;
						sqlrequest($database_eorweb,"UPDATE remediation SET state='approved', date_validation='".date("Y-m-d G:i")."' WHERE id='$remediation_selected[$i]'");
						$total_validate .= $name.", ";
					} else {
						$not_validate = true;
						$total_not_validate .= $name.", ";
					}
				}
				if ($validate) {
					$total_validate = substr($total_validate,0,-2);
					message(6," : ".getLabel("message.manage_remediation.validate")." ( $total_validate )",'ok');
				}
				if ($not_validate) {
					$total_not_validate = substr($total_not_validate,0,-2);
					message(0," : ".getLabel("message.manage_remediation.validate_fail")." ( $total_not_validate )",'critical');
				}
			}
			break;

		case "refus":
			if(isset($remediation_selected[0])) {
				$total_refuse = "";
				$total_not_refuse = "";
				$refuse = false;
				$not_refuse = false;
				
				for ($i = 0; $i < sizeof($remediation_selected); $i++) {
					$req = sqlrequest($database_eorweb, "SELECT state,name FROM remediation WHERE id='$remediation_selected[$i]'");
					$state = mysqli_result($req,0,"state");
					$name = mysqli_result($req,0,"name");

					// Update remediations state if request-remediation has not been executed
					if ($state != "executed") {
						$refuse = true;
						sqlrequest($database_eorweb,"UPDATE remediation SET state='refused', date_validation='".date("Y-m-d G:i")."' WHERE id='$remediation_selected[$i]'");
						$total_refuse .= $name.", ";
					} else {
						$not_refuse = true;
						$total_not_refuse .= $name.", ";
					}
				}
				
				// display message if a request_remdiation has been updated
				if ($refuse) {
					$total_refuse = substr($total_refuse,0,-2);
					message(6," : ".getLabel("message.manage_remediation.refuse")." ( $total_refuse )",'ok');
				} 
				if ($not_refuse) {
					$total_not_refuse = substr($total_not_refuse,0,-2);
					message(0," : ".getLabel("message.manage_remediation.not_refuse")." ( $total_not_refuse )",'critical');
				}
			}
			break;

		case "demand":
			if(isset($remediation_selected[0])) {
				$total_demand = "";
				$total_not_demand = "";
				$demand = false;
				$not_demand = false;

				for ($i = 0; $i < sizeof($remediation_selected); $i++) {
					$req = sqlrequest($database_eorweb, "SELECT state,name FROM remediation WHERE id='$remediation_selected[$i]'");
					$state = mysqli_result($req,0,"state");
					$name = mysqli_result($req,0,"name");

					// Update remediations state
					if ($state == "inactive" || $state == "refused") {
						$demand = true;
						sqlrequest($database_eorweb,"UPDATE remediation SET state='waiting', date_validation='".date("Y-m-d G:i")."' WHERE id='$remediation_selected[$i]'");
						$total_demand .= $name.", ";
					} else {
						$not_demand = true;
						$total_not_demand .= $name.", ";
					}	
				}

				// display message if a request_remdiation has been updated
				if ($demand) {
					$total_demand = substr($total_demand,0,-2);
					message(6," : ".getLabel("message.manage_remediation.demand")." ( $total_demand )",'ok');
				} 
				if ($not_demand) {
					$total_not_demand = substr($total_not_demand,0,-2);
					message(0," : ".getLabel("message.manage_remediation.not_demand")." ( $total_not_demand )",'critical');
				}
			}
			break;
		
		case "execution":
			if(isset($remediation_selected[0])) {
				// Create CSV files with infos in DB
				$array = array('Valid','"Date debut"','"Heure debut"','"Date fin"','"Heure fin"','Type','Host','Service');
				$array = str_replace('"', '', $array);
				$execute = array();
				$total_execute = "";
				$total_fail = "";
				$success_execution = false;
				$fail_execution = false;

				for ($i = 0; $i < sizeof($remediation_selected); $i++) {
					$req = sqlrequest($database_eorweb, "SELECT state,name FROM remediation WHERE id='$remediation_selected[$i]'");
					$state = mysqli_result($req,0,"state");
					$name = mysqli_result($req,0,"name");
					
					// execution if validator or approved
					if ( $validator || $state == "executed" || $state == "approved" ) {
						$success_execution=true;
						$RemediationExec = sqlrequest($database_eorweb,"SELECT * FROM remediation_action WHERE remediationID='$remediation_selected[$i]' ORDER BY id_group");
	
						//Remediation group get file name
						$RemediationGroupExec = sqlrequest($database_eorweb,"SELECT distinct id_group FROM remediation_action WHERE remediationID='$remediation_selected[$i]'");
						$ids = "";
						while($line = mysqli_fetch_array($RemediationGroupExec)) {
							$ids .= $line["id_group"]." or id=";
						}
						$ids = substr($ids, 0, -7);
						
						//remediation group file exist test
						$remediation_group = sqlrequest($database_eorweb, "SELECT description FROM remediation_group WHERE id=".$ids);
						while($line = mysqli_fetch_array($remediation_group)) {
							$description = str_replace(' ', '_', $line["description"]);
							if(file_exists("/srv/eyesofreport/etl/injection/Inject".$description.".csv")){
								exec("rm -rf /srv/eyesofreport/etl/injection/Inject".$description.".csv");
							}
						}
						
						while($line = mysqli_fetch_array($RemediationExec)) {
							
							if($line['Action'] == "add") {
								$lignes = array();
								$exist = true;
								
								$remediation_group = sqlrequest($database_eorweb, "SELECT description FROM remediation_group WHERE id='$line[id_group]'");
								$group_name = mysqli_fetch_all($remediation_group,MYSQLI_ASSOC);
								$delimiteur = ';';
								$type = ($line["type"] == "maintenance") ? "Downtime" : "Outage";
								$dateDebut = explode(" ", date('d/m/Y H:i:s', strtotime($line["DateDebut"])));
								$dateFin = explode(" ", date('d/m/Y H:i:s', strtotime($line["DateFin"])));
								$lignes[] = array('O', $dateDebut[0], $dateDebut[1], $dateFin[0], $dateFin[1], $line["type"], $line["host"], $line["service"]);
							
								// Paramétrage de l'écriture du futur fichier CSV
								$chemin = "Inject".str_replace(' ', '_', $group_name[0]["description"]).".csv";
								if(!isset($oldchemin) || $oldchemin != $chemin){
									$remdem = array();
									$remdem[0]= $chemin;
									$remdem[1]= $line['source'];
									$remdem[2]= $type;
									
									$execute[]= $remdem;
								}
								
								// Création du fichier csv
								if(!file_exists("/srv/eyesofreport/etl/injection/".$chemin)){
									$exist = false;
								}
								
								//ouverture du fichier et éciture
								$fichier_csv = fopen("/srv/eyesofreport/etl/injection/".$chemin, 'a+');
								if(!$exist){
									fputs($fichier_csv, implode($array, $delimiteur)."\n");
								}

								foreach($lignes as $ligne){
									fputcsv($fichier_csv, $ligne, $delimiteur);
								}
								fclose($fichier_csv);
								$oldchemin = $chemin;
							}
				
							// if delete incident	
							elseif($line['Action'] == "delete" && $line['type'] == "incident") {
								$host_id = mysqli_result(sqlrequest($database_thruk, "SELECT host_id FROM ".$line['source']."_host WHERE host_name = '".$line['host']."'"),0,"host_id");
								$service_id = mysqli_result(sqlrequest($database_thruk, "SELECT service_id FROM ".$line['source']."_service WHERE service_description = '".$line['service']."' AND host_id = '".$host_id."'"),0,"service_id");
								$output_id = mysqli_result(sqlrequest($database_thruk,"SELECT output_id from ".$line['source']."_plugin_output where output ='OK;HARD;4;' limit 1"),0,"output_id");
								
								// delete outage service
								if($line['service'] != "Hoststatus") {
									sqlrequest($database_thruk,"UPDATE ".$line['source']."_log SET message = $output_id WHERE  time BETWEEN ".strtotime($line['DateDebut'])." AND ".strtotime($line['DateFin'])." AND state_type ='HARD' AND host_id =".$host_id." AND service_id = ".$service_id);
									sqlrequest($database_thruk,"UPDATE ".$line['source']."_log SET state = 0 WHERE  time BETWEEN ".strtotime($line['DateDebut'])." AND ".strtotime($line['DateFin'])." AND state_type ='HARD' AND host_id =".$host_id." AND service_id = ".$service_id);
								} else {
									sqlrequest($database_thruk,"UPDATE ".$line['source']."_log SET message = $output_id WHERE  time BETWEEN ".strtotime($line['DateDebut'])." AND ".strtotime($line['DateFin'])." AND state_type ='HARD' AND host_id =".$host_id." AND service_id IS null");
									sqlrequest($database_thruk,"UPDATE ".$line['source']."_log SET state = 0 WHERE  time BETWEEN ".strtotime($line['DateDebut'])." AND ".strtotime($line['DateFin'])." AND state_type ='HARD' AND host_id =".$host_id." AND service_id IS null");
								}
							}
							
							// if delete downtime	
							elseif($line['Action'] == "delete" && $line['type'] == "maintenance") {
								$host_id = mysqli_result(sqlrequest($database_thruk, "SELECT host_id FROM ".$line['source']."_host WHERE host_name = '".$line['host']."'"),0,"host_id");
								$service_id = mysqli_result(sqlrequest($database_thruk, "SELECT service_id FROM ".$line['source']."_service WHERE service_description = '".$line['service']."' AND host_id = '".$host_id."'"),0,"service_id");

								$DateDebutD = strtotime($line['DateDebut']);
								$DateFinD = strtotime($line['DateFin']) + 60;
								
								// delete outage service
								if($line['service'] != "Hoststatus") {
									sqlrequest($database_thruk,"delete from ".$line['source']."_log where host_id = ".$host_id." and service_id = ".$service_id." and type like '%DOWNTIME%' and time between ".$DateDebutD." AND ".$DateFinD.";");
								}
								else {
									sqlrequest($database_thruk,"delete from ".$line['source']."_log where host_id = ".$host_id." and service_id is null and type like '%DOWNTIME%' and time between ".$DateDebutD." AND ".$DateFinD.";");
								}
							}
							$old_remediation_group = $remediation_group;
						}
						
						if(isset($execute[0])){
							foreach($execute as $value){
								exec("/srv/eyesofreport/etl/scripts/massive_inject_HOST_downtime-or-outage.sh root root66 ".$value[1]." ".$value[2]." ".$value[0], $output);
								$file='/tmp/Inject'.str_replace(' ', '_', $group_name[0]["description"]).'.log';
								file_put_contents($file, "/srv/eyesofreport/etl/scripts/massive_inject_HOST_downtime-or-outage.sh root root66 ".$value[1]." ".$value[2]." ".$value[0]."\n");
								foreach($output as $output_val) {
									file_put_contents($file, $output_val."\n", FILE_APPEND);
								}
							}
						}
						
						// Update remediations state
						sqlrequest($database_eorweb,"UPDATE remediation SET state='executed', date_validation='".date("Y-m-d G:i")."' WHERE id='$remediation_selected[$i]'");
						
						$total_execute .= $name.", ";
						// If the selected remediation is not approved 
					}
					else{
						$fail_execution = true;
						$total_fail .= $name.", ";
					}
				}
				if ($success_execution) {
					$total_execute = substr($total_execute,0,-2);
					message(6," : ".getLabel("message.manage_remediation.execute")." ( $total_execute )",'ok');
				}
				if ($fail_execution) {
					$total_fail = substr($total_fail,0,-2);
					message(0," : ".getLabel("message.manage_remediation.fail_execution")." ( $total_fail )",'critical');
				}
			}
			break;
	}
	
	

	if($action == null or $action == "remediation") {
		// SQL get rules
		$rules_sql = "SELECT *, DATE_FORMAT(date_demand, '%d-%m-%Y %Hh%i') AS date_demand, DATE_FORMAT(date_validation, '%d-%m-%Y %Hh%i') AS date_validation FROM remediation ORDER BY date_demand DESC, name";
	?>
		
	<form action="./index.php" method="POST">
		<div class="dataTable_wrapper">
			<table class="table table-striped datatable-eorweb table-condensed">
				<thead>
					<tr>
						<th class="text-center"> <?php echo getLabel("label.admin_group.select"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.name"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.user"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.date_demand"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.date_validation"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.status"); ?> </th>
					</tr>
				</thead>
				<tbody>
				<?php
				// Get remediation_pack
				$methods = sqlrequest($database_eorweb,$rules_sql);
				if($methods) {
					while ($line = mysqli_fetch_array($methods)) {
						if ( ($line["user_id"] == $_COOKIE["user_id"] && !$validator) || $validator ) {
						?>
							<tr>
								<td class="text-center"><label><input type="checkbox" class="checkbox" name="remediation_selected[]" value="<?php echo $line["id"]; ?>"></label></td>
								<td><a href="remediation.php?id=<?php echo $line["id"]; ?>"><?php echo $line["name"]; ?></a></td>
								<td><?php echo mysqli_result(sqlrequest($database_eorweb,"SELECT user_name FROM users WHERE user_id='".$line["user_id"]."'"),0,"user_name"); ?></td>
								<td><?php echo $line["date_demand"]; ?></td>
								<td><?php echo $line["date_validation"]; ?></td>
								<td><?php echo getLabel("label.manage_remediation.state_".$line["state"]); ?></td>
							</tr>
						<?php
						}
					}
				}
				?>
				</tbody>
			</table>

			<!-- group of buttons -->
			<div class="form-group">
				<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
					<div class="btn-group mr-2 form-group" role="group" aria-label="First group">
						<a href="./remediation.php" class="btn btn-success" role="button"><?php echo getLabel("action.add");?></a>
						<button class="btn btn-danger" type="submit" name="actions" value="del_method"><?php echo getLabel("action.delete");?></button>
						<?php if (!$validator) { ?>
						<!-- if user is limited -->
						<button class="btn btn-success" type="submit" name="actions" value="demand"><?php echo getLabel("action.submit");?></button>
					</div>
					<div class="btn-group mr-2" role="group" aria-label="Second group">
						<button class="btn btn-primary" type="submit" name="actions" value="execution"><?php echo getLabel("action.apply"); ?></button>
					</div>
						<?php } else { ?>
						<!-- if the user have the remediation rights -->
						<button class="btn btn-success" type="submit" name="actions" value="validation"><?php echo getLabel("action.validate");?></button>
						<button class="btn btn-danger" type="submit" name="actions" value="refus"><?php echo getLabel("action.refuse");?></button>
					</div>
					<div class="btn-group mr-2" role="group" aria-label="Second group">
						<button class="btn btn-primary" type="submit" name="actions" value="execution"><?php echo getLabel("action.apply"); ?></button>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</form>




	<?php
	/*
	*************** REMEDIATION_ACTION 
	*/
	} elseif($action == "remediation_action") {
		$rules_sql = " SELECT remediation_action.*, remediation.name, remediation_group.description, remediation.state, remediation.user_id, DATE_FORMAT(DateDebut, '%d-%m-%Y %Hh%i') AS DateDebut, DATE_FORMAT(DateFin, '%d-%m-%Y %Hh%i') AS DateFin FROM remediation_action LEFT JOIN remediation ON remediation.id=remediation_action.remediationID LEFT JOIN remediation_group ON remediation_group.id=remediation_action.id_group GROUP BY remediation_group.description ORDER BY remediation.id DESC";
	?>
	
	<form action="./index.php?action=remediation_action" method="POST">
		<div class="dataTable_wrapper">
			<table class="table table-striped datatable-eorweb table-condensed">
				<thead>
					<tr>
						<th class="text-center"> <?php echo getLabel("label.admin_group.select"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.desc"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.remediation_name"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.status"); ?> </th>
					</tr>
				</thead>
				<tbody>
				<?php
				// Get remediation_pack
				$methods=sqlrequest($database_eorweb,$rules_sql);
				
				if($methods) {
					while ($line = mysqli_fetch_array($methods)) {
						if ( (($line["remediationID"] == 0 || $line["user_id"] == $_COOKIE["user_id"]) && !$validator) || $validator ) {
						?>
							<tr>
								<td class="text-center"><label><input type="checkbox" class="checkbox" name="remediation_action_selected[]" value="<?php echo $line["id_group"]; ?>"></label></td>
								<td><a href="remediation_action.php?id=<?php echo $line["id"]; ?>"><?php echo $line["description"]; ?></a></td>
								<td><a href="remediation.php?id=<?php echo $line["remediationID"]; ?>"><?php echo $line["name"]; ?></a></td>
								<td><?php if ($line["state"]) { echo getLabel("label.manage_remediation.state_".$line["state"]); } ?></td>
							</tr>
						<?php
						}
					}
				}
				?>
				</tbody>
			</table>
			<div class="btn-group mr-2 form-group" role="group" aria-label="First group">
				<a href="./remediation_action.php" class="btn btn-success" role="button"><?php echo getLabel("action.add");?></a>
				<button class="btn btn-danger" type="submit" name="actions" value="del_method_remediation"><?php echo getLabel("action.delete");?></button>
			</div>
		</div>	
	</form>

	<?php } ?>
	
</div>

<?php include("../../footer.php"); ?>
