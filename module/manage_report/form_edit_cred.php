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
			<h1 class="page-header"><?php echo getLabel("label.manage_report.credential_title"); ?></h1>
		</div>
	</div>
	
	<div id="message">
	<?php
	# --- Get Report ID
	if (!isset($_GET['report_id'])) {
		message(0,getLabel("label.manage_report.no_report"),"critical");
	} else {
		
		// Variables
		$grp_avail = array();
		$grp_format = array();
		
		$report_id = $_GET['report_id'];
		$sql = "SELECT * FROM reports WHERE report_id=?;";
		$result = sqlrequest($database_eorweb,$sql,false,array("i",(int)$report_id));
		$row = $result->fetch_assoc(); 
		$report_name = $row['report_name'];
	
	}
	
	# --- Change report name
	if (isset($_POST['change_name']) && $_POST['change_name'] != '') {
		$report_name = $_POST['change_name'];
		$sql2 = "UPDATE reports SET report_name = ? WHERE report_id = ?";
		$result2 = sqlrequest($database_eorweb,$sql2, false,array("si",(string)$report_name,(int)$report_id));
		echo "<script>window.location.replace(\"./form_edit_cred.php?report_id=".$report_id."\")</script>";
	}
	
	# --- Upload new report
	if( isset($_POST['upload']) ){
		# --- Check if there is an error in the upload
		if ($_FILES['filename']['error']) {
			switch ($_FILES['filename']['error']){
				   case 1: // UPLOAD_ERR_INI_SIZE
					   message(5,getLabel("label.manage_report.upload_error"),"critical");
					   break;
				   case 2: // UPLOAD_ERR_FORM_SIZE
					   message(5,getLabel("label.manage_report.upload_error2"),"critical");
					   break;
				   case 3: // UPLOAD_ERR_PARTIAL
					   message(5,getLabel("label.manage_report.upload_error3"),"critical");
					   break;
				   case 4: // UPLOAD_ERR_NO_FILE
					   message(5,getLabel("label.manage_report.upload_error4"),"critical");
					   break;
			}
		} else {
			$file_tmp = $_FILES['filename']['tmp_name'];
			$file_dst = "/srv/eyesofreport/report/" . basename($_FILES['filename']['name']);
			if (move_uploaded_file($file_tmp, $file_dst)) {
				message(6,getLabel("label.manage_report.upload_success"). $file_dst ." : ".getLabel("label.manage_report.upload_success2"),"ok");
			} else {
				message(5,getLabel("label.manage_report.upload_error5"),"critical");
			}
		}
	} 
	?>
	</div>

	<div class="row">
		<div class="col-md-12">
			<h2 class="page-header"><?php echo $report_name ?></h2>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo getLabel("label.form_edit_cred.change_name"); ?>
				</div>
				<div class="panel-body">
					<form method="POST">
						<div class="input-group col-md-12">
							<input class="form-control" type="text" name="change_name">
							<span class="input-group-btn">
								<input type="submit" class="btn btn-primary" value="<?php echo getLabel("action.submit"); ?>">
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo getLabel("label.mgt_upload_report.title"); ?>
				</div>
				<div class="panel-body">
					<form method="post" ENCTYPE="multipart/form-data" action="./form_edit_cred.php?report_id=<?php echo $report_id ?>">
						<div class="input-group">
						<input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
						<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
						<input class="form-control" type="file" name="filename">
						<span class="input-group-btn">
						<button class="btn btn-primary" type="submit" name="upload" value="Upload"><?php echo getLabel("label.mgt_upload_report.btn_upload"); ?></button>
						</span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><?php echo getLabel("label.manage_report.modify_credential"); ?></div>
				<div class="panel-body">
					<div class="table-responsive">          
						<table class="table table-striped">
							<thead>
								<tr>
									<th><?php echo getLabel("label.manage_report.user_group"); ?></th>
									<th><?php echo getLabel("label.manage_report.available"); ?></th>
									<?php
									$sql = "SELECT * FROM output_format;";
									$result = sqlrequest($database_eorweb,$sql);
									while($row = $result->fetch_assoc()){
									?>
									<th><?php echo $row['type']; ?></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php
								/****************  User Group ********************/
								$sql_group = " SELECT * FROM groups;";
								$result_group = sqlrequest($database_eorweb,$sql_group);
								while($row_group = $result_group->fetch_assoc()){
								?>
								<tr> 
									<td id="group_id_<?php echo $row_group['group_id']; ?>"><?php echo $row_group['group_descr']; ?></td>
									<?php
									/**************** Available (list from join_report_cred ****************/
									$sql_report_avail = "SELECT * FROM join_report_cred WHERE group_id=".$row_group['group_id']." AND report_id=".$report_id.";";
									$result_report_avail = sqlrequest($database_eorweb,$sql_report_avail);
									$num_rows = mysqli_num_rows($result_report_avail);
									if ($num_rows > 0){ 
										echo " <td><input type=\"checkbox\" id=\"grp_".$row_group['group_id']."_avail_".$row_group['group_id']."\" value=\"\" checked></td>\n";
										array_push($grp_avail,"grp_".$row_group['group_id']."_avail_".$row_group['group_id']);
									} else {
										echo " <td><input type=\"checkbox\" id=\"grp_".$row_group['group_id']."_avail_".$row_group['group_id']."\" value=\"\"></td>\n";
										array_push($grp_avail,"grp_".$row_group['group_id']."_avail_".$row_group['group_id']);
									}
									
									/***************** FORMAT ******************/  
									$sql_format = "SELECT * FROM output_format;";
									$result_format = sqlrequest($database_eorweb,$sql_format);
									while($row_format = $result_format->fetch_assoc()){ 
										# Test current Settings....
										$sql_format_inDB = "SELECT * FROM join_report_format  INNER JOIN join_report_cred ON join_report_cred.report_id=join_report_format.report_id 
										WHERE output_format_id=".$row_format['format_id']." 
										AND join_report_cred.report_id=".$report_id." 
										AND group_id=".$row_group['group_id'].";";
										$result_format_inDB = sqlrequest($database_eorweb,$sql_format_inDB);
										$num_rowsDB = mysqli_num_rows($result_format_inDB);
										if ($num_rowsDB > 0){                                       
											echo " <td><input type=\"checkbox\" id=\"grp_".$row_group['group_id']."_format_id_".$row_format['format_id']."\" value=\"\" checked></td>\n";
											array_push($grp_format,"grp_".$row_group['group_id']."_format_id_".$row_format['format_id']);
										} else {
											echo " <td><input type=\"checkbox\" id=\"grp_".$row_group['group_id']."_format_id_".$row_format['format_id']."\" value=\"\"></td>\n";
											array_push($grp_format,"grp_".$row_group['group_id']."_format_id_".$row_format['format_id']);
										}
									}
									echo "</tr>\n\n";                
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<button class="btn btn-default" type="button" name="back" value="back" onclick="location.href='../manage_report/index.php'"><?php echo getLabel("action.cancel") ?></button>
		</div>
	</div>
</div>

<script src="/bower_components/jquery/dist/jquery.min.js"></script>

<?php 
/********* Global Avail *************/
while (list ($key, $val) = each ($grp_avail) ) {
    echo "<script>\n";
    echo "$('#".$val."').on('click',function() {\n";
    echo " \n";  // row was clicked
    echo "window.open ('./update_report_cred.php?report_id=".$report_id."&tgt=".$val."','_self',false);";
    echo "});\n";
    echo "</script>\n";
} 
reset($grp_avail);

/********* By Format ******************/
while (list ($key, $val) = each ($grp_format) ) {
    echo "<script>\n";
    echo "$('#".$val."').on('click',function() {\n";
    echo " \n";  // row was clicked
    echo "window.open ('./update_report_cred.php?report_id=".$report_id."&tgt=".$val."','_self',false);";
    echo "});\n";
    echo "</script>\n";
}
reset($grp_format);

include("../../footer.php"); 

?>
