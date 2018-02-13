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
?>

<?php


include("../../header.php");
include("../../side.php");

global $database_eonweb;
global $database_host;
global $database_username;
global $database_password;
global $path_rptdesign;

$grp_avail = array();
$grp_format = array();

$db = new mysqli($database_host, $database_username, $database_password, $database_eonweb);

if($db->connect_errno > 0){
die('Unable to connect to database [' . $db->connect_error . ']');
} 


$report_id = $_GET['report_id'];
if (!isset($report_id)) {
    die('Unable to requested report.');
}

$sql = "SELECT * FROM reports WHERE report_id=".$report_id.";";

if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}
while($row = $result->fetch_assoc()){ 
    $report_name = $row['report_name'];
}

if (isset($_POST['change_name']) && $_POST['change_name'] != '') {
    $request = $db->query("UPDATE reports SET report_name = '".$_POST['change_name']."' WHERE report_id='".$report_id."'");
    echo "<script>window.location.replace(\"http://eyesofreport/module/manage_cred_report/form_edit_cred.php?report_id=".$report_id."\")</script>";
}

?>

<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">Report credentials settings</h1>
        <div class="form-group">
            <h3><?php echo getLabel("label.mgt_upload_report.title"); ?></h3>
            <form class="form-inline" method="post">
                <div class="input-group col-md-5">
                    <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                    <input class="form-control" type="file" name="filename">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit" name="upload" onclick="upload()"><?php echo getLabel("label.mgt_upload_report.btn_upload"); ?></button>
                    </span>
                </div>
            </form>
        </div>
        <h2><?php echo $report_name ?></h2>
        <div class="table-responsive">          
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User group</th>
                        <th>Available</th>
                        <?php
                        $sql = "SELECT * FROM output_format;";
                        if(!$result = $db->query($sql)){
                           die('There was an error running the query [' . $db->error . ']');
                        }
                        while($row = $result->fetch_assoc()){ 
                            echo " <th>".$row['type']."</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    /****************  User Group ********************/
                    $sql_group = " SELECT * FROM groups;";
                    if(!$result_group = $db->query($sql_group)){
                        die('There was an error running the query [' . $db->error . ']');
                    }
                    while($row_group = $result_group->fetch_assoc()){
                        echo "<tr>\n"; 
                        echo "<td id=\"group_id_".$row_group['group_id']."\">".$row_group['group_descr']."</td>\n";
                        /**************** Available (list from join_report_cred ****************/
                        $sql_report_avail = "SELECT * FROM join_report_cred 
                            WHERE group_id=".$row_group['group_id']."
                            AND report_id=".$report_id.";";
                        if(!$result_report_avail = mysqli_query($db,$sql_report_avail)){
                            die('There was an error running the query [' . $db->error . ']');
                        }
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
                        if(!$result_format = $db->query($sql_format)){
                            die('There was an error running the query ['. $db->error . ']');
                        }
                        while($row_format = $result_format->fetch_assoc()){ 
                            # Test current Settings....
                            $sql_format_inDB = "SELECT * FROM join_report_format 
                                INNER JOIN join_report_cred ON join_report_cred.report_id=join_report_format.report_id 
                                WHERE output_format_id=".$row_format['format_id']." 
                                AND join_report_cred.report_id=".$report_id." 
                                AND group_id=".$row_group['group_id'].";";
                                if(!$result_format_inDB = mysqli_query($db,$sql_format_inDB)){
                                    die('There was an error running the query [' . $db->error . ']');
                                }
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
        <h3><?php echo getLabel("label.form_edit_cred.change_name"); ?></h3>
        <form method="POST">
            <div class="input-group col-md-3">
                <?php echo "<input class=\"form-control\" type=\"text\" name=\"change_name\" value=\"".$report_name."\">"; ?>
                <span class="input-group-btn">
                    <input type="submit" class="btn btn-primary" onclick="changeName()" value="Valid">
                </span>
            </div>
        </form>
    </div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>

<script>
    $(function () {
        $(".source, .target").sortable({
            connectWith: ".connected"
        });
        $(".source, .target").bind('sortstop', function(e, ui) {
            $('.btn-primary').removeClass('disabled')
        });
    });

    function changeName() {
        $.ajax({
            url: ".",
            type : 'POST',
            dataType:'json',
            data: {"change_name" : change_name}
        })
        .done (function(data) { 
            if(data.status == 'success'){
                location.reload();
            } else if(data.status == 'error'){
                alert("<?php echo getLabel("label.manage_report.error_query"); ?>");
            }
        })
        .fail(function(){ alert("<?php echo getLabel("label.manage_report.error_delete"); ?>"); });
    };

    function upload() {
        /************************************************* à coder ***************************************/
    }
</script>

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

  $db->close();
?>

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

include("../../footer.php"); 
?>