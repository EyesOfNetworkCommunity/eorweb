 
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

if (isset($_GET["id"])) {
	$remediation_id = htmlspecialchars($_GET["id"]);
} else {
	$remediation_id = "";
}

$result_name = sqlrequest($database_eorweb,"SELECT name FROM remediation WHERE id = ?",false,array("i",(int)$remediation_id));
$result_action = sqlrequest($database_eorweb,"SELECT * FROM remediation INNER JOIN remediation_action ON remediation.id = remediationID WHERE remediationID = ?",false,array("i",(int)$remediation_id));
?> 

<div id="page-wrapper">
	
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_remediation.list_remediations_action").": ".mysqli_result($result_name,0,"name"); ?></h1>
		</div>
	</div>

	<div class="dataTable_wrapper">
		<table class="table table-striped datatable-eorweb table-condensed">
			<thead>
				<tr>
					<th> <?php echo getLabel("label.manage_remediation.desc"); ?> </th>
					<th> <?php echo getLabel("label.manage_remediation.type"); ?> </th>
					<th> <?php echo getLabel("label.manage_remediation.date_beginning"); ?> </th>
					<th> <?php echo getLabel("label.manage_remediation.date_ending"); ?> </th>
					<th> <?php echo getLabel("label.manage_remediation.status"); ?> </th>
				</tr>
			</thead>
			<tbody>
				<?php
				if($result_action) {
					while ($line = mysqli_fetch_array($result_action)) { ?>
						<tr>
							<td><a href="index.php"><?php echo $line["description"]; ?></a></td>
							<td><?php echo getLabel("label.manage_remediation.type_".$line["type"]); ?></td>
							<td><?php echo $line["DateDebut"]; ?></td>
							<td><?php echo $line["DateFin"]; ?></td>
							<td><?php if ($line["state"]) { echo getLabel("label.manage_remediation.state_".$line["state"]); } ?></td>
						</tr>
					<?php
					}
				} ?>
			</tbody>
		</table>
	</div>

</div>

<?php include("../../footer.php"); ?>
