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
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.contract_context_title"); ?></h1>
		</div>
	</div>

	<div id="global_form"></div>

	<form>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-name">
					<label for="name"><?php echo getLabel("label.manage_contracts.contract_context_name_descr"); ?></label>
					<div class="input-name">
						<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
				</div>
			</div>	
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-desc">
					<label for="desc"><?php echo getLabel("label.manage_contracts.contract_context_descr"); ?></label>
					<div class="input-desc">
						<input type="text" class="form-control" id="desc" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
				</div>
			</div>	
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-contract">
					<label for="name_contract"><?php echo getLabel("label.manage_contracts.contract_context_contract"); ?></label>
					<div class="input-contract">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_contract"><?php echo getLabel("label.manage_contracts.contract_view_title"); ?> <span class="caret"></span></button>
						<ul class="dropdown-menu btn-block" id="ul_contract">
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-time">
					<label for="name_time_period"><?php echo getLabel("label.time_period"); ?></label>
					<div class="input-time">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_time_period"><?php echo getLabel("label.manage_contracts.time_period_view_title"); ?> <span class="caret"></span></button>
						<ul class="dropdown-menu btn-block" id="ul_time">
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-kpi">
					<label for="name_kpi"><?php echo getLabel("label.indicator"); ?></label>
					<div class="input-kpi">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_kpi"><?php echo getLabel("label.contracts_menu.context_contract_create_indicator_value_default"); ?> <span class="caret"></span></button>
						<ul class="dropdown-menu btn-block" id="ul_kpi">
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row form-group" id="display_step_group">
			<div class="col-md-6">
	            <div class="has-feedback">
                    <label for="name_step_group"><?php echo getLabel("label.manage_contracts.indicator_associate"); ?></label>
                    <div>
                        <button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_step_group"><?php echo getLabel("label.manage_contracts.sla_selection"); ?>
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu btn-block" id="ul_step_group"></ul>
                    </div>
	            </div>
	        </div>
	    </div>
		
		<div style="display:none">
			<input type="text" class="form-control" id="id_contract">
			<input type="text" class="form-control" id="id_time_period">
			<input type="text" class="form-control" id="id_kpi">
		    <input type="text" class="form-control" id="id_step_group">
	    </div>
		<div class="row">
			<div class="col-md-4">
				<button class="form-group btn btn-primary" type="submit" id="submit"><?php echo getLabel("action.submit"); ?></button>
				<button class="btn btn-default" type="button" name="back" value="back" onclick="location.href='contract_context_view.php'"><?php echo getLabel("action.cancel") ?>
				</button>
			</div>
		</div>
	</form>
</div>

<?php include("../../footer.php"); ?>
