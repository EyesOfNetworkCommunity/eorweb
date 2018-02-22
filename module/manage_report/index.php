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

  global $database_eorweb;
  global $database_host;
  global $database_username;
  global $database_password;
  global $path_rptdesign;
   
  $db = new mysqli($database_host, $database_username, $database_password, $database_eorweb);

  if($db->connect_errno > 0){
    die("echo getLabel(\"label.manage_report.query_error\")" . $db->connect_error . ']');
  }  
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">    
            <h1 class="page-header"><?php echo getLabel("label.manage_report.report_declaration");?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive form-group">          
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?php echo getLabel("label.manage_report.column_report_name");?></th>
                            <th><?php echo getLabel("label.manage_report.column_filename");?></th>
                            <th class="col-sd-1"><?php echo getLabel("label.actions");?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM reports ORDER BY report_rptfile;";
                        if(!$result = $db->query($sql)){
                            die("echo getLabel(\"label.manage_report.query_error\")". $db->error . ']');
                        }
                        while($row = $result->fetch_assoc()){
                            echo " <tr>
                                    <td>".$row['report_name']."</td>
                                    <td>".$path_rptdesign."/".$row['report_rptfile']."</td>
                                    <td>
                                        <button type=\"button\" class=\"btn btn-primary\" id=\"row_edit_".$row['report_id']."\">
                                        <span class=\"glyphicon glyphicon-pencil\"></span></button>
                                        <button type=\"button\" class=\"btn btn-danger\" id=\"row_report_".$row['report_id']."\">
                                        <span class=\"glyphicon glyphicon-trash\"></span></button>
                                    </td>
                            </tr>";
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <button class="btn btn-primary" id="AddButton"><?php echo getLabel("label.manage_report.add_report");?></button>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>

<?php 
    $sql = "SELECT report_id FROM reports ORDER BY report_rptfile;";
    if(!$result = $db->query($sql)){
        die("echo getLabel(\"label.manage_report.query_error\")". $db->error . ']');
    }
    while($row = $result->fetch_assoc()){ 
        echo "<script>\n";
        echo "$('#row_report_".$row['report_id']."').on('click',function() {\n";
        echo " \n";  // row was clicked
        echo "RemoveSelection(".$row['report_id'].");";
        echo "});\n";
        echo "$('#row_report_".$row['report_id']."').hover(function(){\n";
        echo "$(this).css('cursor','pointer');";
        echo "});\n";
        echo "</script>\n";
    }

    $sql = "SELECT report_id FROM reports ORDER BY report_rptfile;";
    if(!$result = $db->query($sql)){
        die("echo getLabel(\"label.manage_report.query_error\")". $db->error . ']');
    }
    while($row = $result->fetch_assoc()){ 
        echo "<script>\n";
        echo "$('#row_edit_".$row['report_id']."').on('click',function() {\n";
        echo " \n";  // row was clicked
        echo "window.open ('../manage_cred_report/form_edit_cred.php?report_id=".$row['report_id']."','_self',false);";
        echo "});\n";
        echo "$('#row_edit_".$row['report_id']."').hover(function(){\n";
        echo "$(this).css('cursor','pointer');";
        echo "});\n";
        echo "</script>\n";
    }


$db->close();
include("../../footer.php");
?>