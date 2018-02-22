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
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.time_period_title"); ?></h1>
		</div>
	</div>

	<div id="global_form"></div>
	
	<form>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-name">
					<label for="name"><?php echo getLabel("label.contracts_menu.period_create_name"); ?></label>
					<div class="input-name">
						<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
				</div>
			</div>	
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-desc">
					<label for="desc"><?php echo getLabel("label.description"); ?></label>
					<div class="input-desc">
						<input type="text" class="form-control" id="desc" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*éèàêâç]/g,'')">
					</div>
				</div>
			</div>	
		</div>
	</form>

	<div class="row">
		<div class="col-md-12">
			<h2 class="page-header"><?php echo getLabel("label.contracts_menu.period_create_title_create"); ?></h2>
		</div>
	</div>

	<div class="form-group">
		<form>
			<div class="row form-group">
				<div class="col-md-6">
					<div class="has-feedback div-entry">
						<label for="name_entry"><?php echo getLabel("label.day"); ?></label>
						<div class="input-entry">
							<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_entry"><?php echo getLabel("label.day"); ?>
							<span class="caret"></span></button>
							<ul class="dropdown-menu btn-block" id="ul_entry">
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="row form-group">
				<div class="col-md-6">
					<div class="has-feedback">
						<label for="start_hour"><?php echo getLabel("label.contracts_menu.period_create_hour_start"); ?></label>
						<div class="row">
							<div class="col-xs-6 col-md-6">
								<div class="has-feedback">
									<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="start_hour"><?php echo getLabel("label.hour"); ?>
									<span class="caret"></span></button>
									<ul class="dropdown-menu btn-block" id="ul_start_hour">
									</ul>
								</div>
							</div>
							<div class="col-xs-6 col-md-6">
								<div class="has-feedback">
									<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="start_min"><?php echo getLabel("label.minute"); ?>
									<span class="caret"></span></button>
									<ul class="dropdown-menu btn-block" id="ul_start_min">
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row form-group">
				<div class=" col-md-6 has-feedback">
					<label for="end_hour"><?php echo getLabel("label.contracts_menu.period_create_hour_end"); ?></label>
					<div class="row">
						<div class="col-xs-6 col-md-6">
							<div class="has-feedback">
								<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="end_hour"><?php echo getLabel("label.hour"); ?>
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu btn-block" id="ul_end_hour">
								</ul>
							</div>
						</div>
						<div class="col-xs-6 col-md-6">
							<div class="has-feedback">
								<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="end_min"><?php echo getLabel("label.minute"); ?>
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu btn-block" id="ul_end_min">
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<button class="form-group btn btn-primary" type="submit" id="submit_entry"><?php echo getLabel("label.contracts_menu.period_create_btn_add"); ?></button>
			<button class="btn btn-default" type="button" name="back" value="back" onclick="location.href='time_period_view.php'"><?php echo getLabel("action.cancel") ?></button>	

			<input type="text" class="form-control" style="display:none" id="name_entry_hide">
			<input type="text" class="form-control" style="display:none" id="start_hour_hide">
			<input type="text" class="form-control" style="display:none" id="start_min_hide">
			<input type="text" class="form-control" style="display:none" id="end_hour_hide">
			<input type="text" class="form-control" style="display:none" id="end_min_hide">
		</form>
	</div>
	
	<div id="input_list" class="row" style="display: none;">
		<div class="col-md-12">
			<h2 class="page-header"><?php echo getLabel("label.contracts_menu.period_create_title_list"); ?></h2>
		</div>
		<div class="col-md-12 form-group">
			<table class="table table-striped" style="display:none" id="container_time_period">
			    <thead>
				    <tr>
				        <th><?php echo getLabel("label.day"); ?></th>
				        <th><?php echo getLabel("label.contracts_menu.period_create_hour_start"); ?></th>
				        <th><?php echo getLabel("label.contracts_menu.period_create_hour_end"); ?></th>
						<th><?php echo getLabel("label.contracts_menu.period_create_list_delete"); ?></th>
				    </tr>
			    </thead>
				<tbody id="body_table">
				</tbody>
			</table>
		</div>
		<div class="col-md-12 form-group">
			<button class="form-group btn btn-primary" type="submit" id="submit"><?php echo getLabel("action.submit"); ?></button>
		</div>
	</div>
</div>

<?php include("../../footer.php"); ?>
