<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Michael Aubertin
# VERSION 2.0
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
			<h1 class="page-header"><?php echo getLabel("label.manage_report.report_declaration");?></h1>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive form-group">          
				<table id="table-manage-report" class="table table-striped">
					<thead>
						<tr>
							<th><?php echo getLabel("label.manage_report.column_report_name");?></th>
							<th><?php echo getLabel("label.manage_report.column_filename");?></th>
							<th class="col-sd-1"><?php echo getLabel("label.actions");?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$sql = "SELECT * FROM reports ORDER BY report_rptfile;";
						$result = sqlrequest($database_eorweb, $sql);
						while($row = $result->fetch_assoc()){
						?>
						<tr>
							<td><?php echo $row['report_name']; ?></td>
							<td><?php echo $path_rptdesign."/".$row['report_rptfile']; ?></td>
							<td>
								<a class="btn btn-primary" href="form_edit_cred.php?report_id=<?php echo $row['report_id']; ?>" role="button">
									<span class="glyphicon glyphicon-pencil"></span>
								</a>
								<button type="button" name="delete" class="btn btn-danger" id="<?php echo $row['report_id']; ?>">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<button class="btn btn-primary" id="AddButton"><?php echo getLabel("label.manage_report.add_report");?></button>
</div>

<?php include("../../footer.php"); ?>
