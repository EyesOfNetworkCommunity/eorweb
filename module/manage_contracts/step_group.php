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
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.step_group_title"); ?></h1>
		</div>
	</div>

	<div id="global_form"></div>

	<form>
		<div class="row form-group">
			<div class="col-md-6">
				<label for="name"><?php echo getLabel("label.contracts_menu.seuils_create_name"); ?></label>
				<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
			</div>
		</div>

		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback">
					<label for="name_kpi"><?php echo getLabel("label.contracts_menu.seuils_create_indicator"); ?></label>
					<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_kpi"><?php echo getLabel("label.contracts_menu.context_contract_create_indicator_value_default"); ?>
					<span class="caret"></span></button>
					<ul class="dropdown-menu btn-block" id="ul_kpi"></ul>
				</div>
			</div>
		</div>
		<div class="row form-group has-feedback" id="display_unit_checkbox" style="display:none">
			<div class="col-md-6">
				<label><?php echo getLabel("label.contracts_menu.seuils_create_type"); ?></label>
				<div class="col-md-12">
					<label class="radio-inline" id="label_unit_kpi" for="unit_kpi">
						<input type="radio" name="optradio" id="unit_kpi" onchange="CheckRadioButton(id)">
					</label>
					<label class="radio-inline" id="label_unit_ratio" for="unit_ratio">
						<input type="radio" name="optradio" id="unit_ratio" onchange="CheckRadioButton(id)">Ratio (%)
					</label>
				</div>
			</div>
		</div>
		<div class="row form-group has-feedback" style="display:none" id="display_interval">
				<div class="row">
					<div class="col-md-3">
						<label><?php echo getLabel("label.contracts_menu.seuils_create_minimum"); ?></label>
						<input type="text" class="form-control" id="interval_min" onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')">
					</div>
					<div class="col-md-3">
						<label><?php echo getLabel("label.contracts_menu.seuils_create_maximum"); ?></label>
						<input type="text" class="form-control" id="interval_max" onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')">
					</div>
				</div>
		</div>
		<div class="form-group">
			<button class="btn btn-primary" type="submit" id="submit_interval"><?php echo getLabel("label.contracts_menu.seuils_create_btn_add"); ?>
			</button>
		</div>
		
		<input type="text" class="form-control" id="id_kpi" style="display:none">
		
		<div class="row" style="display:none" id="text_entry">
			<div class="col-md-12">
				<h2 class="page-header"><?php echo getLabel("label.contracts_menu.seuils_create_title_list"); ?></h2>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<table class="table" style="display:none" id="container_interval">
				    <thead>
					    <tr>
					        <th><?php echo getLabel("label.contracts_menu.seuils_create_list_sla"); ?></th>
							<th><?php echo getLabel("label.contracts_menu.seuils_create_minimum"); ?></th>
							<th><?php echo getLabel("label.contracts_menu.seuils_create_maximum"); ?></th>
							<th><?php echo getLabel("action.delete"); ?></th>
					    </tr>
				    </thead>
					<tbody id="body_table">
					</tbody>
				</table>
			</div>
		</div>
		<div class="row" id="hidden_button" style="display: none;">
			<div class="col-md-12">
				<button class="form-group btn btn-primary" type="submit" id="submit"><?php echo getLabel("action.submit"); ?></button>
				<button class="btn btn-default" type="button" name="back" value="back" onclick="location.href='step_group_view.php'"><?php echo getLabel("action.cancel") ?></button>
			</div>
		</div>
	</form>
</div>


<?php include("../../footer.php"); ?>


