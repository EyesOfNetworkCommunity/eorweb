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
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.company_title"); ?></h1>
		</div>
	</div>

	<div id="global_form"></div>
	<form>
		<div class="row form-group">
			<div class="col-md-6">
				<label><?php echo getLabel("label.manage_contracts.company_name"); ?></label>
				<div class="input-name">
					<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
				</div>
			</div>
		</div>
		<button class="form-group btn btn-primary" type="submit" id="submit"><?php echo getLabel("action.submit"); ?></button>
		<button class="btn btn-default" type="button" name="back" value="back" onclick="location.href='company_view.php'"><?php echo getLabel("action.cancel") ?></button>
	</form>
</div>

<?php include("../../footer.php"); ?>
