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

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_remediation.remediation_new"); ?></h1>
		</div>
	</div>
	
	<?php 	
	$user_infos2=array();
	$remediation_action_id="";
	
	// concatenation of actions send by post
	if(!empty($_POST['remediation_actions_id'])){
		foreach($_POST['remediation_actions_id'] as $selected){
			$remediation_action_id .= $selected.",";
		}
		$remediation_action_id=rtrim($remediation_action_id,",");
	}
	
	// General data
	$user_id = $_COOKIE['user_id'];
	if(isset($_POST["add"])){
		$remediation_id = NULL;
		$date_demand = date("Y-m-d G:i");
	}
	
	// get infos for updates
	$remediation_id = retrieve_form_data("id",null);
	$remediation_name = retrieve_form_data("name",null);
	
	if($remediation_id != null && !isset($_POST['add']) && !isset($_POST['update'])){
		$user_infos = sqlrequest($database_eorweb, "SELECT * FROM remediation WHERE id='".$remediation_id."'");
		$user_infos2 = sqlrequest($database_eorweb, "SELECT description FROM remediation_action WHERE remediationID='".$remediation_id."'");
		
		// Retrieve Information from database
		$remediation_name = mysqli_result($user_infos,0,"name");
		$remediation_statut = mysqli_result($user_infos,0,"state");
		
		while ($line = mysqli_fetch_array($user_infos2)){
			$remediation_action_id .= $line[0].",";
		}
		$remediation_action_id = substr($remediation_action_id, 0, -1);
	}

	if(isset($_POST["add"]) || isset($_POST["update"])) {
		if(!$remediation_name || $remediation_name == ""){
			message(7," : ".getLabel("message.error.remediation_request_name"),'warning');
		}
		elseif(empty($remediation_action_id) || $remediation_action_id == null) {
			message(7," : ".getLabel("message.error.remediation_request_action"),'warning');
		}
		elseif(isset($_POST["add"])){
			// insert values for add
			$sql_add = "INSERT INTO remediation (name,user_id,date_demand) VALUES('".$remediation_name."','".$user_id."','".$date_demand."')";
			$remediation_statut = "inactive";
			$remediation_id = sqlrequest($database_eorweb,$sql_add,true);
			$remediation_ids=explode(",",$remediation_action_id);
		
			foreach($remediation_ids as $selected){
				sqlrequest($database_eorweb,"UPDATE remediation_action SET remediationID = '".$remediation_id."' WHERE description='".$selected."'");
			}
			message(6," : ".getLabel("message.manage_remediation.request_created"),'ok');		
		}
		elseif(isset($_POST["update"])){
			$user_infos = sqlrequest($database_eorweb, "SELECT * FROM remediation WHERE id='".$remediation_id."'");
			$remediation_statut = mysqli_result($user_infos,0,"state");
			$sql_modify = "UPDATE remediation SET name='".$remediation_name."' WHERE id='".$remediation_id."'";
			sqlrequest($database_eorweb,$sql_modify);
			
			$Infos=explode(",", $remediation_action_id);	
			for($i=0; $i<count($Infos);$i++){
				$value = mysqli_result(sqlrequest($database_eorweb, "SELECT id FROM remediation_action WHERE description='".$Infos[$i]."'"), 0,"id");
				array_push($user_infos2,$value);
			}

			sqlrequest($database_eorweb,"UPDATE remediation_action SET remediationID = '0' WHERE remediationID='".$remediation_id."'",true);
			foreach($user_infos2 as $selected){
				sqlrequest($database_eorweb,"UPDATE remediation_action SET remediationID = '".$remediation_id."' WHERE id='".$selected."'",true);
			}
			?> <div id="remediation_id" hidden value="<?php echo $remediation_id; ?>"></div> <?php

			message(6," : ".getLabel("message.manage_remediation.request_update"),'ok');
		}
	}
	?>
	
	<form id="form_remediation" action='./remediation.php' method='POST' name='form_remediation'>
		<input type='hidden' name='id' value='<?php echo $remediation_id; ?>'>
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.remediation_name"); ?></label>
			<div class="col-md-9">
				<input class="form-control" type='text' name='name' value="<?php echo $remediation_name; ?>" maxlength="25" autofocus>
			</div>
		</div>
		
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.remediation_action_add"); ?></label>
			<div class="col-md-9">
				<div class="form-group input-group">
					<input class="form-control" id="rule_host1" type="text" name="rule_host">
					<span class="input-group-btn">
						<?php  
						if(isset($remediation_statut) && ($remediation_statut == "executed" || $remediation_statut == "approved" || $remediation_statut == "waiting") ) { ?>
							<input class="btn btn-danger" disabled id="rule_host_button_del" type="button" value="<?php echo getLabel("action.delete");?>">
						<?php } else { ?>
							<input class="btn btn-danger" id="rule_host_button_del" type="button" value="<?php echo getLabel("action.delete");?>">
						<?php } ?>
					</span>
				</div>
				<select class="form-control" id="remediation_actions_id" name="remediation_actions_id[]" multiple size=4>
				<?php 
					if($remediation_action_id != ""){
						$division = explode(",", $remediation_action_id);
						for($i=0; $i<sizeof($division);$i++){
							echo "<option selected='selected' value='".$division[$i]."'>".$division[$i]."</option> ";
						}
					}
				?>
				</select>
			</div>
		</div>
		
		<div class="form-group">
			<?php
				if (isset($remediation_id) && $remediation_id != null) { ?>
					<?php if(isset($remediation_statut) && ($remediation_statut == "executed" || $remediation_statut == "approved" || $remediation_statut == "waiting") ) { ?>
						<input class="btn btn-primary" type="submit" name="update" value="<?php echo getLabel('action.update'); ?>" disabled>
					<?php } else { ?>
						<input class="btn btn-primary" type="submit" name="update" value="<?php echo getLabel('action.update'); ?>">
					<?php } ?>
				<?php } else {
					echo "<input class='btn btn-primary' type='submit' name='add' value=".getLabel('action.add').">";
				}
			?>
			<button class="btn btn-default" style="margin-left: 10px;" type="button" name="back" value="back" onclick='location.href="index.php"'><?php echo getLabel("action.cancel"); ?></button>
		</div>
	</form>

</div>

<?php include("../../footer.php"); ?>
