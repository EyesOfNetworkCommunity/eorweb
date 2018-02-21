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
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.title"); ?></h1>
		</div>
	</div>
	<div id="welcome" class="row" style="display: none; text-align: center">
		<h3><?php echo getLabel("label.manage_contract.welcome"); ?></h3>
		<p><?php echo getLabel("label.manage_contract.welcome2"); ?></p>
	</div>
	<div id="global_container" class="row" style="display: none">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-12">
							<span><?php echo getLabel("label.manage_contract.record_number"); ?></span>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="col-md-12">
						<div class="skillst4">
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.contract_context"); ?></div>
								<div class="count-bar color-1">
									<div class="count"></div>
								</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.contract"); ?></div>
								<div class="count-bar color-2">
									<div class="count"></div>
								</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.company"); ?></div>
								<div class="count-bar color-3">
									<div class="count"></div>
								</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.time_period"); ?></div>
									<div class="count-bar color-4">
								<div class="count"></div>
							</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.indicator"); ?></div>
									<div class="count-bar color-5">
								<div class="count"></div>
							</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.sla"); ?></div>
									<div class="count-bar color-5">
								<div class="count"></div>
							</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.application"); ?></div>
								<div class="count-bar color-5">
									<div class="count"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-12">
							<span><?php echo getLabel("label.manage_contract.record_entries"); ?></span>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="col-md-12 table-responsive">
						<table class="table table-responsive">
							<thead>
								<tr>
									<th><?php echo getLabel("label.status"); ?></th>
									<th><?php echo getLabel("label.input"); ?></th>
									<th><?php echo getLabel("label.input_name"); ?></th>
									<th><?php echo getLabel("label.date"); ?></th>
								</tr>
							</thead>
							<tbody id="body_table">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include("../../footer.php"); ?>
