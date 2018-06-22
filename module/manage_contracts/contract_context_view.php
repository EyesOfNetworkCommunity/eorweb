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
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.contract_context_view_title"); ?></h1>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th><?php echo getLabel("label.name"); ?></th>
					<th><?php echo getLabel("label.description"); ?></th>
					<th><?php echo getLabel("label.manage_contracts.associate_contracts"); ?></th>
					<th><?php echo getLabel("label.time_period"); ?></th>
					<th><?php echo getLabel("label.indicator"); ?></th>
					<th><?php echo getLabel("label.manage_contracts.associate_sla"); ?></th>
					<th><?php echo getLabel("label.actions"); ?></th>
				</tr>
			</thead>
			<tbody id="body_table">
			<?php
			$sql = "select contract_context.ID_CONTRACT_CONTEXT as id,contract_context.name,contract_context.alias,
					contract.name as contract,time_period.name as time_period,kpi.name as kpi,step_group.name as step_group
					from contract_context
					inner join contract on contract_context.id_contract = contract.id_contract
					inner join time_period on contract_context.id_time_period = time_period.id_time_period
					inner join kpi on contract_context.id_kpi = kpi.id_kpi
					inner join step_group on contract_context.id_step_group = step_group.id_step_group
					order by contract_context.name";
			$ccv = sqlrequest($database_vanillabp,$sql);
			if($ccv) {
				while ($line = mysqli_fetch_array($ccv)) {
					?>
					<tr id="<?php echo $line["id"]; ?>">
						<td><span class="glyphicon glyphicon-share-alt text-warning"></span>
						<td><?php echo $line["name"]; ?></td>
						<td><?php echo $line["alias"]; ?></td>
						<td><?php echo $line["contract"]; ?></td>
						<td><?php echo $line["time_period"]; ?></td>
						<td><?php echo $line["kpi"]; ?></td>
						<td><?php echo $line["step_group"]; ?></td>
						<td>
							<button type="button" class="btn btn-primary" id="<?php echo $line["id"]; ?>" onclick=EditSelection(id)>
								<span class="glyphicon glyphicon-pencil"></span>
							</button>
							<button type="button" class="btn btn-danger" id="<?php echo $line["id"]; ?>" onclick=RemoveSelection(id)>
								<span class="glyphicon glyphicon-trash"></span>
							</button>
						</td>
					</tr> 
					<?php
				}
			}
			?>
			</tbody>
		</table>
	</div>

	<input type="button" class="btn btn-primary" value="<?php echo getLabel("label.manage_contracts.contract_context_add"); ?>" onclick="location.href='./contract_context.php';">

</div>

<?php include("../../footer.php"); ?>
