<?php
/*
#########################################
#
# Copyright (C) 2014 EyesOfNetwork Team
# DEV NAME : Michael Aubertin
# VERSION 4.2
# APPLICATION : eonweb for Vanilla4eyesofnetwork project
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
        <?php
			include("../../include/include_module.php");
		?>
        

</head>
<?php
/* ~~~~~~~~~~ FUNCTIONS ~~~~~~~~~~ */
	/**
	 * Récupère le nick_name d'une source.
	 *
	 * @param $db_name (String) -> Nom de la source.
	 *
	 * @return (String) -> Le nick_name de la source.
	 */
	function getNickName($db_name)
	{
		global $database_vanillabp;

		$result = sqlrequest($database_vanillabp, "SELECT nick_name FROM bp_sources WHERE db_names='$db_name'");
		while ($line = mysql_fetch_array($result)){
			$nick_name=$line[0];
        }

		return $nick_name;
	}

	/**
	 * Récupère le thruk_idx d'une source.
	 *
	 * @param $source_name (String) -> Nom de la source choisie.
	 *
	 * @return (String) -> Le thruk_idx associé à la source.
	 */
	function getThrukId($source_name)
	{
		global $database_vanillabp;

		$result = sqlrequest($database_vanillabp, "SELECT thruk_idx FROM bp_sources WHERE db_names='$source_name'");
		while ($line = mysql_fetch_array($result)){
			$thruk_idx=$line[0];
        }

		return $thruk_idx;
	}
/* ~~~~~~~~~~ ! FUNCTIONS ~~~~~~~~~~ */
	
	$nick_name = getNickName($_GET["source"]);
	
	// ~~~~~~~~~~~~ TEST ~~~~~~~~~~~~ // 
	$type = mysql_fetch_assoc(sqlrequest($_GET["source"],"SELECT type,min_value FROM bp WHERE name='$_GET[uname]'"));
	if ( $type['type'] == "MIN") {
		$return = sqlArrayDatabase($_GET["source"], "SELECT COUNT(bp_name) as nbr FROM bp_services WHERE bp_name='$_GET[uname]' UNION select COUNT(bp_name) FROM bp_links WHERE bp_name='$_GET[uname]'");
		if ( ($return[0]['nbr']+$return[1]['nbr']) >= $type['min_value']){
			$result = sqlrequest($_GET["source"],"UPDATE bp SET `is_define`='1' WHERE `name`='$_GET[uname]'");
		}
		else {
			$result = sqlrequest($_GET["source"],"UPDATE bp SET `is_define`='0' WHERE `name`='$_GET[uname]'");
		}
	}
	else {
		$result = sqlrequest($_GET["source"],"UPDATE bp SET `is_define`='1' WHERE `name`='$_GET[uname]'");
	}
	// ~~~~~~~~~~~~ TEST ~~~~~~~~~~~~ //
	
	$result = sqlrequest($_GET[source],"SELECT '$_GET[source]' as source, name, description, priority, type,command,url,min_value,is_define FROM bp WHERE name='$_GET[uname]'");
	$metier = mysql_fetch_assoc($result);
?>
<body id="main">
	<form action='./add_mod_bp.php' method='POST' name='form_bp'>
		<span id="output"></span>
		<center>
			<table>
				<tr>
					<td valign="top">
						<h1><?php echo $xmlmodules->getElementsByTagName("manage_bp")->item(0)->getAttribute("prop")?></h1>
						<table class="table">
							<tr rowspan="3">
								<td><h2>Source</h2></td>
								<td id="sourcedb"></td>
								<td>
									<center>Use a list? <input type='checkbox' id='sourceinput' onclick='javascript:selectValueSource("changesource",this.checked);'></center>
								</td>
							</tr>
							<?php if($_GET["source"] != "global_nagiosbp") : ?>
							<tr>
								<td><h2>Equipment</h2></td>
								<td id="td"></td>
								<td>
									<center>Use a list? <input type='checkbox' id='check' onclick='javascript:selectValue("change",this.checked);'></center>
								</td>
							</tr>
							<tr>
								<td><h2>Service</h2></td>
								<td>
									<select size='1' name='service[]' id="change" style='width:360px;'></select>
								</td>
								<td>
									<center><input class='button' type='button' id='add' name='add' value='add' onclick='javascript:addValue("nobp");'></center>
								</td>
							</tr>
							<?php endif; ?>
							<tr>
								<td><h2>Process</h2></td>
								<td>
									<select size='1' name='prio' id='prio' style='50px;' onChange="javascript:chooseDisplay(this.value);">
										<option value='all'>Display All</option>
										<option value='null'>No Display</option>
										<?php 
											global $max_display;
											global $display_zero;
											for ($i=((int)!$display_zero); $i < $max_display+1 ; $i++) { 
												echo "<option value='$i'>Display $i</option>";
											}
										?>
									</select>
									<select size='1' name='proc[]' id='proc' style='width:276px;'></select>
								</td>
								<td>
									<center><input class='button' type='button' id='addProc' name='add' value='add' onclick='javascript:addValue("bp");'></center>
								</td>
							</tr>
							<tr>
								<td class="blanc" align="center" colspan="3">
									<input class='button' type='button' name='back' value='back' onclick='location.href="index.php"'>
								</td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<h1>Definition of : <?php echo "<span id=\"definitionNickName\">$nick_name</span><span id=\"definitionSource\" style=\"display: none;\">$metier[source]</span>::$metier[name] ; type : $metier[type]"; if( $metier['min_value'] != "") echo " $metier[min_value]";?></h1>
						<table class='table'>
						<thead>
							<tr>
								<th>Source::Name;Service<br/>Source::Process</th>
								<th>Select</th>
							</tr>
						</thead>
						<tbody id="sum">
						</tbody>
						<tr>
							<td class="blanc" colspan="9" align="center">
								<input class='button' type='button' name='del' value='delete' onclick='javascript:delValue();'>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
		</center>
	</form>
	<?php 
		$nbrServ = 0;
		$tabMetier = sqlArrayDatabase($_GET["source"],"SELECT '$_GET[source]' as source, name, description, priority, type,command,url,min_value,is_define FROM bp WHERE name NOT IN (SELECT bp_name FROM bp_links WHERE bp_link='$_GET[uname]')");
		$result = sqlArrayDatabase($_GET["source"],"SELECT '$_GET[source]' as source,id, bp_name, host, service FROM bp_services WHERE `bp_name`='$_GET[uname]'");
		$nbrServ += count($result)."<br>";
		
		$result = sqlArrayDatabase($_GET["source"],"SELECT '$_GET[source]' as source,id, bp_name, bp_link FROM bp_links WHERE `bp_name`='$_GET[uname]'");
		$nbrServ += count($result)."<br>";
	?>

	<script type="text/javascript" src="function.js"></script>
	<script type="text/javascript" src="../../js/jquery.js"></script>
	<script type="text/javascript" src="../../js/jquery.autocomplete.js"></script>
	<script type="text/javascript">
		$("input[name=equip]").attr("disabled", true);
		$("#sourceinput").attr("checked", true);
		
		$("#check").attr("checked", true);
		selectValueSource("changesource", true);
		selectValue("change", true);
			
		setValues("<?php echo $_GET['uname']?>", <?php echo json_encode($tabMetier) ?>,"<?php echo $_GET['source']?>","<?php echo $nbrServ?>","<?php echo $metier['min_value'];?>");
		
		<?php 
		if ( $metier['is_define']){
			$result = sqlArrayDatabase($_GET[source],"SELECT '$_GET[source]' as source,id, bp_name, host, service FROM bp_services WHERE bp_name='$_GET[uname]'");
        // DEBUG
          //echo "\n\n/* SQLREQUEST Serv is:SELECT '".$_GET[source]."' as source,id, bp_name, host, service FROM bp_services WHERE bp_name='".$_GET[uname]."'\n";
          //echo "\n Result:".var_dump($result)."\n";
          //echo "\n*/\n";
        // FIN DEBUG
			echo "setServ(".json_encode($result).");";
      
      
      if ( $_GET[source] == "global_nagiosbp" ) {
			  $result = sqlArrayDatabase($_GET[source],"SELECT '$_GET[source]' as source,id, bp_name, bp_link, bp_source FROM bp_links WHERE bp_name='$_GET[uname]'");
        // DEBUG
          //echo "\n\n/* SQLREQUEST Proc is:SELECT '".$_GET[source]."' as source,id, bp_name, bp_link, bp_source FROM bp_links WHERE bp_name='".$_GET[uname]."'\n";
          //echo "\n Result:".var_dump($result)."\n";
          //echo "\n*/\n";
        // FIN DEBUG
      }
      else {
        $result = sqlArrayDatabase($_GET[source],"SELECT '$_GET[source]' as source,id, bp_name, bp_link, '$_GET[source]' as bp_source  FROM bp_links WHERE bp_name='$_GET[uname]'");
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
</body>
</html>
