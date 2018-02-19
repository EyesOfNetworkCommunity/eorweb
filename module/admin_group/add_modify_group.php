<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Jean-Philippe LEVY
# VERSION : 2.0
# APPLICATION : eorweb for eyesofnetwork project
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

<?php

// Check or uncheck input
function check_uncheck($form_name,$name,$checked,$value)
{
	global $defaulttab;
	
	echo "<div class='checkbox'>";
	echo "<label>";
	if ($form_name=="tab_".$defaulttab){
		echo "<input type='hidden' name='$form_name' value='$value'>";
		echo "<input type='checkbox' class='checkbox' checked disabled='disabled'>".getLabel($name);
	}
	elseif ($checked)
		echo "<input type='checkbox' class='checkbox' name='$form_name' value='$value' checked>".getLabel($name)."<br>";
	else
		echo "<input type='checkbox' class='checkbox' name='$form_name' value='$value'>".getLabel($name)."<br>";
		
	echo "</label>";
	echo "</div>";
}

// get all menu_tab info (create an array with all names)
function retrieve_menu () {
	
	global $menus;

	$array_tabs = array();
	foreach($menus["menutab"] as $menutab) {
		array_push($array_tabs, $menutab["name"]);
	}
	
	return $array_tabs;
	
}

// Retrieve allowed menu for a group_id and build checkbox
function retrieve_allowed_menu($array_tabs,$group_id)
{
	global $database_eorweb;
	
	$count_item=count($array_tabs);

	$sql_req = "SELECT ";
	for ($i=1;$i < $count_item;$i++)
	{
		$sql_req = "$sql_req tab_$i,";
	}
	$sql_req = "$sql_req tab_$i";
	
	$sql_req = "$sql_req FROM groupright";
	if ($group_id !="")
		$sql_req = "$sql_req WHERE group_id='$group_id'";

	$grp_right_result=sqlrequest("$database_eorweb","$sql_req");
	
	$check =1;
	if ($group_id !=null)
	{
		for ($i=1;$i < $count_item +1;$i++)
		{
			check_uncheck("tab_$i",$array_tabs[$i-1],mysqli_result($grp_right_result,0,"tab_$i"),1);
		}
	}
	else
	{
		for ($i=1;$i < $count_item +1;$i++)
		{
			check_uncheck("tab_$i",$array_tabs[$i-1],0,1);
		}
	}
	
	return $count_item;
	
}

// Retrieve Group Information
function retrieve_group_info($group_id)
{
	global $database_eorweb;
	return sqlrequest("$database_eorweb","SELECT group_name, group_descr, group_type, group_dn FROM groups WHERE group_id='$group_id'");
}

// Update Group Information & Right
function update_group($count_menu_item,$group_id,$group_name,$group_descr,$group_type,$ldap_group_name,$message,$old_group=false)
{
	global $database_eorweb;
	global $database_lilac;

	if(!$group_name)
	{
		$group_name = $ldap_group_name;
	}
	
	// Check if group exist
	if($group_name!=$old_group)
			$group_exist=mysqli_result(sqlrequest("$database_eorweb","SELECT count('group_name') from groups where group_name='$group_name';"),0);
	else
			$group_exist=0;

	// Check group descr
	if($group_descr=="")
			$group_descr=$group_name;
	
	if (($group_id != "") && ($group_id != null) && ($group_name != "") && ($group_name != null) && ($group_exist == 0 || $old_group==false))
	{
		for ($i=1;$i<$count_menu_item +1;$i++)
		{
			if (isset ($_POST["tab_$i"]))
				sqlrequest("$database_eorweb","UPDATE groupright set tab_$i='1' where group_id='$group_id'");
			else
				sqlrequest("$database_eorweb","UPDATE groupright set tab_$i='0' where group_id='$group_id'");
		}
		
		// get the DN of the ldap group !
		$group_dn = "";
		$group_ldap=sqlrequest("$database_eorweb","SELECT dn from ldap_groups_extended where group_name='$group_name';");
		if(mysqli_num_rows($group_ldap) > 0){
			$group_dn = mysqli_result($group_ldap, 0);
		}
		if($group_dn == ""){
			$group_type = 0;
		}
		
		// Update into eorweb
		sqlrequest("$database_eorweb","UPDATE groups set group_name='$group_name', group_descr='$group_descr', group_type='$group_type', group_dn='$group_dn' where group_id='$group_id'");
		// Update into lilac
		sqlrequest("$database_lilac", "UPDATE nagios_contact_group SET name='$group_name', alias='$group_descr' WHERE name='$old_group'");
		logging("admin_group","UPDATE : $group_id $group_name $group_descr");
		if($message){ message(8," : Group updated",'ok'); }
	}
	elseif($group_exist != 0)
		message(8," : Group $group_name already exists",'warning');
	else
		message(8," : Group name can not be empty",'warning');
}

// Insert Group Information
function insert_group($group_name,$group_descr,$group_type,$ldap_group_name)
{
	global $database_eorweb;
	global $database_lilac;
	$group_id=null;

	// Check if group exist
	if(!$group_name)
	{
		$group_name = $ldap_group_name;
	}
	$group_exist=mysqli_result(sqlrequest("$database_eorweb","SELECT count('group_name') from groups where group_name='$group_name';"),0);
	
	// Check group descr
	if($group_descr=="")
		$group_descr=$group_name;
	
	if (($group_name != "") && ($group_name != null) && ($group_exist == 0))
	{
		// get the DN of the ldap group !
		$group_dn = "";
		$group_ldap=sqlrequest("$database_eorweb","SELECT dn from ldap_groups_extended where group_name='$group_name';");
		if(mysqli_num_rows($group_ldap) > 0){
			$group_dn = ldap_escape(mysqli_result($group_ldap,0));
		}
		if($group_dn == ""){
			$group_type = 0;
		}
		
		// Insert into eorweb
		sqlrequest("$database_eorweb","INSERT INTO groups (group_name,group_descr,group_type,group_dn) VALUES('$group_name', '$group_descr', '$group_type', '$group_dn')");
		$group_id=mysqli_result(sqlrequest("$database_eorweb","SELECT group_id, group_descr FROM groups WHERE group_name='$group_name'"),0,"group_id");
		sqlrequest("$database_eorweb","INSERT INTO groupright (group_id) VALUES('$group_id')");
		// Insert into lilac
		sqlrequest("$database_lilac", "INSERT INTO nagios_contact_group (id, name, alias) VALUES('', '$group_name', '$group_descr')");
		logging("admin_group","INSERT : $group_id $group_name $group_descr $group_type");
		message(8," : Group inserted",'ok');
	}
	elseif($group_exist != 0)
		message(8," : Group $group_name already exists",'warning');
	else
		message(8," : Group name can not be empty",'warning');
	
	return $group_id;
}

// Get tab
$array_tabs=retrieve_menu();
$count_menu_item=count($array_tabs);

// Get parameter
$group_id = retrieve_form_data("group_id",null);
$group_name = mysqli_result(sqlrequest("$database_eorweb","SELECT group_name FROM groups WHERE group_id='$group_id'"),0,"group_name");
$group_descr = mysqli_result(sqlrequest("$database_eorweb","SELECT group_descr FROM groups WHERE group_id='$group_id'"),0,"group_descr");
$group_type = mysqli_result(sqlrequest("$database_eorweb","SELECT group_type FROM groups WHERE group_id='$group_id'"),0,"group_type");
$group_location = mysqli_result(sqlrequest("$database_eorweb","SELECT group_name FROM groups WHERE group_id='$group_id'"),0,"group_name");

if ($group_id == null) 
{
	echo '<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">'.getLabel("label.admin_group.title_new").'</h1>
				</div>
			</div>';
	if 	(isset($_POST['add']))
	{
		$group_name = retrieve_form_data("group_name",null);
		$group_descr = retrieve_form_data("group_descr","");
		$group_type = retrieve_form_data("group_type", "");
		$ldap_group_name = retrieve_form_data("group_location", "");
		$group_id=insert_group($group_name,$group_descr,$group_type,$ldap_group_name);
		if ($group_id != null)
			update_group($count_menu_item,$group_id,$group_name,$group_descr,$group_type,$ldap_group_name, false);
	}
}
else
{
	echo '<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">'.getLabel("label.admin_group.title_upd").'</h1>
				</div>
			</div>';
	if 	(isset($_POST['update']))
	{	
		$old_group = $group_name;
		$group_name = retrieve_form_data("group_name",null);
		$group_descr = retrieve_form_data("group_descr","");
		$group_type = retrieve_form_data("group_type", "");
		$ldap_group_name = retrieve_form_data("group_location", "");
		update_group($count_menu_item,$group_id,$group_name,$group_descr,$group_type,$ldap_group_name,true,$old_group);
	}
}

// Retrieve Group Information from database
$group_name_descr = retrieve_group_info($group_id);
$group_name=mysqli_result($group_name_descr,0,"group_name");
$group_descr=mysqli_result($group_name_descr,0,"group_descr");
$group_type=mysqli_result($group_name_descr,0,"group_type");
$group_location=mysqli_result($group_name_descr,0,"group_name");
if($group_type == 0){
	$group_location = "";
}
?>

	<form class="form" action='./add_modify_group.php' method='POST' name="form_group">
		<input type='hidden' name='group_id' value='<?php echo $group_id?>'>
		
		<div class="row">
			<label class="col-md-3"><?php echo getLabel("label.admin_group.group_name"); ?></label>
			<div class="col-md-9">
				<?php echo "<input class='form-control' type='text' name='group_name' value='$group_name' ";
				if($group_type==1){echo "disabled='disabled'";}
				echo " />"; ?>
			</div>
		</div>
		<br>
		<div class="row">
			<label class="col-md-3"><?php echo getLabel("label.admin_group.ldap_group"); ?></label>
			<div class="col-md-9">
				<?php
					if($group_type=="1") $checked="checked='checked'";
					else $checked="";
					echo "<input type='checkbox' class='checkbox' name='group_type' value='1' $checked onclick='disable()'>";
				?>
			</div>
		</div>
		<br>
		<div class="row">
			<label class="col-md-3"><?php echo getLabel("label.admin_group.dn_group"); ?></label>
			<div class="col-md-9">
				<?php
					echo "<input class='form-control' id='group_location' name='group_location' type='text' value='".htmlspecialchars($group_location, ENT_QUOTES)."' ";
					if($group_type==0){echo "disabled='disabled'";}
					echo " />";
				?>
			</div>
		</div>
		<br>
		<div class="row">
			<label class="col-md-3"><?php echo getLabel("label.admin_group.group_desc"); ?></label>
			<div class="col-md-9">
				<?php echo "<input class='form-control' type='text' name='group_descr' value='$group_descr' size=50>";?>
			</div>
		</div>
		<br>
		<div class="row">
			<label class="col-md-3"><?php echo getLabel("label.admin_group.rights"); ?></label>
			<div class="col-md-9">
			<?php retrieve_allowed_menu($array_tabs,$group_id); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
			<?php
			if ($group_id !=null) {
				echo "<button class='btn btn-primary' type='submit' name='update' value='update'>".getLabel("action.update")."</button>";
			}
			else {
				echo "<button class='btn btn-primary' type='submit' name='add' value='add'>".getLabel("action.add")."</button>";
			}
			echo "<button class='btn btn-default' style='margin-left: 10px;' type='button' name='back' value='back' onclick='location.href=\"index.php\"'>".getLabel("action.cancel")."</button>";
			?>
			</div>
		</div>
		<br>
	</form>

</div>
	
<?php include("../../footer.php"); ?>
