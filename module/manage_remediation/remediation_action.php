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

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_remediation.remediation_action_new"); ?></h1>
		</div>
	</div>
	
	<?php 
	$remediation_name=retrieve_form_data("name",null);
	$remediation_type=retrieve_form_data("type",null);
	$remediation_dateDebut=retrieve_form_data("start",null);
	$remediation_dateFin=retrieve_form_data("end",null);
	
	if(isset($_POST["add"]) || isset($_POST["update"])) {
		if(!$remediation_name || $remediation_name==""){
			message(7," : Your remediation need a name",'warning');
		}
		elseif(!$remediation_type || $remediation_type==""){
			message(7," : Your remediation need a type",'warning');
		}
		/*elseif($remediation_dateDebut>$remediation_dateFin){
			message(7," : Wrong order in your dates",'warning');
		}*/
		elseif(isset($_POST["add"])){
			
			// insert values for add
			$sql_add = "INSERT INTO remediation_action (description,type,DateDebut,DateFin,Action) VALUES('".$remediation_name."','".$remediation_type."','".$remediation_dateDebut."','".$remediation_dateFin."', 'add')";
			$remediation_id = sqlrequest("eorweb",$sql_add,true);
			
			message(6," : Remediation have been created",'ok');
			
		}elseif(isset($_POST["update"])){
			
			message(6," : Remediation have been updated",'ok');
		}
	}
	
	?>
				
	<form id="form_user" action='./remediation_action.php' method='POST' name='form_user'>
		<input type='hidden' name='user_id' value='<?php echo $user_id?>'>
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.remediation_action_name"); ?></label>
			<div class="col-md-9">
				<input class="form-control" type='text' name='name'>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.type"); ?></label>
			<div class="col-md-9">
				<select class="form-control" name='type' size=1>
					<OPTION value='maintenance' SELECTED>Maintenance </OPTION>";
					<OPTION value='outage'>Outage </OPTION>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.date_beginning"); ?></label>
			<div class="col-md-9">
				<input type="text" class="form-control datepicker_start" name="start">
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.manage_remediation.date_ending"); ?></label>
			<div class="col-md-9">
				<input type="text" class="form-control datepicker_end" name="end">
			</div>
		</div>
		
		<div class="form-group">
			<?php
				echo "<button class='btn btn-primary' type='submit' name='add' value='add'>".getLabel("action.add")."</button>";
				echo "<button class='btn btn-default' style='margin-left: 10px;' type='button' name='back' value='back' onclick='location.href=\"index.php\"'>".getLabel("action.cancel")."</button>";
			?>
		</div>
	</form>

</div>

<?php include("../../footer.php"); ?>

