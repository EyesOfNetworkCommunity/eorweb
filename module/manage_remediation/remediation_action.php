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

global $database_host;
global $database_username;
global $database_password;
global $database_eorweb;

function generatePIN($digits = 4){
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while($i < $digits){
        //generate a random number between 0 and 9.
        $pin .= mt_rand(0, 9);
        $i++;
    }
    return $pin;
}
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_remediation.remediation_action_new"); ?></h1>
		</div>
	</div>

	<?php
	// get infos for updates
	$remediation_action_id = retrieve_form_data("id",null);
	$invalid=false;
	
	// define if the user is validator or not 
	$req = "SELECT validator FROM groups WHERE group_id = ?";
	$validator_bool = sqlrequest($database_eorweb,$req,false,array("i",(int)$_COOKIE['group_id']));
	$validator = mysqli_result($validator_bool,0,"validator");
	
	if(isset($_GET["id"]) && $_GET["id"] != null){
		$sql = "SELECT remediation_action.*, remediation.state, remediation_group.description as group_name, remediation_group.id as group_id FROM remediation_action LEFT JOIN remediation ON remediation.id = remediation_action.remediationID LEFT JOIN remediation_group ON remediation_action.id_group = remediation_group.id WHERE remediation_action.id=?";

		$user_infos=sqlrequest($database_eorweb, $sql, false, array("i",(int)$remediation_action_id));
		
		// Retrieve Information from database
		$remediation_group=mysqli_result($user_infos,0,"group_name");
		$old_remediation_group=mysqli_result($user_infos,0,"group_name");
		$remediation_id =mysqli_result($user_infos,0,"remediationID");
		$remediation_source=mysqli_result($user_infos,0,"source");
		$remediation_host=mysqli_result($user_infos,0,"host");
		$remediation_state=mysqli_result($user_infos,0,"state");
		
		// Retrieve services on remediation action
		$sql = "SELECT service FROM remediation_action WHERE id_group=?";
		$services=sqlrequest($database_eorweb, $sql, false, array("i",(int)mysqli_result($user_infos,0,"group_id")));
		
		while ($line = mysqli_fetch_array($services)){
			$remediation_service[] = $line[0];
		}
		
		$remediation_type=mysqli_result($user_infos,0,"type");
		$remediation_action=mysqli_result($user_infos,0,"Action");
		$remediation_dateDebut=mysqli_result($user_infos,0,"DateDebut");
		$remediation_dateFin=mysqli_result($user_infos,0,"DateFin");
	} else {
		$remediation_group=retrieve_form_data("group_name",null);
		$old_remediation_group=retrieve_form_data("old_group_name",null);
		$remediation_source=retrieve_form_data("source",null);
		$remediation_host=retrieve_form_data("host",null);
		$remediation_service=retrieve_form_data("service_id",null);
		if ($remediation_service == null) {
			$remediation_service=retrieve_form_data("service",null);
		}
		$remediation_type=retrieve_form_data("type",null);
		$remediation_action=retrieve_form_data("action",null);
		$remediation_dateDebut=retrieve_form_data("start",null);
		$remediation_dateFin=retrieve_form_data("end",null);
		$remediation_create =retrieve_form_data("request",null);
		$remediation_id=retrieve_form_data("remediationID",0);
	}
	
	if(isset($_GET["host"]) && $_GET["host"] != null){
		$remediation_group="delete_".$remediation_host."_".$remediation_service;
		$remediation_type="incident";
		$remediation_action="delete";
		$remediation_create = 1;
	}
	
	$validate_creation_action=false;
	
	$user_id = $_COOKIE['user_id'];
	$date_demand = date("Y-m-d G:i");

	$sql_group_name = sqlrequest($database_eorweb, "SELECT description FROM remediation_group");
	$array_group_name = [];
	while ($line = mysqli_fetch_array($sql_group_name)){
		array_push($array_group_name, $line["description"]);
	}
	

	if (isset($_POST["add"])) {
		if(!$remediation_group || $remediation_group==""){
			message(7," : ".getLabel("message.error.remediation_action_name"),'warning');
			$invalid=true;
		}
		elseif(in_array($remediation_group, $array_group_name)){
			message(7," : ".getLabel("message.error.remediation_request_name_exist"),'warning');
			$invalid=true;
		}
		elseif(!$remediation_type || $remediation_type==""){
			message(7," : ".getLabel("message.error.remediation_action_type"),'warning');
			$invalid=true;
		}
		elseif(!$remediation_source || $remediation_source=="" || $remediation_source=="none"){
			message(7," : ".getLabel("message.error.remediation_action_source"),'warning');
			$invalid=true;
		}
		elseif(!$remediation_host || $remediation_host==""){
			message(7," : ".getLabel("message.error.remediation_action_host"),'warning');
			$invalid=true;
		} else {
			$connexion = mysqli_connect($database_host, $database_username, $database_password, $database_eorweb);
			$query = "INSERT INTO remediation_group (description) VALUES('".$remediation_group."')";
			if(isset($remediation_create) && $remediation_create == 1){
				$rand = generatePIN(4);
				$sql_add = "INSERT INTO remediation (name,user_id,date_demand) VALUES('".$remediation_group."-request-".$rand."','".$user_id."','".$date_demand."')";
				$remediation_id = sqlrequest($database_eorweb,$sql_add,true);
			}
			//$id_group = mysqli_insert_id($connexion);
			if (isset($remediation_service)) {
				for ($i=0; $i < sizeof($remediation_service) ; $i++) {
					if ($remediation_source != "none" && $remediation_source != "") {
						$result = sqlrequest($database_thruk, "SELECT host_name,host_id FROM ".$remediation_source."_host WHERE host_name=?",false,array("s",(string)$remediation_host));
						$array_host=mysqli_fetch_row($result);
						
						if($array_host && $remediation_service[$i]!="Hoststatus") {
							$result = sqlrequest($database_thruk, "SELECT service_description FROM ".$remediation_source."_service WHERE host_id=? and service_description=?",false,array("is",(int)$array_host[1],(string)$remediation_service[$i]));
							$array_service=mysqli_fetch_row($result);
						} elseif($remediation_service[$i]=="Hoststatus") {
							$array_service[0]="Hoststatus";
						}
					}
					if($remediation_host != $array_host[0]){
						message(7," : ".getLabel("message.error.remediation_action_host_name"),'warning');
						$invalid=true;
					}
					elseif(!$remediation_service[$i] || $remediation_service[$i]==""){
						message(7," : ".getLabel("message.error.remediation_action_service"),'warning');
						$invalid=true;
					}
					elseif($remediation_service[$i] != $array_service[0]){
						message(7," : ".getLabel("message.error.remediation_action_service_description"),'warning');
						$invalid=true;
					}
					$desciptionExist = sqlrequest($database_eorweb,"SELECT description FROM remediation_action");
					if(!$invalid){
						$id_group = sqlrequest($database_eorweb,$query,true);
						if(!isset($remediation_id)){
							// insert values for add
							$sql_add = "INSERT INTO remediation_action (description,type,DateDebut,DateFin,Action,host,service,source,id_group) VALUES('".$remediation_group." - ".$remediation_service[$i]."','".$remediation_type."','".$remediation_dateDebut."','".$remediation_dateFin."', '".$remediation_action."','".$remediation_host."','".$remediation_service[$i]."','".$remediation_source."','".$id_group."')";
						}else{
							$sql_add = "INSERT INTO remediation_action (remediationID,description,type,DateDebut,DateFin,Action,host,service,source,id_group) VALUES('".$remediation_id."','".$remediation_group." - ".$remediation_service[$i]."','".$remediation_type."','".$remediation_dateDebut."','".$remediation_dateFin."', '".$remediation_action."','".$remediation_host."','".$remediation_service[$i]."','".$remediation_source."','".$id_group."')";
						}
						$remediation_action_id = sqlrequest($database_eorweb,$sql_add,true);
						$validate_creation_action=true;
					}
				}
				if($validate_creation_action){
					message(6," : ".getLabel("message.manage_remediation.action_create"),'ok');
				}
			}
		}
	}


	if(isset($_POST["update"])) {
		if(!$remediation_group || $remediation_group==""){
			message(7," : ".getLabel("message.error.remediation_action_name"),'warning');
		}
		elseif(in_array($remediation_group, $array_group_name) && $remediation_group != $old_remediation_group){
			message(7," : ".getLabel("message.error.remediation_request_name_exist"),'warning');
			$invalid=true;
		}
		elseif(!$remediation_type || $remediation_type==""){
			message(7," : ".getLabel("message.error.remediation_action_type"),'warning');
		}elseif(!$remediation_source || $remediation_source=="" || $remediation_source=="none"){
			message(7," : ".getLabel("message.error.remediation_action_source"),'warning');
			$invalid=true;
		}
		elseif(!$remediation_host || $remediation_host==""){
			message(7," : ".getLabel("message.error.remediation_action_host"),'warning');
			$invalid=true;
		}else{
			$id_group = sqlrequest($database_eorweb, "SELECT id FROM remediation_group WHERE description='".$old_remediation_group."'");
			$remediation_group_id = mysqli_result($id_group,0,"id");

			sqlrequest($database_eorweb, "UPDATE remediation_group SET description='".$remediation_group."' WHERE id ='".$remediation_group_id."'");
			
			if(isset($remediation_create) && $remediation_create == 1){
				$sql_add = "INSERT INTO remediation (name,user_id,date_demand) VALUES('".$remediation_group."-request','".$user_id."','".$date_demand."')";
				$remediation_id = sqlrequest($database_eorweb,$sql_add,true);
			}
			
			$old_remediation_services = sqlrequest($database_eorweb, "SELECT id FROM remediation_action WHERE description LIKE '%".$old_remediation_group." -%'");
			while ($line = mysqli_fetch_array($old_remediation_services)){
				sqlrequest($database_eorweb, "DELETE FROM remediation_action WHERE id=".$line[0]);
			}
			
			if (isset($remediation_service)) {
				for ($i=0; $i < sizeof($remediation_service) ; $i++) {
					if ($remediation_source != "none" && $remediation_source != "") {
						$result = sqlrequest($database_thruk, "SELECT host_name,host_id FROM ".$remediation_source."_host WHERE host_name=?",false,array("s",(string)$remediation_host));
						$array_host=mysqli_fetch_row($result);
						
						if($array_host && $remediation_service[$i]!="Hoststatus") {
							$result = sqlrequest($database_thruk, "SELECT service_description FROM ".$remediation_source."_service WHERE host_id=? and service_description=?",false,array("is",(int)$array_host[1],(string)$remediation_service[$i]));
							$array_service=mysqli_fetch_row($result);
						} elseif($remediation_service[$i]=="Hoststatus") {
							$array_service[0]="Hoststatus";
						}
					}
					if($remediation_host != $array_host[0]){
						message(7," : ".getLabel("message.error.remediation_action_host_name"),'warning');
						$invalid=true;
					}
					elseif(!$remediation_service[$i] || $remediation_service[$i]==""){
						message(7," : ".getLabel("message.error.remediation_action_service"),'warning');
						$invalid=true;
					}
					elseif($remediation_service[$i] != $array_service[0]){
						message(7," : ".getLabel("message.error.remediation_action_service_description"),'warning');
						$invalid=true;
					}
					$desciptionExist = sqlrequest($database_eorweb,"SELECT description FROM remediation_action");
					if(!$invalid){
						if(!isset($remediation_id)){
							// insert values for add
							$sql_add = "INSERT INTO remediation_action (description,type,DateDebut,DateFin,Action,host,service,source,id_group) VALUES('".$remediation_group." - ".$remediation_service[$i]."','".$remediation_type."','".$remediation_dateDebut."','".$remediation_dateFin."', '".$remediation_action."','".$remediation_host."','".$remediation_service[$i]."','".$remediation_source."','".$remediation_group_id."')";
						}else{
							$sql_add = "INSERT INTO remediation_action (remediationID,description,type,DateDebut,DateFin,Action,host,service,source,id_group) VALUES(".$remediation_id.",'".$remediation_group." - ".$remediation_service[$i]."','".$remediation_type."','".$remediation_dateDebut."','".$remediation_dateFin."', '".$remediation_action."','".$remediation_host."','".$remediation_service[$i]."','".$remediation_source."','".$remediation_group_id."')";
							sqlrequest($database_eorweb,"UPDATE remediation SET state='inactive' WHERE id='$remediation_id'");
						}
						$remediation_action_id = sqlrequest($database_eorweb,$sql_add,true);
						$remediation_creation_validate = true;
					}
				}
				if ($remediation_creation_validate){
					message(6," : ".getLabel("message.manage_remediation.action_update"),'ok');
				}
			}
		}
	}
	
	$disable="";
	if(isset($remediation_state) && ((!$validator && ($remediation_state == "executed" || $remediation_state == "waiting")) || ($remediation_state == "executed" && $validator) )){
		$disable="disabled";
	}
	
	?>
	<form id="form_user" action='./remediation_action.php' method='POST' name='form_user'>
		
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.group_name"); ?></label>
			<div class="col-md-9">
				<input class="form-control" <?php echo $disable; ?> type='text' name='group_name'  value='<?php echo $remediation_group?>' maxlength="50">
				<input class="form-control" type='hidden' name='old_group_name'  value='<?php if(isset($old_remediation_group)){echo $old_remediation_group;}?>'>
				<input class="form-control" type='hidden' name='remediationID'  value='<?php if(isset($remediation_id)){echo $remediation_id;}?>'>
			</div>
		</div>

		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.remediation_action.source"); ?></label>
			<div class="col-md-9">
				<select class="form-control"  <?php echo $disable; ?>  id='source' name='source' size=1>
					<?php
						$request="SELECT distinct thruk_idx,nick_name FROM bp_sources";
						$result=sqlrequest($database_vanillabp,$request);
						echo "<option value='none'>".getLabel("label.manage_remediation.remediation_action.source_select")."</option>";
						while ($line = mysqli_fetch_array($result)){
							if($line[0] != "NR"){
								if(isset($remediation_source) && $remediation_source == $line[0]){
									echo "<option value='$line[0]' SELECTED>$line[1]</option>";
								}else{
									echo "<option value='$line[0]'>$line[1]</option>";
								}
							}
						}
					?>
				</select>
			</div>
		</div>

		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.host"); ?></label>
			<div class="col-md-9">
				<input class="form-control"  <?php echo $disable; ?>  type='text' id='host' name='host' value='<?php echo $remediation_host?>'>
			</div>
		</div>

		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.service"); ?></label>
			<div class="col-md-9">
				<div class="form-group input-group">
					<input class="form-control"  <?php echo $disable; ?> type='text' id='service' name='service' <?php if (isset($_GET["host"]) && $_GET["host"] != null) {echo "onclick='rename(\"$remediation_host\")'";}?> >
					<span class="input-group-btn">
						<input class="btn btn-danger"  <?php echo $disable; ?>  id="service_button_del" type="button" value="<?php echo getLabel("action.delete");?>">
					</span>
				</div>
				<select class="form-control"  <?php echo $disable; ?>  id="service_id" name="service_id[]" multiple>
					<?php 
						if(isset($_GET["service"]) && $_GET["service"] != null){
							echo "<option selected='selected' value='".$remediation_service."'>".$remediation_service."</option> ";
						}else if($remediation_service[0] != ""){
							for($i=0; $i<sizeof($remediation_service);$i++){
								echo "<option selected='selected' value='".$remediation_service[$i]."'>".$remediation_service[$i]."</option> ";
							}
						}
					?>
				</select>
			</div>
		</div>

		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.type"); ?></label>
			<div class="col-md-9">
				<select class="form-control"  <?php echo $disable; ?>  name='type' size=1>
					<?php
						if ($remediation_type == "incident"){
							echo "<option value='maintenance'>".getLabel("label.manage_remediation.remediation_action.downtime")."</option>";
							echo "<option value='incident' SELECTED>".getLabel("label.manage_remediation.remediation_action.incident")."</option>";
						} else {
							echo "<option value='maintenance' SELECTED>".getLabel("label.manage_remediation.remediation_action.downtime")."</option>";
							echo "<option value='incident'>".getLabel("label.manage_remediation.remediation_action.incident")."</option>";
						}
					?>
				</select>
			</div>
		</div>

		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.action"); ?></label>
			<div class="col-md-9">
				<select class="form-control"  <?php echo $disable; ?>  name='action' size=1>
					<?php
						if ($remediation_action == "delete"){
							echo "<option value='add'>".getLabel("action.add")."</option>";
							echo "<option value='delete' SELECTED>".getLabel("label.manage_remediation.remediation_action.remove")."</option>";
						} else {
							echo "<option value='add' SELECTED>".getLabel("action.add")."</option>";
							echo "<option value='delete'>".getLabel("label.manage_remediation.remediation_action.remove")."</option>";
						}
					?>
				</select>
			</div>
		</div>

		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.kettle_apps.time_period_select"); ?></label>
			<div class="col-md-9">
				<div class="input-group input-validity-date">
					<input type="text" <?php echo $disable; ?> class="form-control" readonly id="validity_date">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
					<input id="datepickerStart" name="start" type="hidden" value='<?php echo $remediation_dateDebut?>'>
					<input id="datepickerEnd" name="end" type="hidden" value='<?php echo $remediation_dateFin?>'>
				</div>
			</div>
		</div>
		
		<?php if($remediation_id == 0){ ?>
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.request"); ?></label>
			<div class="col-md-9">
				<?php
					if($remediation_create == 1){
						echo "<input type='checkbox' class='checkbox' name='request' checked value='1'>";
					}else{
						echo "<input type='checkbox' class='checkbox' name='request' value='1'>";
					}
				?>
			</div>
		</div>
		<?php } ?>

		<div class="form-group">
			<?php
				if (isset($remediation_action_id) && $remediation_action_id != null) {
					if(isset($remediation_state)){
						if((!$validator && ($remediation_state == "executed" || $remediation_state == "waiting")) || ($remediation_state == "executed" && $validator) ) {
							echo "<input disabled class='btn btn-primary' type='submit' name='update' value=".getLabel('action.update').">";
						}else{
							echo "<input class='btn btn-primary' type='submit' name='update' value=".getLabel('action.update').">";
						}
					}else{
						echo "<input class='btn btn-primary' type='submit' name='update' value=".getLabel('action.update').">";
					}
				}else{
					echo "<button class='btn btn-primary' type='submit' name='add' value='add'>".getLabel("action.add")."</button>";
				}
				echo "<button class='btn btn-default' style='margin-left: 10px;' type='button' name='back' value='back' onclick='location.href=\"index.php?action=remediation_action\"'>".getLabel("action.cancel")."</button>";
			?>
		</div>
	</form>

</div>

<?php include("../../footer.php"); ?>
