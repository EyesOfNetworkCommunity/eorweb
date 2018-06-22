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
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.time_period_view_title"); ?></h1>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th><?php echo getLabel("label.name"); ?></th>
					<th><?php echo getLabel("label.time_period_defined"); ?></th>
					<th><?php echo getLabel("label.actions"); ?></th>
				</tr>
			</thead>
			<tbody id="body_table">
			<?php
				$sql = "SELECT id_time_period, name FROM time_period order by name";
				$ccv = sqlrequest($database_vanillabp,$sql);
				if($ccv) {
					while ($line = mysqli_fetch_array($ccv)) {
						$count=0;
						$concatenation_time_period = "";
						$sql2 = "SELECT id_timeperiod_entry, entry, h_open, h_close FROM timeperiod_entry where id_time_period=".$line["id_time_period"];
						$ccv2 = sqlrequest($database_vanillabp,$sql2);
						while ($line2 = mysqli_fetch_array($ccv2)) {
							if($count == 4){
								$concatenation_time_period .= "    <span class='glyphicon glyphicon-option-horizontal' style='vertical-align:bottom'></span>";
								break;
							}
							$concatenation_time_period .= "  <button type='button' class='btn btn-primary'><span class='glyphicon glyphicon-tag'></span> " .$line2["entry"]. ", " .$line2["h_open"]. " " .$line2['h_close']."</button>";
							$count++;
						}
						?>
						<tr>
							<td><span class="glyphicon glyphicon-share-alt text-warning"></span></td>
							<td> <?php echo $line["name"]; ?> </td>
							<td> <?php echo $concatenation_time_period; ?> </td>
							<td>
								<button type="button" class="btn btn-primary" id="<?php echo $line["id_time_period"]; ?>" onclick=EditSelection(id)><span class="glyphicon glyphicon-pencil"></span></button>
								<button type="button" class="btn btn-danger" id="<?php echo $line["id_time_period"]; ?>" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button>
							</td>
						</tr>
						<?php
					}
				}
				?>
			</tbody>
		</table>
	</div>
	<input type="button" class="btn btn-primary" value="<?php echo getLabel("label.manage_contracts.time_period_add"); ?>" onclick="location.href='./time_period.php';">
</div>

<?php include("../../footer.php"); ?>
