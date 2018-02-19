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

include ("../../header.php"); 
include("../../side.php");
?>

<div id="page-wrapper">

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header marge"><?php echo getLabel("label.kettle_apps.title"); ?></h1>
		</div>
	</div>

	<div id="message"></div>
	
	<form id="global">
		<div class="row form-group">
			<div class="col-md-6">
				<label><?php echo getLabel("label.kettle_apps.time_period_select"); ?></label>
				<div class="input-group input-validity-date">
					<input type="text" class="form-control" readonly id="validity_date">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
				<div id="startDate"></div>
				<div id="endDate"></div>
			</div>
		</div>
		<button class="form-group btn btn-primary" type="submit" id="launch"><?php echo getLabel("label.kettle_apps.launch"); ?></button>
	</form>

</div>

<?php include("../../footer.php"); ?>
