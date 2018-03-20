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

// Get hosts or services by autocompletion
function get_field_autocomplete($service=false) {
	global $database_vanillabp;
	global $database_thruk;
	
	$autocomplete=array();
	
	$request="SELECT distinct thruk_idx FROM bp_sources";
	$infs=sqlrequest($database_vanillabp,$request);
	
	while ($inf = mysqli_fetch_array($infs)){ 
		if($inf[0] != "NR"){
			if($service != false){
				$requests="SELECT DISTINCT service_description FROM $inf[0]_service";
			}else{
				$requests="SELECT DISTINCT host_name FROM $inf[0]_host";
			}
			$result = sqlrequest($database_thruk,$requests);
			
			while ($line = mysqli_fetch_array($result)){ 
				if($service != false){
					$autocomplete[]=$line[0];
				}else{
					$autocomplete[]=$inf[0]."--".$line[0];
				}
			}
		}
	}
	error_log( print_r($autocomplete, TRUE) );
		
	$autocomplete= array_unique($autocomplete);
	echo json_encode($autocomplete);
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
	$id = retrieve_form_data("id",null);
	$invalid=false;
	
	if($id != null){
		$user_infos=sqlrequest("eorweb", "SELECT * from remediation_action where id='".$id."'");
		
		// Retrieve Information from database
		$remediation_name=mysqli_result($user_infos,0,"description");
		$remediation_host==mysqli_result($user_infos,0,"host");
		$remediation_service==mysqli_result($user_infos,0,"service");
		$remediation_type=mysqli_result($user_infos,0,"type");
		$remediation_dateDebut=mysqli_result($user_infos,0,"DateDebut");
		$remediation_dateFin=mysqli_result($user_infos,0,"DateFin");
		$action=mysqli_result($user_infos,0,"Action");
	}else{	
		$remediation_name=retrieve_form_data("name",null);
		$remediation_host=retrieve_form_data("host",null);
		$remediation_service=retrieve_form_data("service",null);
		$remediation_type=retrieve_form_data("type",null);
		$remediation_dateDebut=retrieve_form_data("start",null);
		$remediation_dateFin=retrieve_form_data("end",null);
	}
	
	if(isset($_POST["add"]) || isset($_POST["update"])) {
		if(!$remediation_name || $remediation_name==""){
			message(7," : Your remediation need a name",'warning');
		}
		elseif(!$remediation_type || $remediation_type==""){
			message(7," : Your remediation need a type",'warning');
		}
		elseif(!$remediation_host || $remediation_host==""){
			message(7," : Your remediation need a host",'warning');
		}
		elseif(!$remediation_service || $remediation_service==""){
			message(7," : Your remediation need a service",'warning');
		}
		/*elseif($remediation_dateDebut>$remediation_dateFin){
			message(7," : Wrong order in your dates",'warning');
		}*/
		elseif(isset($_POST["add"])){
			$desciptionExist = sqlrequest("eorweb","SELECT description from remediation_action");
			while ($line = mysqli_fetch_array($desciptionExist)){
				if($line[0] == $remediation_name){
					message(7," : Your description already exist",'warning');
					$invalid=true;
				}
			}
			
			if(!$invalid){
				// insert values for add
				$sql_add = "INSERT INTO remediation_action (description,type,DateDebut,DateFin,Action,host,service) VALUES('".$remediation_name."','".$remediation_type."','".$remediation_dateDebut."','".$remediation_dateFin."', 'add','".$remediation_host."','".$remediation_service."')";
				$remediation_id = sqlrequest("eorweb",$sql_add,true);
				
				message(6," : Remediation have been created",'ok');
			}
		}elseif(isset($_POST["update"])){
			
			message(6," : Remediation have been updated",'ok');
		}
	}
	
	?>
	<form id="form_user" action='./remediation_action.php' method='POST' name='form_user'>
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.remediation_action_name"); ?></label>
			<div class="col-md-9">
				<input class="form-control" type='text' name='name'  value='<?php echo $remediation_name?>'>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-3">Host</label>
			<div class="col-md-9">
				<input class="form-control" type='text' id='host' name='host'  value='<?php echo $remediation_host?>' onFocus='$(this).autocomplete({source: <?php echo get_field_autocomplete(); ?>})'>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-3">Service</label>
			<div class="col-md-9">
				<input class="form-control" type='text' id='service' name='service' value='<?php echo $remediation_service?>' onFocus='$(this).autocomplete({source: <?php echo get_field_autocomplete(true); ?>})'>
			</div>
		</div>

		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.type"); ?></label>
			<div class="col-md-9">
				<select class="form-control" name='type' size=1>
					<?php
						if ($remediation_type == "incident"){
							echo "<OPTION value='OUTAGE'>Maintenance </OPTION>";
							echo "<OPTION value='DOWNTIME' SELECTED>incident </OPTION>";
						}else{
							echo "<OPTION value='OUTAGE' SELECTED>Maintenance </OPTION>";
							echo "<OPTION value='DOWNTIME'>Incident </OPTION>";
						}
					?>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.date_beginning"); ?></label>
			<div class="col-md-9">
				<input type="text" id="datepickerStart" class="form-control datepicker_start" name="start" value='<?php echo $remediation_dateDebut?>'>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.date_ending"); ?></label>
			<div class="col-md-9">
				<input type="text" id="datepickerEnd" class="form-control datepicker_end" name="end" value='<?php echo $remediation_dateFin?>'>
			</div>
		</div>
		
		<div class="form-group">
			<?php
				echo "<button class='btn btn-primary' type='submit' name='add' value='add'>".getLabel("action.add")."</button>";
				echo "<button class='btn btn-default' style='margin-left: 10px;' type='button' name='back' value='back' onclick='location.href=\"index.php?action=remediation_action\"'>".getLabel("action.cancel")."</button>";
			?>
		</div>
	</form>

</div>

<?php include("../../footer.php"); ?>

