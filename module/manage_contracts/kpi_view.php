<?php 
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Jean-Philippe LEVY
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
		<div class="col-md-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.kpi_view_title"); ?></h1>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
			        <th><?php echo getLabel("label.name"); ?></th>
		            <th><?php echo getLabel("label.contracts_menu.indicator_display_tab_unit"); ?></th>
				    <th><?php echo getLabel("label.actions"); ?></th>
				</tr>
			</thead>
			<tbody id="body_table">
			</tbody>
		</table>
	</div>

	<div class="row">
		<div class="col-md-12">
			<input type="button" class="btn btn-primary" value="<?php echo getLabel("label.manage_contracts.kpi_add"); ?>" onclick="location.href='./kpi.php';">
		</div>
	</div>
</div>

<?php include("../../footer.php"); ?>
