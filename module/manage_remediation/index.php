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
}
?> 

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

	<?php
	if($action == null or $action == "remediation") {
	?>
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo getLabel("label.manage_remediation.list_remediations"); ?></h1>
			</div>
		</div>
	<?php
	} elseif($action == "remediation_action") {
	?>
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo getLabel("label.manage_remediation.list_remediations_action"); ?></h1>
			</div>
		</div>
	<?php 
	} elseif($action == "apply_pack") {
	?>	
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo getLabel("label.manage_remediation.list_accepted_remediations"); ?></h1>
			</div>
		</div>
	<?php 
	}

	switch($actions){
		case "del_method":
			if (isset($remediation_selected[0])) {
				$total_remediation = "";
				for ($i = 0; $i < sizeof($remediation_selected); $i++) {
					// Get remediation_action name
					$user_res = sqlrequest($database_eorweb,"SELECT name FROM remediation WHERE id = '$remediation_selected[$i]'");
					$remediation = mysqli_result($user_res,0,"name");
					$total_remediation .= $remediation.", ";

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
					$user_res = sqlrequest($database_eorweb,"SELECT description FROM remediation_action WHERE id='$remediation_action_selected[$i]'");
					$remediation_action = mysqli_result($user_res,0,"description");
					
					// Delete user in eorweb
					sqlrequest($database_eorweb,"DELETE FROM remediation_action WHERE id='$remediation_action_selected[$i]'");
					
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
				for ($i = 0; $i < sizeof($remediation_selected); $i++) {
					// Update remediations state
					sqlrequest($database_eorweb,"UPDATE remediation SET state='approved', date_validation='".date("Y-m-d G:i")."' WHERE id='$remediation_selected[$i]'");
				}
				message(6," : ".getLabel("message.manage_remediation.request_update"),'ok');
			}
			break;
		case "refus":
			if(isset($remediation_selected[0])) {
				for ($i = 0; $i < sizeof($remediation_selected); $i++) {
					// Update remediations state
					sqlrequest($database_eorweb,"UPDATE remediation SET state='refused', date_validation='".date("Y-m-d G:i")."' WHERE id='$remediation_selected[$i]'");
				}
				message(6," : ".getLabel("message.manage_remediation.request_update"),'ok');
			}
			break;
		case "demand":
			if(isset($remediation_selected[0])) {
				for ($i = 0; $i < sizeof($remediation_selected); $i++) {
					// Update remediations state
					sqlrequest($database_eorweb,"UPDATE remediation SET state='en attente', date_validation='".date("Y-m-d G:i")."' WHERE id='$remediation_selected[$i]'");
				}
				message(6," : ".getLabel("message.manage_remediation.request_update"),'ok');
			}
			break;
		case "execution":
			if(isset($remediation_selected[0])) {
				// Create CSV files with infos in DB
				$array = array('Valid','"Date debut"','"Heure debut"','"Date fin"','"Heure fin"','Type','Host','Service');
				$array = str_replace('"', '', $array);
				
				$total_execute = "";
				for ($i = 0; $i < sizeof($remediation_selected); $i++) {
					$remediation_request_name = sqlrequest($database_eorweb, "SELECT name FROM remediation WHERE id = '$remediation_selected[$i]'");
					$result_request_remediation_name = mysqli_result($remediation_request_name,0,"name");

					$RemediationExec = sqlrequest($database_eorweb,"SELECT * FROM remediation_action WHERE remediationID='$remediation_selected[$i]'");
					//$result = $RemediationExec->fetch_array(MYSQLI_ASSOC);
					
					while($line = mysqli_fetch_array($RemediationExec)) {
						if($line['Action'] == "add") {
							// Paramétrage de l'écriture du futur fichier CSV
							$chemin = '/srv/eyesofreport/etl/injection/Inject'.$line['type'].$line['id'].'.csv';
							$delimiteur = ';'; 

							// Création du fichier csv
							$fichier_csv = fopen($chemin, 'w+');
							
							$dateDebut = split(" ", date('d/m/Y h:i:s', strtotime($line["DateDebut"])));
							$dateFin = split(" ", date('d/m/Y h:i:s', strtotime($line["DateFin"])));
							
							$lignes[] = array('O', $dateDebut[0], $dateDebut[1], $dateFin[0], $dateFin[1], $line["type"], $line["host"], $line["service"]);
							
							fputs($fichier_csv, implode($array, ';')."\n");
							foreach($lignes as $ligne){
								fputcsv($fichier_csv, $ligne, $delimiteur);
							}

							fclose($fichier_csv);
							
							if($line["type"] == "maintenance"){
								$type = "Downtime";
							}else{
								$type = "Outage";
							}
							
							exec("/srv/eyesofreport/etl/scripts/massive_inject_HOST_downtime-or-outage.sh root root66 ".$line['source']." ".$type." ".'Inject'.$line['type'].$line['id'].'.csv', $output);
							
							var_dump($output);
							
						// si delete incident, faire requete
						} elseif($line['Action'] == "delete" && $line['type'] == "incident") {
							// suppression d'incident incomplète
							if($line['service'] != "" || $line['service'] != null){
								sqlrequest("$database_thruk","UPDATE ".$line['source']."_log SET message=7930, state=0 WHERE time between ".strtotime($line['DateDebut'])." AND ".strtotime($line['DateFin'])." AND state_type='HARD' AND host_id='".$line['host']."' AND service_id='".$line['service']."'");
								echo "UPDATE ".$line['source']."_log SET message=7930, state=0 WHERE time between ".strtotime($line['DateDebut'])." AND ".strtotime($line['DateFin'])." AND state_type='HARD' AND host_id='".$line['host']."' AND service_id='".$line['service']."'";
							} else {
								sqlrequest("$database_thruk","UPDATE ".$line['source']."_log SET message=7930, state=0 WHERE time between ".strtotime($line['DateDebut'])." AND ".strtotime($line['DateFin'])." AND state_type='HARD' AND host_id='".$line['host']."'");
							}
							
						}
					}
					
					// Update remediations state
					sqlrequest($database_eorweb,"UPDATE remediation SET state='executed', date_validation='".date("Y-m-d G:i")."' WHERE id='$remediation_selected[$i]'");
					
					$total_execute .= $result_request_remediation_name.", ";
				}
				$total_execute = substr($total_execute,0,-2);
				message(6," : ".getLabel("message.manage_remediation.request_execute")." ( $total_execute )",'ok');
			}
			break;
	}
	
	

	if($action == null or $action == "remediation") {
		// SQL get rules
		$rules_sql = "SELECT * FROM remediation ORDER BY date_demand, name";
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
					?>
						<tr>
							<td class="text-center"><label><input type="checkbox" class="checkbox" name="remediation_selected[]" value="<?php echo $line["id"]; ?>"></label></td>
							<td><a href="remediation.php?id=<?php echo $line["id"]; ?>"><?php echo $line["name"]; ?></a></td>
							<td><?php echo mysqli_result(sqlrequest("eorweb","SELECT user_name FROM users WHERE user_id='".$line["user_id"]."'"),0,"user_name"); ?></td>
							<td><?php echo $line["date_demand"]; ?></td>
							<td><?php echo $line["date_validation"]; ?></td>
							<td><?php echo $line["state"]; ?></td>
						</tr>
					<?php
					}
				}
				?>
				</tbody>
			</table>
			<div class="form-group">
				<a href="./remediation.php" class="btn btn-success" role="button"><?php echo getLabel("action.add");?></a>
				<button class="btn btn-danger" type="submit" name="actions" value="del_method"><?php echo getLabel("action.delete");?></button>
				
				<!-- Si les droits de remediation de l'utilisateur sont limités -->
				<?php 
				$req = "SELECT validator FROM groups WHERE group_id = ?";
				$validator_bool = sqlrequest($database_eorweb,$req,false,array("i",(int)$_COOKIE['group_id']));
				$result = mysqli_result($validator_bool,0,"validator");
				if (!$result) {
				?>
					<button class="btn btn-default" type="submit" name="actions" value="demand"><?php echo getLabel("action.submit");?></button>
				<?php
				} else { ?>
					<!-- Si l'utilisateur a tous les droits de remediation -->
					<button class="btn btn-default" type="submit" name="actions" value="validation"><?php echo getLabel("action.validate");?></button>
					<button class="btn btn-danger" type="submit" name="actions" value="refus"><?php echo getLabel("action.refuse");?></button>
				<?php } ?>
			</div>
		</div>
	</form>
	
	<?php
	/*
	*************** REMEDIATION_ACTION 
	*/
	} elseif($action == "remediation_action") {
		$rules_sql = "SELECT * FROM remediation_action";
	?>
	
	<form action="./index.php?action=remediation_action" method="POST">
		<div class="dataTable_wrapper">
			<table class="table table-striped datatable-eorweb table-condensed">
				<thead>
					<tr>
						<th class="text-center"> <?php echo getLabel("label.admin_group.select"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.desc"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.type"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.date_beginning"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.date_ending"); ?> </th>
					</tr>
				</thead>
				<tbody>
				<?php
				// Get remediation_pack
				$methods=sqlrequest($database_eorweb,$rules_sql);
				
				if($methods) {
				while ($line = mysqli_fetch_array($methods)) {
					?>
						<tr>
							<td class="text-center"><label><input type="checkbox" class="checkbox" name="remediation_action_selected[]" value="<?php echo $line["id"]; ?>"></label></td>
							<td><a href="remediation_action.php?id=<?php echo $line["id"]; ?>"><?php echo $line["description"]; ?></a></td>
							<td><?php echo $line["type"]; ?></td>
							<td><?php echo $line["DateDebut"]; ?></td>
							<td><?php echo $line["DateFin"]; ?></td>
						</tr>
					<?php
					}
				}
				?>
				</tbody>
			</table>
			<div class="form-group">
				<a href="./remediation_action.php" class="btn btn-success" role="button"><?php echo getLabel("action.add");?></a>
				<button class="btn btn-danger" type="submit" name="actions" value="del_method_remediation"><?php echo getLabel("action.delete");?></button>
			</div>
		</div>	
	</form>

	<?php
	/*
	*************** Apply remediation 
	*/
	} elseif($action == "apply_pack") {
		$rules_sql = "SELECT * FROM remediation WHERE state = 'approved' OR state = 'executed'";
	?>
	
	<form action="./index.php?action=apply_pack" method="POST">
		<div class="dataTable_wrapper">
			<table class="table table-striped datatable-eorweb table-condensed">
				<thead>
					<tr>
						<th class="text-center"> <?php echo getLabel("label.admin_group.select"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.name"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.user"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.date_demand"); ?> </th>
						<th> <?php echo getLabel("label.manage_remediation.date_validation"); ?> </th>
					</tr>
				</thead>
				<tbody>
				<?php
				// Get remediation_pack
				$methods=sqlrequest($database_eorweb,$rules_sql);
				if($methods) {
					while ($line = mysqli_fetch_array($methods)) {
					?>
						<tr>
							<td class="text-center"><label><input type="checkbox" class="checkbox" name="remediation_selected[]" value="<?php echo $line["id"]; ?>"></label></td>
							<td><a href="remediation.php?id=<?php echo $line["id"]; ?>"><?php echo $line["name"]; ?></a></td>
							<td><?php echo mysqli_result(sqlrequest("eorweb","SELECT user_name FROM users WHERE user_id='".$line["user_id"]."'"),0,"user_name"); ?></td>
							<td><?php echo $line["date_demand"]; ?></td>
							<td><?php echo $line["date_validation"]; ?></td>
						</tr>
					<?php
					}
				}
				?>
				</tbody>
			</table>
			<div class="form-group">
				<button class="btn btn-success" type="submit" name="actions" value="execution"><?php echo getLabel("action.apply"); ?></button>
			</div>
		</div>
	</form>
	<?php
	}
	?>
	
</div>

<?php include("../../footer.php"); ?>