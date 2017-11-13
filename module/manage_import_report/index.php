<?php
/*
#########################################
#
# Copyright (C) 2014 EyesOfNetwork Team
# DEV NAME : Jean-Philippe LEVY
# VERSION 4.1
# APPLICATION : eonweb for eyesofnetwork project
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
</head>
<body id="main">

        <h1><?php echo $xmlmodules->getElementsByTagName("manage_import")->item(0)->getAttribute("title")?></h1>
	<table class="table" width="95%">
		<tr align="center">
			<td colspan="5" class="blanc">
				<FORM method="POST" ENCTYPE="multipart/form-data">
					<INPUT type=hidden name=MAX_FILE_SIZE  VALUE=200000000>
					<INPUT class="file" type=file name="filename">
					<INPUT class="button" type=submit name="upload" value="Upload">
				</FORM>
			</td>
		</tr>
		<tr>
			<td colspan="5" class="blanc">&nbsp;</td>
		</tr>
		<?php
		# --- Check if the form is post
		if( isset($_POST['upload']) )
		{
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
			}
			else {
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
	</table>
</body>
</html>
