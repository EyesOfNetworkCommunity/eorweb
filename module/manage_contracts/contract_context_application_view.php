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
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.contract_context_application_view_title"); ?></h1>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="radius_th"></th>
					<th><?php echo getLabel("menu.link.app"); ?></th>
					<th><?php echo getLabel("menu.subtad.contracts"); ?></th>
					<th><?php echo getLabel("label.time_period"); ?></th>
					<th><?php echo getLabel("label.contracts_menu.indicator"); ?></th>
					<th><?php echo getLabel("label.sla"); ?></th>
					<th class="radius_th"><?php echo getLabel("action.delete"); ?></th>
				</tr>
			</thead>
			<tbody id="body_table">
			</tbody>
		</table>
	</div>
	
	<input type="button" class="btn btn-primary" value="<?php echo getLabel("label.manage_contracts.contract_context_application_add"); ?>" onclick="location.href='./contract_context_application.php';">

</div>

<?php include("../../footer.php"); ?>
