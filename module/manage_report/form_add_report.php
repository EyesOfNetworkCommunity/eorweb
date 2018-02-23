<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Michael Aubertin
# VERSION 2.0
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
			<h1 class="page-header"><?php echo getLabel("label.form_report.name");?></h1>
		</div>
	</div>
	
	<div  id="message"></div>

	<div class="row form-group">
		<div class="col-md-6">
			<label for="rpt_name"><?php echo getLabel("label.form_report_name.name");?></label>
			<input type="text" class="form-control" id="rpt_name">
		</div>
	</div>
	
	<div class="row form-group">
		<div class="col-md-6">
			<label for="rpt_filename"><?php echo getLabel("label.form_report_name.file");?></label>
			<input type="text" class="form-control" id="rpt_filename">
		</div>
	</div>
	
	<button class="btn btn-sm btn-primary" id="AddButton_form"><?php echo getLabel("label.manage_report.add_report");?></button>
	<button class="btn btn-default" type="button" name="back" value="back" onclick="location.href='index.php'"><?php echo getLabel("action.cancel") ?></button>

</div>

<?php include("../../footer.php"); ?>
