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
	if($action==null or $action =="remediation") {
		// SQL get rules
		$rules_sql="SELECT * FROM remediation";
	?>

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.module_eorweb.list_remediations"); ?></h1>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<h2><?php echo getLabel("label.module_eorweb.remediations"); ?></h2>
		</div>
	</div>
	
	<form action="./index.php" method="POST">
		<div class="dataTable_wrapper">
			<div class="table-responsive">
				<table class="table table-striped datatable-eonweb table-condensed">
					<thead>
						<tr>
							<th class="text-center"> <?php echo getLabel("label.admin_group.select"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.name"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.user"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.date_demand"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.date_validation"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.status"); ?> </th>
						</tr>
					</thead>
					<tbody>
					<?php
					// Get remediation_pack
					$methods=sqlrequest($database_eorweb,$rules_sql);

					while ($line = mysqli_fetch_array($methods)) {
					?>
						<tr>
							<td class="text-center"><label><input type="checkbox" class="checkbox" name="method_selected[]" value="<?php echo $line["id"]; ?>"></label></td>
							<td><a href="methods.php?id=<?php echo $line["id"]; ?>"><?php echo $line["name"]; ?></a></td>
							<td><?php echo $line["user_id"]; ?></td>
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
				<a href="./remediation.php" class="btn btn-default" role="button">Valider</a>
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
			<h1 class="page-header"><?php echo getLabel("label.module_eorweb.list_remediations_action"); ?></h1>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<h2><?php echo getLabel("label.module_eorweb.remediations_action"); ?></h2>
		</div>
	</div>
	
	<form action="./index.php" method="POST">
		<div class="dataTable_wrapper">
			<div class="table-responsive">
				<table class="table table-striped datatable-eonweb table-condensed">
					<thead>
						<tr>
							<th class="text-center"> <?php echo getLabel("label.admin_group.select"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.desc"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.type"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.date_beginning"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.date_ending"); ?> </th>
						</tr>
					</thead>
					<tbody>
					<?php
					// Get remediation_pack
					$methods=sqlrequest($database_eorweb,$rules_sql);

					while ($line = mysqli_fetch_array($methods)) {
					?>
						<tr>
							<td class="text-center"><label><input type="checkbox" class="checkbox" name="method_selected[]" value="<?php echo $line["id"]; ?>"></label></td>
							<td><a href="methods.php?id=<?php echo $line["id"]; ?>"><?php echo $line["description"]; ?></a></td>
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
				<button class="btn btn-danger" type="submit" name="actions" value="del_method"><?php echo getLabel("action.clear");?></button>
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
		<h1 class="page-header"><?php echo getLabel("label.module_eorweb.list_accepted_remediations"); ?></h1>
	</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<h2><?php echo getLabel("label.module_eorweb.remediations"); ?></h2>
		</div>
	</div>
	
	<form action="./index.php" method="POST">
		<div class="dataTable_wrapper">
			<div class="table-responsive">
				<table class="table table-striped datatable-eonweb table-condensed">
					<thead>
						<tr>
							<th class="text-center"> <?php echo getLabel("label.admin_group.select"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.name"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.user"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.date_demand"); ?> </th>
							<th> <?php echo getLabel("label.module_eorweb.date_validation"); ?> </th>
						</tr>
					</thead>
					<tbody>
					<?php
					// Get remediation_pack
					$methods=sqlrequest($database_eorweb,$rules_sql);

					while ($line = mysqli_fetch_array($methods)) {
					?>
						<tr>
							<td class="text-center"><label><input type="checkbox" class="checkbox" name="method_selected[]" value="<?php echo $line["id"]; ?>"></label></td>
							<td><a href="methods.php?id=<?php echo $line["id"]; ?>"><?php echo $line["name"]; ?></a></td>
							<td><?php echo $line["user_id"]; ?></td>
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
				<a href="./remediation.php" class="btn btn-success" role="button">Executer</a>
				<a href="./remediation.php" class="btn btn-default" role="button">Annuler</a>
			</div>
		</div>
	</form>
	<?php
	}
	?>
	
</div>

<?php include("../../footer.php"); ?>