<?php
/*
#########################################
#
# Copyright (C) 2014 EyesOfNetwork Team
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
?>
<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <?php include("../../include/include_module.php"); ?>
        <script type="text/javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" src="function.js"></script>
</head>
<body id="main">

<?php
	//Escape special chars (",',etc..)
	//Encode for the URL. (&,+,etc..)
	$bp_source=retrieve_form_data("source_name",null);
	$bp_uname=retrieve_form_data("unique_name",null);
	$bp_name=retrieve_form_data("process_name",null);
	$bp_prio=retrieve_form_data("process_prio",null);
	$bp_url=retrieve_form_data("process_url",null);
	$bp_cmd=retrieve_form_data("process_cmd",null); 
	$bp_type=retrieve_form_data("process_type",null);
	$bp_type_min=retrieve_form_data("process_type_min",null);
	$action=retrieve_form_data("add",null);

	switch ($action){
		case "add" :
      $current_request = "INSERT INTO bp VALUES('$bp_uname','$bp_name','$bp_prio','$bp_type','$bp_cmd','$bp_url','$bp_type_min','')";
      //print $current_request;
			$return = sqlrequest($bp_source,$current_request);
			if ( $return ) {
				echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT='0;URL=add_mod_bp.php?uname=$bp_uname&source=$bp_source'>";
				header("Location: add_mod_bp.php?uname=$bp_uname&source=$bp_source");
				exit();
			}
			else {
				//message(0,": Could not insert in Database $bp_source or this BP Name already exist","critical");
				message(0,$current_request ,"critical");
				}
			break ;
		case "modify" :
			$sqlrequest="UPDATE bp SET `name`='$bp_uname',`description`='$bp_name',`priority`='$bp_prio',`type`='$bp_type',`command`='$bp_cmd',`url`='$bp_url',`min_value`='$bp_type_min' WHERE `name`='$bp_uname'";
			$return = sqlrequest($bp_source,$sqlrequest);
			if ( $return ) {
				echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT='text/html; charset=UTF-8;URL=add_mod_bp.php?uname=$bp_uname&source=$bp_source'>";
				header("Location: add_mod_bp.php?uname=$bp_uname&source=$bp_source");
				exit();
			}
			else message(0,": Could not update Database $bp_source","critical");
			break ;
	}
?>

<h1>
	<?php
		if(isset($_GET["name"])) 
			echo $xmlmodules->getElementsByTagName("manage_bp")->item(0)->getAttribute("mod");
		else
			echo $xmlmodules->getElementsByTagName("manage_bp")->item(0)->getAttribute("new");
	?>
</h1>
<?php
	global $max_display;
	global $display_zero;
	
	/*$link = mysql_connect('localhost', 'root', 'root66');
								$db_selected = mysql_select_db('global_nagiosbp', $link);
								$db_list = mysql_query("SELECT nick_name FROM bp_sources");
								var_dump($db_list);
								while ($row = mysql_fetch_array($db_list)) {
									//echo "<option>". $row[0] . "</option>";
									echo "<pre>";
									var_dump($row);
									echo "</pre>";
								}*/
								
	/*echo "<pre>";
	var_dump($_GET);
	echo "<pre>";*/
?>
	<form action='./add_process.php' method='POST' name='form_bp'>
		<input type='hidden' name='serv' value=''>
		<p id="output"></p>
		<center>
			<table class="table">
				<tr>
					<td><h2>Source db</h2></td>
					<!--td>
						<input type='textbox' name='source_name' <?php if(isset($_GET["source"])) echo "value='$_GET[source]' disabled";?> style="width:300px;" onFocus="javascript:clean(this);" onblur="javascript:refill(this,document.form_bp.source_name.value)"/>
					</td-->
					<td>
						<?php
							$select_disabled = "";
							if(isset($_GET["source"]))
							{
								$select_disabled = "disabled";
							}
							echo '<select name="source_name" style="width: 300px;" '.$select_disabled.'>';
							$db_list = sqlrequest("global_nagiosbp","SELECT db_names, nick_name FROM bp_sources");

							while ($row = mysql_fetch_array($db_list))
							{
								$selected = "";
								if(isset($_GET["source"]) && $_GET["source"] == $row[1]) { $selected = 'selected="selected"'; }
								echo "<option value=\"".$row[0]."\" ".$selected.">". $row[1] . "</option>";
							}
							echo "</select>";
						?>
					</td>
				</tr>
				<tr>
				<tr>
					<td><h2>Unique Name</h2></td>
					<td>
						<!--input type='textbox' name='unique_name' <?php if(isset($_GET["name"])) echo "value='$_GET[name]' disabled";?> style="width:300px;" onFocus="javascript:clean(this);" onblur="javascript:refill(this,document.form_bp.unique_name.value)"/-->
						<input type='textbox' name='unique_name' <?php if(isset($_GET["name"])) echo "value='$_GET[name]' disabled";?> style="width:300px;" onFocus="javascript:clean(this);"/>
					</td>
				</tr>
				<tr>
					<td><h2>Process Name</h2></td>
					<td>
						<input type='textbox' name='process_name' disabled value='' style="width:300px;" onChange="javascript:checkName(this)"/>
					</td>
				</tr>
				<tr>
					<td><h2>Display</h2></td>
					<td>
						<select size="1" name="process_prio" onChange="javascript:isDisp(this);">
							<option value="null">None</option>
							<?php for ($i=((int)!$display_zero); $i < $max_display+1 ; $i++) { 
								echo "<option value='$i'>$i</option>";
							}?>
						</select>
					</td>
				</tr>
				<tr>
					<td><h2>URL</h2></td>
					<td>
						<input type='textbox' name='process_url' disabled value='' style="width:300px;" onChange="javascript:checkURL(this);"/>
					</td>
				</tr>
				<tr>
					<td><h2>Command</h2></td>
					<td>
						<input type='textbox' name='process_cmd' disabled value='' style="width:300px;" onChange="javascript:checkCommand(this);"/>
					</td>
				</tr>
				<tr>
					<td><h2>Type</h2></td>
					<td>
						<select size="1" name="process_type" onchange="javascript:ismin(this);">
							<option value="ET" selected>ET</option>
							<option value="OU">OU</option>
							<option value="MIN">MINIMUN</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><h2>Minimum Value</h2></td>
					<td>
						<select name='process_type_min' disabled>
							<?php
							for ($i = 1 ; $i < 10 ; $i++){
								echo "<option value='$i'>$i</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="blanc" align="center" colspan="2">
						<!-- The onClick will be execute before the submit. So we can get the value of unique_name field by enabling it.-->
						<input class='button' type='submit' name='add' id='add' value='add' onclick='javascript:enable();'>
						<input class='button' type='button' name='back' value='back' onclick='location.href="index.php"'>
					</td>
				</tr>
			</table>
		</center>
	</form>
<script type="text/javascript">
	
	
	//If the unique name field is not empty or egal to a default value, we check for an existing name. (Prevent for previous , etc..)
	if (document.form_bp.source_name.value != "" && document.form_bp.source_name.value != "Choose a name")
	{
		if (document.form_bp.unique_name.value != "" && document.form_bp.unique_name.value != "Choose a name")
			exist(document.form_bp.unique_name.value,document.form_bp.source_name.value);
	}
	else state("#add",0);
	setTimeout(function(){ismin(document.form_bp.process_type);}, 200);
</script>
</body>
</html>
