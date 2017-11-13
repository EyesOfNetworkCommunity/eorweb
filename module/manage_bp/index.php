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
<?php
/* ~~~~~~~~~~ FUNCTIONS ~~~~~~~~~~ */
function deleteAll($tableBp,$Source){
	foreach($tableBp as $bp){
		$result = sqlArrayDatabase($Source,"SELECT bp_name FROM bp_links WHERE bp_link='$bp[bp_name]'");
		sqlrequest($Source,"DELETE FROM bp_links WHERE bp_name='$bp[bp_name]'");
		sqlrequest($Source,"DELETE FROM bp_services WHERE bp_name='$bp[bp_name]'");
		sqlrequest($Source,"DELETE FROM bp WHERE name='$bp[bp_name]'");
		deleteAll($result);
	}
}

function deleteOne($bp,$deleted,$Source){
	if ( $deleted != "" ) $deleted .= ",$bp";
	else $deleted = "$bp";

	sqlrequest($Source,"DELETE FROM bp_links WHERE bp_name='$bp'");
	sqlrequest($Source,"DELETE FROM bp_services WHERE bp_name='$bp'");
	sqlrequest($Source,"DELETE FROM bp WHERE name='$bp'");
	return $deleted;
}
/* ~~~~~~~~~~ ! FUNCTIONS ~~~~~~~~~~ */
?>
<html>
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<?php include("../../include/include_module.php"); ?>
		<script type="text/javascript" src="../../js/jquery.js"></script>
		<script type="text/javascript" src="function.js"></script>

</head>
<body id="main">
<h1><?php echo $xmlmodules->getElementsByTagName("manage_bp")->item(0)->getAttribute("title")?></h1>
<?php
	
	global $max_display;
	global $display_zero;
	$action=retrieve_form_data("action",null);
	$build=retrieve_form_data("build",null);
	$bp_mgt_list=retrieve_form_data("bp_mgt_list",null);
	global $path_nagiosbpcfg ;
	global $path_nagiosbpcfg_bu ;
	global $path_nagiosbpcfg_lock ;
	global $database_vanillabp;
	global $max_bu_file;

	if ($build == "Apply Config") buildFile();
	if ($action == "submit"){
		switch($bp_mgt_list)
		{
			case "add_process" : 
				echo "<META HTTP-EQUIV=refresh CONTENT='0;URL=add_process.php'>";
				break ;
			case "delete_process" :
				$bp_selected = array();
				for ( $i = 0 ; $i < $max_display+2 ; $i++){
					$bps = retrieve_form_data("bp_selected$i",null);
					if ( $bps ) $bp_selected = array_merge($bp_selected,$bps);
				} 

				$notDeleted = array();
				$deleted= "";
				foreach($bp_selected as $bp)
				{	
					$bp_parts = explode("::", $bp);
					$bp_name = $bp_parts[0];
					$Source = $bp_parts[1];
					
           // Get the db_sources_from_nickname //
          	$resultsource=sqlrequest("global_nagiosbp","SELECT db_names FROM bp_sources WHERE nick_name='$Source'");
          
            while ($db_line = mysql_fetch_array($resultsource))
            {
                    $Source = $db_line[db_names];
            }       
					$result = sqlArrayDatabase($Source,"SELECT bp_name FROM bp_links WHERE bp_link='$bp_name'");
					if ( $result ){
						foreach($result as $i=>$r){
							if ( !in_array($r['bp_name'],$bp_selected) ){
								if ( isset($strDep) ) $strDep .= ",$r[bp_name]";
								else $strDep = "$r[bp_name]";
							}
						}
						if (isset($strDep))	$notDeleted[] = $bp_name." has dependencies with ".$strDep.".";
						else $deleted = deleteOne($bp_name, $deleted, $Source);
					}
					else {
						$deleted = deleteOne($bp_name, $deleted, $Source);
					}
				}

				if ( $deleted != "") message(6," : $deleted deleted","ok");
				foreach($notDeleted as $del){
					message(0," : $del","warning");
				}
				break ;
			case "cascade_delete" :
				$bp_selected = array();
				for ( $i = 0 ; $i < $max_display+2 ; $i++){
					$bps = retrieve_form_data("bp_selected$i",null);
					if ( $bps ) $bp_selected = array_merge($bp_selected,$bps);
				}
				foreach ($bp_selected as $bp)
				{
					$bp_parts = explode("::", $bp);
					$bp_name = $bp_parts[0];
					$Source = $bp_parts[1];
                  
           // Get the db_sources_from_nickname //
        	$resultsource=sqlrequest("global_nagiosbp","SELECT db_names FROM bp_sources WHERE nick_name='$Source'");
        
          while ($db_line = mysql_fetch_array($resultsource))
          {
                  $Source = $db_line[db_names];
          }
          
					$result = sqlArrayDatabase($Source,"SELECT bp_name FROM bp_links WHERE bp_link='$bp_name'");
					sqlrequest($Source,"DELETE FROM bp_links WHERE bp_name='$bp_name'");
					sqlrequest($Source,"DELETE FROM bp_services WHERE bp_name='$bp_name'");
					sqlrequest($Source,"DELETE FROM bp WHERE name='$bp_name'");
					
					deleteAll($result, $Source);
				}
				break ;
			case "backup" :
				$option = retrieve_form_data("bu_list",null);
				switch ($option){
					case "clean" :
						for ( $i = 1 ; $i < $max_bu_file+1 ; $i++){
							if ( file_exists($path_nagiosbpcfg_bu.$i)) unlink($path_nagiosbpcfg_bu.$i);
							else break;
						}
						break;
					default :
						wait($path_nagiosbpcfg_lock);	//Wait for the file to not be in use.
						$fp=@fopen($path_nagiosbpcfg_lock,"w");	//Lock the file.
						fputs($fp,getmypid());
						fclose($fp);

						rename($path_nagiosbpcfg_bu.$option,$path_nagiosbpcfg_bu.'temp');
						backup_file($option);
						copy($path_nagiosbpcfg_bu.'temp',$path_nagiosbpcfg);

						$lines = file($path_nagiosbpcfg);
						unset($lines[0]);
						write_file($path_nagiosbpcfg,$lines,"w");

						unlink($path_nagiosbpcfg_bu.'temp');
						unlink($path_nagiosbpcfg_lock);
						break ;
				}
				break;
			case "duplicate" :
				global $min_dup;
				global $max_dup;
				$bp_selected = array();
				for ( $i = 0 ; $i < $max_display+2 ; $i++){
					$bps = retrieve_form_data("bp_selected$i",null);
					if ( $bps ) $bp_selected = array_merge($bp_selected,$bps);
				}
				$notDuplicate = array() ;
				foreach ( $bp_selected as $bp)
				{
					$bp_parts = explode("::", $bp);
					$bp_name = $bp_parts[0];
					$Source = $bp_parts[1];
					
					$count = sqlArrayDatabase($Source,"SELECT COUNT(name) AS nbr FROM bp WHERE name REGEXP '$bp_name-([0-9]){".strlen($min_dup).",".strlen($max_dup)."}$'");
					if ( $count[0]['nbr'] == $max_dup-$min_dup+1 ) {
						$notDuplicate[] = $bp ;
					}
					else {
						$rand_num = mt_rand($min_dup,$max_dup);

						while ( sqlArrayDatabase($Source,"SELECT * FROM bp WHERE name='$bp_name-$rand_num'") ) {
							$rand_num++;
							if ($rand_num > $max_dup) $rand_num = $min_dup;
						}

						$infos = sqlArrayDatabase($Source,"SELECT * FROM bp WHERE name='$bp_name'");
						foreach ($infos as $info){
							$request = "INSERT INTO bp VALUES ('$info[name]-$rand_num','";
							if ( $info['description'] != "")	$request .= "$info[description]-$rand_num";
							$request .=	"','$info[priority]','$info[type]','$info[command]','$info[url]','$info[min_value]','$info[is_define]')";
							sqlrequest($Source,$request);
						}

						$infos = sqlArrayDatabase($Source,"SELECT * FROM bp_services WHERE bp_name='$bp_name'");
						foreach ( $infos as $info){
							sqlrequest($Source,"INSERT INTO bp_services VALUES('','$info[bp_name]-$rand_num','$info[host]','$info[service]')");
						}
						$infos = sqlArrayDatabase($Source,"SELECT * FROM bp_links WHERE bp_name='$bp_name'");
						foreach ( $infos as $info){
							sqlrequest($Source,"INSERT INTO bp_links VALUES('','$info[bp_name]-$rand_num','$info[bp_link]')");
						}
					}
				}
				
				if ( !empty($notDuplicate) ){
					foreach ($notDuplicate as $dup) {
						if ( !isset($str) ) $str = "$dup";
						else $str .= ",$dup";
					}

					message(0," : Can not duplicate $str","warning");
				}
				break;
			case "delete_all" :

				$result=sqlrequest($database_vanillabp,"SELECT db_names FROM bp_sources");
				sqlrequest($database_vanillabp,"DELETE FROM bp");
				sqlrequest($database_vanillabp,"DELETE FROM bp_services");
				sqlrequest($database_vanillabp,"DELETE FROM bp_links");

			    while ($db_line = mysql_fetch_array($result))
			    {
			    		sqlrequest($db_line[db_names],"DELETE FROM bp");
						sqlrequest($db_line[db_names],"DELETE FROM bp_services");
						sqlrequest($db_line[db_names],"DELETE FROM bp_links");
				}

				message(6,"","ok");
				break;
		}
	}

	//Check for inconvenient in the process.
	if (isset($_GET['del'])){
		unlink($_GET['del']);
	}

	if ( file_exists($path_nagiosbpcfg_lock)){
		sleep(1);
		if ( file_exists($path_nagiosbpcfg_lock)){
			echo $xmlmodules->getElementsByTagName("manage_bp")->item(0)->getAttribute("check");
			echo "   <a href=index.php?del=$path_nagiosbpcfg_lock>yes</a> | <a href=index.php>No</a>";
			exit;
		}
	}

	//Read the file to get the informations.
	formatFile();
    
    $tabMetier = array();
	$result=sqlrequest("global_nagiosbp","SELECT db_names, nick_name FROM bp_sources");

    while ($db_line = mysql_fetch_array($result))
    {
            $tabMetier = array_merge($tabMetier,sqlArrayDatabase($db_line[db_names],"SELECT '$db_line[nick_name]' as nick_name, '$db_line[db_names]' as db_names, name, description, priority, type,command,url,min_value,is_define FROM bp ORDER BY name ASC"));
    }
?>
<form action='./index.php' method='GET'>
	<table id="getWidth" style="position:fixed;margin-top:10px;">
		<tr>
			<td class="blanc" align="left">
				Display :
			</td>
			<td>
				<select id='prio' onChange='javascript:show(this.value)'>
					<option value='all'>Display All</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="blanc" align="left">
				Action :
			</td>
			<td>
				<?php	
					// Get the global table
					global $array_bp_mgt;

					// Get the first array key
					reset($array_bp_mgt);

					// Display the list of management choices
					echo "<select name='bp_mgt_list' size='1' onchange='setVisible(this.value)'>";
					while (list($mgt_name, $mgt_url) = each($array_bp_mgt)) {
						echo "<option value='$mgt_url'>$mgt_name</option>";
					}
					echo "</select>";
					?>
				<input class='button' type='submit' name='action' value='submit' onclick="javascript:return getConfirm(this.value);">
			</td>
		</tr>
		<tr id="setVis">
			<td class="blanc" align="left">
				Back-Up File :
			</td>
			<td>
				<select name="bu_list" size="1" onchange="showSurvey(this.value)">
					<option value='clean'>Clean</option>
					<?php
						for ($i=1;$i < $max_bu_file+1 ; $i++){
							if ( file_exists($path_nagiosbpcfg_bu.$i) ){
								echo "<option value='$i'>Get $i</option>";
							}
						}
					?>
				</select>
				<input class='button' type="button" name='survey' value='survey' onclick="preview()"/>
			</td>
		</tr>
		<tr>
			<td class="blanc" align="left">
				<!-- <input class="button" type="submit" value="Apply Config" name="build" onclick="javascript:return getConfirm(this.value);"> -->
        <br><a href="./viewver.php">View Business Process Tree</a>
        <br><br><a href="./admin_category_CI.php">Customize Core Infrastructure Category</a>
        <br><a href="./admin_category_CA.php">Customize Customer Access Category</a>
			</td>
		</tr>
	</table>
	<table id="setWidth">
		<tr><td><table class='table' id='0' style='display:none;'>
			<thead><tr>
				<th>Source</th><th>Name</th><th style="width:40px;">Select</th>
			</tr></thead>
			<tr>
				<td colspan='2'><center>No Display</center></td>
				<td><center><a href='#' onclick='javascript:selectAll(0)'>ALL</a></center></td>
			</tr>
		</table></td></tr>
		<?php
			for ($i = 1 ; $i < $max_display+2 ; $i++){
				echo "<tr><td><table class='table' id='$i' style='display:none;'>
					<thead><tr>
					<th>Source</th><th>Name</th><th style='width:40px;'>Select</th>
					</tr></thead>
					<tr>
						<td colspan='2'><center>Display ".($i-$display_zero)."</center></td>
						<td><center><a href='#' onclick='javascript:selectAll($i)'>ALL</a></center></td>
					</tr>
					</table></td></tr>";
			}
		?>
	</table>
	<textarea cols='90' rows='25' id='survey' readonly scrolling='no' style='display:none;margin-top:10px;resize:none;'></textarea>
</form>
<script type="text/javascript">
	
	$("#setWidth").css("margin-left",$("#getWidth").width()+10);
	$("#survey").css("margin-left",$("#getWidth").width()+20);
	setDisplay(<?php echo $max_display;?>);
	<?php
	foreach( $tabMetier as $metier) {
		if (array_key_exists('nick_name', $metier)) {
    		echo "makeTable(\"$metier[nick_name]\",\"$metier[priority]\",\"$metier[name]\");\n";
		}
		else
		{
			echo "makeTable(\"global_nagiosbp\",\"$metier[priority]\",\"$metier[name]\");\n";
		}
	}?>
	resizeAll();
	appendDisplay();
	show("all");
	setVisible($("select[name=bp_mgt_list]").val());
</script>
</body>
</html>
