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
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.contract_title"); ?></h1>
		</div>
	</div>

	<div id="global_form"></div>
	
		<form>
			<div class="row form-group">
				<div class="col-md-6">
					<label for="name"><?php echo getLabel("label.contracts_menu.contracts_menu_create_name"); ?></label>
					<div class="input-name">
						<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6 has-feedback div-desc">
					<label for="desc"><?php echo getLabel("label.contracts_menu.contracts_menu_create_description"); ?></label>
					<div class="input-desc">
						<input type="text" class="form-control" id="desc" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
				</div>	
			</div>
			<div class="row form-group">
				<div class="col-md-6 has-feedback div-sdm-intern">
					<label for="contract_sdm_intern"><?php echo getLabel("label.contracts_menu.contracts_menu_create_sdm_internal"); ?></label>
					<div class="input-sdm-intern">
						<input type="text" class="form-control" id="contract_sdm_intern" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6 has-feedback div-sdm-extern">
					<label for="contract_sdm_extern"><?php echo getLabel("label.contracts_menu.contracts_menu_create_sdm_external"); ?></label>
					<div class="input-sdm-extern">
						<input type="text" class="form-control" id="contract_sdm_extern" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6">
					<div class="has-feedback div-company">
						<label for="name_company"><?php echo getLabel("label.contracts_menu.contracts_menu_display_tab_company"); ?></label>
						<div class="cinput-company">
							<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_company"><?php echo getLabel("label.manage_contracts.company_view_title"); ?>
							<span class="caret"></span></button>
							<ul class="dropdown-menu btn-block" id="ul_company">
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6 has-feedback div-extern-id">
					<label for="extern_contract_id"><?php echo getLabel("label.contracts_menu.contracts_menu_create_reference"); ?></label>
					<div class="input-extern-id">
						<input type="text" class="form-control" id="extern_contract_id" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6 has-feedback div-validity-date">
					<label for="validity_date"><?php echo getLabel("label.contracts_menu.contracts_menu_create_date"); ?></label>
					<div class="input-validity-date input-group" id="datepicker">
						<input type="text" class="form-control" readonly id="validity_date">
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<input type="text" class="form-control" id="id_company" style="display:none">
				</div>
			</div>
			
			<button class="form-group btn btn-primary" type="submit" id="submit"><?php echo getLabel("action.submit"); ?></button>
			<button class="btn btn-default" type="button" name="back" value="back" onclick="location.href='contract_view.php'"><?php echo getLabel("action.cancel") ?></button>
		</form>
</div>

<?php include("../../footer.php"); ?>
