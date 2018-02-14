<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Michael Aubertin
# VERSION 4.2
# APPLICATION : eorweb for Vanilla4eyesofnetwork project
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

<script src="./function.js"></script>

<?php
	$bp_source=retrieve_form_data("source_name",null);
	$bp_uname=retrieve_form_data("unique_name",null);
	$bp_name=retrieve_form_data("process_name",null);
	$bp_prio=retrieve_form_data("process_prio",null);
	$bp_url=retrieve_form_data("process_url",null);
	$bp_cmd=retrieve_form_data("process_cmd",null); 
	$bp_type=retrieve_form_data("process_type",null);
	$bp_type_min=retrieve_form_data("process_type_min",null);
	$action=retrieve_form_data("add",null);

	// verification des valeurs recus (si "ok" on passe sur add_mod_bp.php sinon message d'erreur)
	switch ($action){
		case "add" :
			if ($bp_uname == "") {
				echo "<div id=\"message\"class=\"row\">";
				message(11,"Unique name not set !", "critical");
				echo "</div>";
			} else {
				$current_request = "INSERT INTO bp VALUES('$bp_uname','$bp_name','$bp_prio','$bp_type','$bp_cmd','$bp_url','$bp_type_min','')";
				$return = sqlrequest($bp_source,$current_request);
				if ( $return ) {
					echo "<script>document.location.replace(\"add_mod_bp.php?uname=$bp_uname&source=$bp_source\");</script>";
				} else {
					echo "<div id=\"message\"class=\"row\">";
					message(0,$current_request ,"critical");
					echo "</div>";
				}
			}
			break ;
		case "modify" :
			if ($bp_uname == "") {
				echo "<div id=\"message\"class=\"row\">";
				message(11,"Unique name not set !", "critical");
				echo "</div>";
			} else {
				$sqlrequest="UPDATE bp SET name='$bp_uname',description='$bp_name',priority='$bp_prio',type='$bp_type',command='$bp_cmd',url='$bp_url',min_value='$bp_type_min' WHERE name='$bp_uname'";
				$return = sqlrequest($bp_source,$sqlrequest);
				if ( $return ) {
					echo "<script>document.location.replace(\"add_mod_bp.php?uname=$bp_uname&source=$bp_source\");</script>";
				} else {
					echo "<div id=\"message\"class=\"row\">";
					message(0,": Could not update Database $bp_source","critical");
					echo "</div>";
				}
			}
			break ;
	}


global $max_display;
global $display_zero;

$select_disabled = "";
if(isset($_GET["source"])){
	$select_disabled = "disabled";
}

if(isset($_GET["name"])) {
	echo "<div id=\"page-wrapper\"><div class=\"row\"><div class='col-lg-12'><h1 class=\"page-header\">Modify</h1></div></div>";
} else {
	echo "<div id=\"page-wrapper\"><div class=\"row\"><div class='col-lg-12'><h1 class=\"page-header\">Add</h1></div></div>";
}

?>
	<div class="col-md-6">
		<form class="form-horizontal" action='./add_process.php' method='POST' name='form_bp'>
			<div class="row">
				<div class="form-group">
					<label style="font-weight:normal;" class="col-md-4 control-label">Source db</label>
					<div class="col-md-7">
						<?php
						echo "<select class=\"form-control\" name=\"source_name\"".$select_disabled.">";
						$db_list = sqlrequest("global_nagiosbp","SELECT db_names, nick_name FROM bp_sources");
						while ($row = mysqli_fetch_array($db_list)) {
							$selected = "";
							if(isset($_GET["source"]) && $_GET["source"] == $row[1]) { 
								$selected = "selected=\"selected\""; 
							}
							echo "<option value=\"".$row[0]."\" ".$selected.">". $row[1] . "</option>";
						}
						echo "</select>";
						?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<label style="font-weight:normal;" class="col-md-4 control-label">Unique Name</label>
					<div class="col-md-7">
						<input class="form-control" name="unique_name" <?php if(isset($_GET["name"])) echo "value=\"$_GET[name]\" disabled";?> onFocus="javascript:clean(this);"/>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group">
					<label style="font-weight:normal;" class="col-md-4 control-label">Display</label>
					<div class="col-md-7">
						<select class="form-control" size="1" name="process_prio" onChange="javascript:isDisp(this);">
							<option value="null">None</option>
							<?php 
							for ($i=((int)!$display_zero); $i < $max_display+1 ; $i++) { 
								echo "<option value=\"$i\">$i</option>";
							} ?>
						</select>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<label style="font-weight:normal;" class="col-md-4 control-label">Process Name</label>
					<div class="col-md-7">
						<input class="form-control" name="process_name" disabled value="" onChange="javascript:checkName(this)"/>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<label style="font-weight:normal;" class="col-md-4 control-label">URL</label>
					<div class="col-md-7">
						<input class="form-control" name="process_url" disabled value="" onChange="javascript:checkURL(this);"/>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<label style="font-weight:normal;" class="col-md-4 control-label">Command</label>
					<div class="col-md-7">
						<input class="form-control" name="process_cmd" disabled value="" onChange="javascript:checkCommand(this);"/>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<label style="font-weight:normal;" class="col-md-4 control-label">Type</label>
					<div class="col-md-7">
						<select class="form-control" name="process_type" onchange="javascript:ismin(this);">
							<option value="et">et</option>
							<option value="ou">ou</option>
							<option value="minimum">minimum</option>
						</select>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<label style="font-weight:normal;" class="col-md-4 control-label">Minimum value</label>
					<div class="col-md-7">
						<select class="form-control" name='process_type_min' disabled>
							<?php
							for ($i = 1 ; $i < 10 ; $i++){
								echo "<option value=\"$i\">$i</option>";
							}
							?>
						</select>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-offset-6">
					<!-- The onClick will be execute before the submit. So we can get the value of unique_name field by enabling it.-->
					<input class="btn btn-primary" type="submit" name="add" id="add" value="add" onclick="javascript:enable();">
					<input class="btn btn-primary" type="button" name="back" value="back" onclick="location.href='index.php'">
				</div>
			</div>
		</form>
	</div>
</div>

<script>	
	//If the unique name field is not empty or egal to a default value, we check for an existing name. (Prevent for previous , etc..)
	if (document.form_bp.source_name.value != "" && document.form_bp.source_name.value != "Choose a name")
	{
		if (document.form_bp.unique_name.value != "" && document.form_bp.unique_name.value != "Choose a name")
			exist(document.form_bp.unique_name.value,document.form_bp.source_name.value);
	}
	else state("#add",0);
	setTimeout(function(){ismin(document.form_bp.process_type);}, 200);
</script>

<?php
include("../../footer.php");
?>