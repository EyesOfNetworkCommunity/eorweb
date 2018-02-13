<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Michael Aubertin
# VERSION 2.2
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
?>

<?php
include("../../header.php");
include("../../side.php");
?>

<div id="page-wrapper">
	<div class="row form-group">
    	<h1 class="page-header"><?php echo getLabel("label.mgt_upload_report.title"); ?></h1>
    </div>
	<div class="row">
		<div class="form-group input-group col-md-10">
			<input class="form-control" type="hidden" name="MAX_FILE_SIZE" value="2000000">
			<span class="btn btn-default btn-file col-md-9">
				<input type="file" name="filename col-md-12">	
			</span>
			<input class="btn btn-primary" type="submit" name="upload" value="upload">
		</div>
	</div>
</div>

<?php
# --- Check if the form is post
if( isset($_POST['upload']) ){
	# --- Check if there is an error in the upload
    if ($_FILES['filename']['error']) {
      	switch ($_FILES['filename']['error']){
               case 1: // UPLOAD_ERR_INI_SIZE
				   message(5,"The uploaded file exceeds the upload_max_filesize directive in php.ini","critical");
				   break;
               case 2: // UPLOAD_ERR_FORM_SIZE
				   message(5,"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.","critical");
	               break;
               case 3: // UPLOAD_ERR_PARTIAL
				   message(5,"The uploaded file was only partially uploaded.","critical");
                   break;
               case 4: // UPLOAD_ERR_NO_FILE
				   message(5,"No file was uploaded","critical");
                   break;
      	}
	} else {
		$file_tmp = $_FILES['filename']['tmp_name'];
    	$file_dst = "/srv/eyesofreport/report/" . basename($_FILES['filename']['name']);
    	if (move_uploaded_file($file_tmp, $file_dst)) {
        	echo "Upload succeed of ".$file_dst.".&nbsp; Please consider to perform declaration and setup credential.&nbsp;";
    	} else {
        	echo "Upload failed. Please contact your administrator.\n";
    	}
	}
}
?>

<?php include("../../footer.php"); ?>