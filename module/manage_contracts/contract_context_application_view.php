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
					<th><?php echo getLabel("label.actions"); ?></th>
				</tr>
			</thead>
			<tbody id="body_table">
				<?php
				$sql = "SELECT id_contract_context, application_name FROM contract_context_application order by application_name";
				$ccv = sqlrequest($database_vanillabp,$sql);
				if($ccv) {
					while ($line = mysqli_fetch_array($ccv)) {
						$sql2 = "SELECT contract.name AS contract,time_period.name AS time_period,kpi.name AS kpi,step_group.name AS step_group, contract_context.name AS contract_context, contract_context.id_contract_context AS context_id FROM contract_context INNER JOIN contract ON contract_context.id_contract = contract.id_contract INNER JOIN time_period ON contract_context.id_time_period = time_period.id_time_period INNER JOIN kpi ON contract_context.id_kpi = kpi.id_kpi INNER JOIN step_group ON contract_context.id_step_group = step_group.id_step_group WHERE contract_context.id_contract_context = ". $line["id_contract_context"] ." ORDER BY contract_context";
						$ccv2 = mysqli_fetch_array(sqlrequest($database_vanillabp,$sql2));
						?>
							<tr>
								<td><span class="glyphicon glyphicon-share-alt text-warning"></span></td>
								<td><?php echo $line["application_name"]; ?></td>
								<td><?php echo $ccv2["contract"]; ?></td>
								<td><?php echo $ccv2["time_period"]; ?></td>
								<td><?php echo $ccv2["kpi"]; ?></td>
								<td><?php echo $ccv2["step_group"]; ?></td>
								<td><a class="btn btn-primary" href="./contract_context_application.php?context_name=<?php echo $ccv2["contract"]; ?>_-_<?php echo $line["id_contract_context"]; ?>" role="button"><span class="glyphicon glyphicon-pencil"></span></a>
									<button type="button" class="btn btn-danger" name="<?php echo $line["application_name"]; ?>" id="<?php echo $line["id_contract_context"]; ?>" onclick=RemoveSelection(name,id)><span class="glyphicon glyphicon-trash"></span></button>
								</td>
							</tr>
						<?php
					}
				}
				?>
			</tbody>
		</table>
	</div>
	
	<input type="button" class="btn btn-primary" value="<?php echo getLabel("label.manage_contracts.contract_context_application_add"); ?>" onclick="location.href='./contract_context_application.php';">

</div>

<?php include("../../footer.php"); ?>
