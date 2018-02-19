<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Quentin HOARAU
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
			<h1 class="page-header"><?php echo getLabel("label.admin_user.title"); ?></h1>
		</div>
	</div>

	<?php 
	global $database_eorweb;
	$action=retrieve_form_data("action",null);
	$user_mgt_list=retrieve_form_data("user_mgt_list",null);
	$user_selected=retrieve_form_data("user_selected",null);
	
	if($action == 'submit') {
		switch($user_mgt_list){
			case "add_user": 
				echo "<meta http-equiv=refresh content='0;url=add_modify_user.php'>";
				break;
			case "delete_user":
				if (isset($user_selected[0])){
					for ($i = 0; $i < count($user_selected); $i++){
						// Get user name
						$user_res=sqlrequest("$database_eorweb","select user_name from users where user_id='$user_selected[$i]'");
						$user_name=mysqli_result($user_res,0,"user_name");
						// Delete user in eorweb
						sqlrequest("$database_eorweb","delete from users where user_id='$user_selected[$i]'");
						// Delete user files
						$user_files_path="$path_eorweb/$dir_imgcache/$user_name";
						@unlink("$user_files_path-ged.xml");
						@unlink("$user_files_path-report.doc");
						@unlink("$user_files_path-report.xml");
						@unlink("$path_eorweb/$dir_imgcache/$user_name-report_xml.xml");
						foreach (glob("$user_files_path-*.png") as $filename){
							@unlink($filename);
						}
						// Logging action
						logging("admin_user","DELETE : $user_selected[$i]");
						message(8," : User $user_name removed",'ok');
					}
				}
				break;
		}
	}
        
	// Get the name user and description group
	$user_id="";
	$user_name_descr=sqlrequest("$database_eorweb","SELECT user_name,user_descr,user_id,group_name,user_type,user_email FROM users LEFT OUTER JOIN groups ON groups.group_id = users.group_id ORDER BY user_name");
	 ?>

		<form action="./index.php" method="GET" class="form-inline">
			<div class="dataTable_wrapper">
				<table class="table table-striped datatable-eorweb table-condensed">
					<thead>
						<tr>
							<th class="col-md-2 text-center"><?php echo getLabel("label.admin_user.select"); ?></th>
							<th><?php echo getLabel("label.admin_user.user_name"); ?></th>
							<th><?php echo getLabel("label.admin_user.user_type"); ?></th>
							<th><?php echo getLabel("label.admin_user.user_desc"); ?></th>
							<th><?php echo getLabel("label.admin_user.user_mail"); ?></th>
							<th><?php echo getLabel("label.admin_user.user_group"); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($line = mysqli_fetch_array($user_name_descr)) {
							echo "<tr>
								<td class=\"text-center\">";
									if($line[2]=="1"){
										echo "<input type='checkbox' name='user_selected[]' value='$line[2]' disabled>";
									} else {
										echo "<input type='checkbox' name='user_selected[]' value='$line[2]'>";
									}
								echo "</td>
								<td>
									<a href='./add_modify_user.php?user_id=$line[2]'> $line[0] </a>
								</td>
								<td>";
									if($line[4]=="0") {
										$type="MYSQL";
									} else {
										$type="LDAP";
									}
									echo "$type
								</td>
								<td>
									$line[1]
								</td>
								<td>
									".$line['user_email']."
								</td>
								<td>
									$line[3]
								</td>
							</tr>";
						} ?>
					</tbody>
				</table>
			</div>
			
			<div class="form-group">
				<select class="form-control" name="user_mgt_list" size=1>
				<?php	
				// Get the global table
				global $array_user_mgt;
				// Get the first array key
				reset($array_user_mgt);
				// Display the list of management choices
				while (list($mgt_name, $mgt_url) = each($array_user_mgt)) {
					echo "<option value='$mgt_url'>".getLabel($mgt_name)."</option>";
				}?>
				</select>
			</div>
			<button class="btn btn-primary" type="submit" name="action" value="submit"><?php echo getLabel("action.submit"); ?></button>
		</form>

</div>

<?php include("../../footer.php"); ?>
