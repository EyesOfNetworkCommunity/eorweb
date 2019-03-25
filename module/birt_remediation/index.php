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

	$req = "SELECT validator FROM groups WHERE group_id = ?";
	$validator_right = sqlrequest($database_eorweb,$req,false,array("i",(int)$_COOKIE['group_id']));
	$remediation_right = mysqli_result($validator_right,0,"validator");

	// SQL get rules
	$rules_sql = "SELECT *, DATE_FORMAT(date_demand, '%d-%m-%Y %Hh%i') AS date_demand, DATE_FORMAT(date_validation, '%d-%m-%Y %Hh%i') AS date_validation FROM remediation ORDER BY date_demand DESC, name";
	?>

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_remediation.list_remediations"); ?></h1>
		</div>
	</div>
	
	<?php
	switch($actions){
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
				$message_update = false;
				for ($i = 0; $i < sizeof($remediation_selected); $i++) {
					$req = sqlrequest($database_eorweb, "SELECT state FROM remediation WHERE id='$remediation_selected[$i]'");
					$execute_request = mysqli_result($req,0,"state");
					// Update remediations state if request-remediation has not been executed
					if ($execute_request != "executed") {
						sqlrequest($database_eorweb,"UPDATE remediation SET state='refused', date_validation='".date("Y-m-d G:i")."' WHERE id='$remediation_selected[$i]'");
						$message_update = true;
					}
				}
				// display message if a request_remdiation has been updated
				if ($message_update) {
					message(6," : ".getLabel("message.manage_remediation.request_update"),'ok');
				} else {
					message(6," : ".getLabel("message.manage_remediation.request_not_update"),'warning');
				}
			}
			break;
	} ?>
		
	<form action="./index.php" method="POST">
		<div class="dataTable_wrapper">
			<table class="table table-striped datatable-eorweb table-condensed">
				<thead>
					<tr>
						<?php if ($remediation_right > 0) {
							echo "<th class=\"text-center\">".getLabel("label.admin_group.select")."</th>";
						} ?>
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
					while ($line = mysqli_fetch_array($methods)) { ?>
						<tr>
							<?php if ($remediation_right > 0) { ?>
								<td class="text-center"><label><input type="checkbox" class="checkbox" name="remediation_selected[]" value="<?php echo $line["id"]; ?>"></label></td>"
							<?php
							} ?>
							<td><a href="remediation.php?id=<?php echo $line["id"]; ?>"><?php echo $line["name"]; ?></a></td>
							<td><?php echo mysqli_result(sqlrequest($database_eorweb,"SELECT user_name FROM users WHERE user_id='".$line["user_id"]."'"),0,"user_name"); ?></td>
							<td><?php echo $line["date_demand"]; ?></td>
							<td><?php echo $line["date_validation"]; ?></td>
							<td><?php echo getLabel("label.manage_remediation.state_".$line["state"]); ?></td>
						</tr>
					<?php
					}
				}
				?>
				</tbody>
			</table>
			<div class="form-group">
				<?php 
				// if the user has the validation rights
				if ($remediation_right > 0) { ?>
					<button class="btn btn-default" type="submit" name="actions" value="validation"><?php echo getLabel("action.validate");?></button>
					<button class="btn btn-danger" type="submit" name="actions" value="refus"><?php echo getLabel("action.refuse");?></button>
				<?php } ?>
			</div>
		</div>
	</form>
	
</div>

<?php include("../../footer.php"); ?>
