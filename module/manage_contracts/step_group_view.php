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
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.step_group__view_title"); ?></h1>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th><?php echo getLabel("label.admin_group.group_name"); ?></th>
					<th><?php echo getLabel("label.contracts_menu.indicator_create_name"); ?></th>
					<th><?php echo getLabel("label.contracts_menu.seuils_display_tab_threshold"); ?></th>
					<th><?php echo getLabel("label.actions"); ?></th>
				</tr>
			</thead>
			<tbody id="body_table">
				<?php
				$sql = "SELECT id_step_group, type, id_kpi, name, step_number, step_1_min, step_1_max, step_2_min, step_2_max, step_3_min, step_3_max, step_4_min, step_4_max, step_5_min, step_5_max, step_6_min, step_6_max, step_7_min, step_7_max, step_8_min, step_8_max,  step_9_min, step_9_max, step_10_min, step_10_max 
				FROM step_group";
				$ccv = sqlrequest($database_vanillabp,$sql);
				if($ccv) {
					while ($line = mysqli_fetch_array($ccv)) {
						$step_array = "";
						$sql2 = "SELECT name FROM kpi where id_kpi=".$line["id_kpi"];
						$ccv2 = mysqli_fetch_array(sqlrequest($database_vanillabp,$sql2));
						
						$type=$line["type"];
						
						for ($i = 0; $i < $line["step_number"]; $i++){
							$number = $i +1;
							$step_min = $line['step_' .$number. '_min'];
							$step_max = $line['step_' .$number. '_max'];
							if($i == 4){
								$step_array .= '    <span class="glyphicon glyphicon-option-horizontal" style="vertical-align:bottom"></span>';
								break;
							}
							$step_array .= '  <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-tag"></span> ' .$step_min. $type .' ; ' .$step_max . $type .'</button>';
						}
						?>
						<tr>
							<td><span class="glyphicon glyphicon-share-alt text-warning"></span></td>
							<td> <?php echo $line["name"]; ?> </td>
							<td> <?php echo $ccv2["name"]; ?> </td>
							<td> <?php echo $step_array; ?> </td>
							<td>
								<button type="button" class="btn btn-primary" id="<?php echo $line["id_step_group"]; ?> " onclick=EditSelection(id)><span class="glyphicon glyphicon-pencil"></span></button>
								<button type="button" class="btn btn-danger" id="<?php echo $line["id_step_group"]; ?> " onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button>
							</td>
						</tr>
						<?php
					}
				}
				?>
			</tbody>
		</table>
	</div>

	<div class="row">
		<div class="col-md-12">
			<input type="button" class="btn btn-primary" value="<?php echo getLabel("label.manage_contracts.step_group_add"); ?>" onclick="location.href='./step_group.php';">
		</div>
	</div>
</div>

<?php include("../../footer.php"); ?>