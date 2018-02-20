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
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.help_about.title")." $version"; ?></h1>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo getLabel("label.help_about.bundle"); ?>
		</div>
		<div class="panel-body">
			<p><?php echo getLabel("label.help_about.bundle1"); ?></p>
			<p><?php echo getLabel("label.help_about.bundle2"); ?></p>
			<p><?php echo getLabel("label.help_about.bundle3"); ?></p>
			<br>
			<p><?php echo getLabel("label.help_about.bundle4")." : "; ?></p>
			<ul class="list-group">
				<li class="list-group-item"><?php echo getLabel("label.help_about.bundle_val1"); ?></li>
				<li class="list-group-item"><?php echo getLabel("label.help_about.bundle_val2"); ?></li>
				<li class="list-group-item"><?php echo getLabel("label.help_about.bundle_val3"); ?></li>
				<li class="list-group-item"><?php echo getLabel("label.help_about.bundle_val4"); ?></li>
			</ul>
		</div>
	</div>

	<div class="panel panel-info">
		<div class="panel-heading">
			<i class="fa fa-info-circle"></i>
			<?php echo getLabel("label.help_about.contact"); ?>
		</div>
		<div class="panel-body">
			<p><?php echo getLabel("label.help_about.contact1"); ?> <a href="https://github.com/EyesOfNetworkCommunity/eyesofreport/issues" target="_blank"> <?php echo getLabel("label.help_about.contact_mail"); ?> </a></p>
		</div>
	</div>

</div>

<?php include("../../footer.php"); ?>
