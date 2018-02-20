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
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.contract_context_application_title"); ?></h1>
		</div>
	</div>

	<div id="global_form"></div>
	
	<form>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-context">
					<label for="name_contract_context"><?php echo getLabel("label.contract_context"); ?></label>
					<div class="input-context">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_contract_context"><?php echo getLabel("label.manage_contracts.contract_context_view_title"); ?>
						<span class="caret"></span></button>
						<ul class="dropdown-menu btn-block" id="ul_context"></ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback">
					<label for="application_name"><?php echo getLabel("label.application"); ?></label>
					<div class="">
					<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="application_name"><?php echo getLabel("label.manage_contracts.contract_context_select_application"); ?>
					<span class="caret"></span></button>
					<ul class="dropdown-menu btn-block" id="ul_application"></ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<button class="btn btn-primary" type="submit" id="submit_entry"><?php echo getLabel("label.contracts_menu.application_menu_create_btn_add"); ?></button>
				<button class="btn btn-default" type="button" name="back" value="back" onclick="location.href='contract_context_application_view.php'"><?php echo getLabel("action.cancel") ?></button>
			</div>
		</div>
	</form>
	<div id="application_list" class="row" style="display: none;">
		<div class="col-md-12">
			<div class="form-group">
				<h2 class="page-header"><?php echo getLabel("label.contracts_menu.application_menu_create_title_list"); ?></h2>
			</div>
		</div>
		<div class="col-md-6">
			<div class="row form-group">
				<div class="col-md-12">
					<table class="table table-striped table-hover" id="container_application">
						<thead>
							<tr>
								<th><?php echo getLabel("menu.link.app"); ?></th>
								<th><?php echo getLabel("action.delete"); ?></th>
							</tr>
						</thead>
						<tbody id="body_table">
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<button class="form-group btn btn-primary " type="submit" id="submit"><?php echo getLabel("action.submit"); ?>
					</button>
				</div>
			</div>
		</div>
		<div class="col-md-6" style="display:none">
			<input type="text" class="form-control" id="id_contract_context">
		</div>
		<div class="col-md-6" style="display:none">
			<input type="text" class="form-control" id="application_name_hide">
		</div>
	</div>
</div>

<?php include("../../footer.php"); ?>
