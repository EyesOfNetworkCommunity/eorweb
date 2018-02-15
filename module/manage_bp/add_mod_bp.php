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
include("./function.php");

global $database_vanillabp;

$nick_name = getNickName($_GET["source"]);
	
	// ~~~~~~~~~~~~ TEST ~~~~~~~~~~~~ // 
	$type = mysqli_fetch_assoc(sqlrequest($_GET["source"],"SELECT type,min_value FROM bp WHERE name='$_GET[uname]'"));
	if ( $type['type'] == "MIN") {
		$return = sqlArrayDatabase($_GET["source"], "SELECT COUNT(bp_name) as nbr FROM bp_services WHERE bp_name='$_GET[uname]' UNION select COUNT(bp_name) FROM bp_links WHERE bp_name='$_GET[uname]'");
		if ( ($return[0]['nbr']+$return[1]['nbr']) >= $type['min_value']){
			$result = sqlrequest($_GET["source"],"UPDATE bp SET is_define='1' WHERE name='$_GET[uname]'");
		}
		else {
			$result = sqlrequest($_GET["source"],"UPDATE bp SET is_define='0' WHERE name='$_GET[uname]'");
		}
	}
	else {
		$result = sqlrequest($_GET["source"],"UPDATE bp SET is_define='1' WHERE name='$_GET[uname]'");
	}
	// ~~~~~~~~~~~~ TEST ~~~~~~~~~~~~ //
	
	$result = sqlrequest($_GET['source'],"SELECT '$_GET[source]' as source, name, description, priority, type,command,url,min_value,is_define FROM bp WHERE name='$_GET[uname]'");
	$metier = mysqli_fetch_assoc($result);
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-md-12">
			<h1 class="page-header"><?php echo getLabel("label.business.title"); ?></h1>
		</div>
	</div>
	<form action='./add_mod_bp.php' method='POST' name='form_bp'>
		<span id="output"></span>
		<div class="row">
			<div class="col-md-7">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo getLabel("Define BP"); ?>
					</div>
					<div class="panel-body">
						<div class="row form-group">
							<div class="form-group">
								<label style="font-weight:normal;" class="col-md-2 control-label">Source: </label>
								<div id="sourcedb" class="col-md-8"></div>
								<div class="col-md-2">
									<span>Use a list?</span>
									<input type="checkbox" id="sourceinput" onclick='javascript:selectValueSource("changesource",this.checked);'>
								</div>
							</div>
						</div>
						<?php if($_GET['source'] != $database_vanillabp) { ?>
						<div class="row form-group">
							<div class="form-group">
								<label style="font-weight:normal;" class="col-md-2 control-label">Equipment: </label>
								<div id="td" class="col-md-8"></div>
								<div class="col-md-2">
									<span>Use a list?</span>
									<input type='checkbox' id='check' onclick='javascript:selectValue("change",this.checked);'>
								</div>
							</div>
						</div>
						<div class="row form-group">
							<div class="form-group">
								<label style="font-weight:normal;" class="col-md-2 control-label">Service: </label>
								<div class="col-md-8">
									<select class="form-control" name='service[]' id="change"></select>
								</div>
								<div class="col-md-2">
									<input class='btn btn-primary' type='button' id='add' name='add' value='add' onclick='javascript:addValue("nobp");'>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="row form-group">
							<div class="form-group">
								<label style="font-weight:normal;" class="col-md-2 control-label">Process: </label>
								<div class="col-md-8">
									<div class="row">
										<div class="col-md-5 form-group">
											<select class="form-control" name="prio" id="prio" value="all" onChange="javascript:chooseDisplay(this.value);">
												<option value="all">Display All</option>
												<option value="null">No Display</option>
												<?php 
												global $max_display;
												global $display_zero;
												for ($i=((int)!$display_zero); $i < $max_display+1 ; $i++) { 
													echo "<option value='$i'>Display $i</option>";
												}
												?>
											</select>
										</div>
										<div class="col-md-7 form-group">
											<select class="form-control" name="proc[]" id="proc"></select>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<input class='btn btn-primary' type='button' id='addProc' name='add' value='add' onclick='javascript:addValue("bp");'>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-7">
				<div class="panel panel-default">
					<div class="panel-heading">
						Definition of : <?php echo "<span id=\"definitionNickName\">$nick_name</span><span id=\"definitionSource\" style=\"display: none;\">$metier[source]</span>::$metier[name] ; type : $metier[type]"; if( $metier['min_value'] != "") echo " $metier[min_value]";?>
					</div>
					<div class="panel-body">
						<div class="row form-group">
							<div class="col-md-6">
								<span>Source::Name;Service<br>Source::Process</span>
							</div>
							<div class="col-md-6">
								<span>Select</span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div id="sum"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-offset-9 col-md-1">
								<input class='btn btn-primary' type='button' name='del' value='delete' onclick='javascript:delValue();'>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<?php 
	$nbrServ = 0;
	$tabMetier = sqlArrayDatabase($_GET["source"],"SELECT '$_GET[source]' as source, name, description, priority, type,command,url,min_value,is_define FROM bp WHERE name NOT IN (SELECT bp_name FROM bp_links WHERE bp_link='$_GET[uname]')");
	$result = sqlArrayDatabase($_GET["source"],"SELECT '$_GET[source]' as source,id, bp_name, host, service FROM bp_services WHERE `bp_name`='$_GET[uname]'");
	$nbrServ += count($result)."<br>";
	
	$result = sqlArrayDatabase($_GET["source"],"SELECT '$_GET[source]' as source,id, bp_name, bp_link FROM bp_links WHERE `bp_name`='$_GET[uname]'");
	$nbrServ += count($result)."<br>";
?>

<script src="./function.js"></script>
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
	
<script>
	$("input[name=equip]").attr("disabled", true);
	$("#sourceinput").attr("checked", true);
	$("#check").attr("checked", true);
	selectValueSource("changesource", true);
	selectValue("change", true);
	setValues("<?php echo $_GET['uname']?>", <?php echo json_encode($tabMetier) ?>,"<?php echo $_GET['source']?>","<?php echo $nbrServ?>","<?php echo $metier['min_value'];?>");
	
	<?php 
	if ( $metier['is_define']){
		$result = sqlArrayDatabase($_GET['source'],"SELECT '$_GET[source]' as source,id, bp_name, host, service FROM bp_services WHERE bp_name='$_GET[uname]'");
		echo "setServ(".json_encode($result).");";
		if ( $_GET['source'] == $database_vanillabp ) {
			$result = sqlArrayDatabase($_GET['source'],"SELECT '$_GET[source]' as source,id, bp_name, bp_link, bp_source FROM bp_links WHERE bp_name='$_GET[uname]'");
		} else {
			$result = sqlArrayDatabase($_GET['source'],"SELECT '$_GET[source]' as source,id, bp_name, bp_link, '$_GET[source]' as bp_source  FROM bp_links WHERE bp_name='$_GET[uname]'");
		}
		echo "setProc(".json_encode($result).");";
	} 
	?>
	
	/* ~~~~~~~~~~ griser les champs quand SOURCE = vide ~~~~~~~~~~ */
	// les 2 champs sont disabled de base (tout en bas du code)
	$('body').bind("change", "input[name=source]", function(){
		var source_value = $("input[name=source]").val();
		if(source_value != "")
		{
			$("input[name=equip]").attr("disabled", false);
			$("#change").attr("disabled", false);
			$("#check").attr("disabled", false);
		}
		else
		{
			$("input[name=equip]").attr("disabled", true);
			$("#change").attr("disabled", true);
			$("#check").attr("disabled", true);
		}
	});
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
</script>

<?php
include("../../footer.php");
?>