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

// Init action
$action=null;
if(isset($_GET["action"])) {
	$action=$_GET["action"];
}
?> 

<div id="page-wrapper">
	<?php
	
	if(isset($_POST["actions"])){
		$actions=$_POST["actions"];
	}else{
		$actions="";
	}
	
	$remediation_action_selected=retrieve_form_data("remediation_action_selected",null);
	$remediation_selected=retrieve_form_data("remediation_selected",null);
	
	switch($actions){
		case "del_method":
			if (isset($remediation_selected[0])){
				for ($i = 0; $i < count($remediation_selected); $i++){
					// Get remediation_action name
					$user_res=sqlrequest("$database_eorweb","select name from remediation where id='$remediation_action_selected[$i]'");
					$remediation=mysqli_result($user_res,0,"user_name");
					// Delete 
					sqlrequest("$database_eorweb","delete from remediation where id='$remediation_selected[$i]'");
					sqlrequest("$database_eorweb","delete from remediation_action where remediationID='$remediation_selected[$i]'");
					
					// Logging action
					logging("manage_remediation","DELETE : $remediation_selected[$i]");
					message(8," : Remediation $remediation removed",'ok');
				}
			}
			break;
		case "del_method_remediation":
			if(isset($remediation_action_selected[0])){
				for ($i = 0; $i < count($remediation_action_selected); $i++){
					// Get remediation_action name
					$user_res=sqlrequest("$database_eorweb","select description from remediation_action where id='$remediation_action_selected[$i]'");
					$remediation_action=mysqli_result($user_res,0,"user_name");
					// Delete user in eorweb
					sqlrequest("$database_eorweb","delete from remediation_action where id='$remediation_action_selected[$i]'");
					
					// Logging action
					logging("manage_remediation","DELETE : $remediation_action_selected[$i]");
					message(8," : Remediation pack $remediation_action removed",'ok');
				}
			}
			break;
		case "validation":
			if(isset($remediation_selected[0])){
				for ($i = 0; $i < count($remediation_selected); $i++){
					// Update remediations state
					sqlrequest("$database_eorweb","UPDATE remediation SET state='approved', date_validation='".date("Y-m-d G:i")."' where id='$remediation_selected[$i]'");
					
					message(8," : Remediation pack selected have been updated",'ok');
				}
			}
			break;
		case "refus":
			if(isset($remediation_selected[0])){
				for ($i = 0; $i < count($remediation_selected); $i++){
					// Update remediations state
					sqlrequest("$database_eorweb","UPDATE remediation SET state='refused', date_validation='".date("Y-m-d G:i")."' where id='$remediation_selected[$i]'");
					
					message(8," : Remediation pack selected have been updated",'ok');
				}
			}
			break;
		case "demand":
			if(isset($remediation_selected[0])){
				for ($i = 0; $i < count($remediation_selected); $i++){
					// Update remediations state
					sqlrequest("$database_eorweb","UPDATE remediation SET state='en attente', date_validation='".date("Y-m-d G:i")."' where id='$remediation_selected[$i]'");
					
					message(8," : Remediation pack selected have been updated",'ok');
				}
			}
			break;
		case "execution":
			if(isset($remediation_selected[0])){
				/*// Create CSV files with infos in DB
				$array = array('Valid','"Date debut"','"Heure debut"','"Date fin"','"Heure fin"','Type','Host','Service');
				$array = str_replace('"', '', $array);

				// Paramétrage de l'écriture du futur fichier CSV
				$chemin = '/srv/eyesofreport/etl/injection/InjectRemediation'.$remediation_selected[0].'.csv';
				$delimiteur = ';'; 

				// Création du fichier csv
				$fichier_csv = fopen($chemin, 'w+');
				
				for ($i = 0; $i < count($remediation_selected); $i++){
					$RemediationExec=sqlrequest("$database_eorweb","select * from remediation_action where remediationID='$remediation_selected[$i]'");
					$result = $RemediationExec->fetch_array(MYSQLI_ASSOC);
					
					
					
					$dateDebut = split(" ", $result["DateDebut"]);
					$dateFin = split(" ", $result["DateFin"]);
					
					$lignes[] = array('O', $dateDebut[0], $dateDebut[1], $dateFin[0], $dateFin[1], $result["type"], $result["host"], $result["service"]);
					
					// Update remediations state
					//sqlrequest("$database_eorweb","UPDATE remediation SET state='approved', date_validation='".date("Y-m-d G:i")."' where id='$remediation_selected[$i]'");
					
					message(8," : Remediation pack have been activated",'ok');
				}
				
				fputs($fichier_csv, implode($array, ';')."\n");
				foreach($lignes as $ligne){
					fputcsv($fichier_csv, $ligne, $delimiteur);
				}

				fclose($fichier_csv);
				
				exec("");*/
			}
			break;
	}
	
	

	if($action==null or $action =="remediation") {
		// SQL get rules
		$rules_sql="SELECT * FROM remediation";
	?>

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_remediation.list_remediations"); ?></h1>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<h2><?php echo getLabel("label.manage_remediation.remediations"); ?></h2>
		</div>
	</div>
	
	<form action="./index.php" method="POST">
		<div class="dataTable_wrapper">
			<div class="table-responsive">
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
					$methods=sqlrequest($database_eorweb,$rules_sql);

					while ($line = mysqli_fetch_array($methods)) {
					?>
						<tr>
							<td class="text-center"><label><input type="checkbox" class="checkbox" name="remediation_selected[]" value="<?php echo $line["id"]; ?>"></label></td>
							<td><a href="remediation.php?id=<?php echo $line["id"]; ?>"><?php echo $line["name"]; ?></a></td>
							<td><?php echo mysqli_result(sqlrequest("eorweb","SELECT user_name from users where user_id='".$line["user_id"]."'"),0,"user_name"); ?></td>
							<td><?php echo $line["date_demand"]; ?></td>
							<td><?php echo $line["date_validation"]; ?></td>
							<td><?php echo $line["state"]; ?></td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
			<div class="form-group">
				<a href="./remediation.php" class="btn btn-success" role="button"><?php echo getLabel("action.add");?></a>
				<button class="btn btn-danger" type="submit" name="actions" value="del_method"><?php echo getLabel("action.clear");?></button>
				<!-- Si les droits de remediation de l'utilisateur son limité -->
				<button class="btn btn-default" type="submit" name="actions" value="demand">Envoyé demande</button>
				<!-- Si l'utilisateur a tous les droits de remediation -->
				<button class="btn btn-default" type="submit" name="actions" value="validation">Valider</button>
				<button class="btn btn-danger" type="submit" name="actions" value="refus">Refuser</button>
			</div>
		</div>
	</form>
	
	<?php
	/*
	*************** REMEDIATION_ACTION 
	*/
	} elseif($action=="remediation_action") {
		$rules_sql="SELECT * FROM remediation_action";
	?>	
	
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_remediation.list_remediations_action"); ?></h1>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<h2><?php echo getLabel("label.manage_remediation.remediations_action"); ?></h2>
		</div>
	</div>
	
	<form action="./index.php?action=remediation_action" method="POST">
		<div class="dataTable_wrapper">
			<div class="table-responsive">
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
					?>
					</tbody>
				</table>
			</div>
			<div class="form-group">
				<a href="./remediation_action.php" class="btn btn-success" role="button"><?php echo getLabel("action.add");?></a>
				<button class="btn btn-danger" type="submit" name="actions" value="del_method_remediation"><?php echo getLabel("action.clear");?></button>
			</div>
		</div>	
	</form>

	<?php
	/*
	*************** Apply remediation 
	*/
	} elseif($action=="apply_pack") {
		$rules_sql="SELECT * FROM remediation WHERE state='approved'";
	?>	
	
	<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header"><?php echo getLabel("label.manage_remediation.list_accepted_remediations"); ?></h1>
	</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<h2><?php echo getLabel("label.manage_remediation.remediations"); ?></h2>
		</div>
	</div>
	
	<form action="./index.php?action=apply_pack" method="POST">
		<div class="dataTable_wrapper">
			<div class="table-responsive">
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

					while ($line = mysqli_fetch_array($methods)) {
					?>
						<tr>
							<td class="text-center"><label><input type="checkbox" class="checkbox" name="remediation_selected[]" value="<?php echo $line["id"]; ?>"></label></td>
							<td><a href="manage_remediation.php?id=<?php echo $line["id"]; ?>"><?php echo $line["name"]; ?></a></td>
							<td><?php echo mysqli_result(sqlrequest("eorweb","SELECT user_name from users where user_id='".$line["user_id"]."'"),0,"user_name"); ?></td>
							<td><?php echo $line["date_demand"]; ?></td>
							<td><?php echo $line["date_validation"]; ?></td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
			<div class="form-group">
				<button class="btn btn-success" type="submit" name="actions" value="execution">Executer</button>
				<button class="btn btn-default" type="submit" name="actions" value="annulation">Annuler</button
			</div>
		</div>
	</form>
	<?php
	}
	?>
	
</div>

<?php include("../../footer.php"); ?>