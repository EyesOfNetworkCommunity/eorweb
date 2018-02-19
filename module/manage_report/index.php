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
  <div class="table-responsive">          
    <table class="table table-striped">
      <thead>
        <tr>
          <th class="col-sd-1"><?php echo getLabel("label.manage_report.delete");?></th>
          <th class="col-sd-1"><?php echo getLabel("label.edit");?></th>
          <th><?php echo getLabel("label.manage_report.column_report_name");?></th>
          <th><?php echo getLabel("label.manage_report.column_filename");?></th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * FROM reports ORDER BY report_rptfile;";
          if(!$result = $db->query($sql)){die("echo getLabel(\"label.manage_report.query_error\")". $db->error . ']');}
          while($row = $result->fetch_assoc()){
            echo " <tr>
                    <td><button type=\"button\" class=\"btn btn-danger\" id=\"row_report_".$row['report_id']."\">
                      <span class=\"glyphicon glyphicon-trash\"></span></button>
                    </td>
                    <td><button type=\"button\" class=\"btn btn-primary\" id=\"row_edit_".$row['report_id']."\">
                      <span class=\"glyphicon glyphicon-pencil\"></span></button>
                    </td>
                    
                    <td>".$row['report_name']."</td>
                    <td>".$path_rptdesign."/".$row['report_rptfile']."</td>
                  </tr>";
          }?>
      </tbody>
    </table>
  </div>
  <div class="col-md-2">
    <button class="btn btn-sm btn-primary btn-block" id="AddButton"><?php echo getLabel("label.manage_report.add_report");?></button>
  </div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>

<?php 
 $sql = "SELECT report_id FROM reports ORDER BY report_rptfile;";
  if(!$result = $db->query($sql)){die("echo getLabel(\"label.manage_report.query_error\")". $db->error . ']');}
  while($row = $result->fetch_assoc()){ 
    echo "<script>\n";
    echo "$('#row_report_".$row['report_id']."').on('click',function() {\n";
    echo " \n";  // row was clicked
    echo "myConfirm(".$row['report_id'].");";
    echo "});\n";
    echo "$('#row_report_".$row['report_id']."').hover(function(){\n";
    echo "$(this).css('cursor','pointer');";
    echo "});\n";
    echo "</script>\n";
    }
  
  $sql = "SELECT report_id FROM reports ORDER BY report_rptfile;";
  if(!$result = $db->query($sql)){die("echo getLabel(\"label.manage_report.query_error\")". $db->error . ']');}
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
?>

<script>  
  $(function () {
    $(".source, .target").sortable({connectWith: ".connected"});
    $(".source, .target").bind('sortstop', function(e, ui) {
      $('.btn-primary').removeClass('disabled')});
  });

  function myConfirm(id) {
    var x;
    if (confirm("<?php echo getLabel("label.manage_report.confirm_delete"); ?>" + id + "?") == true) {
    //alert("You pressed OK!" + id);
    $.ajax({
      url: "delete_report.php",
      type : 'POST',
      dataType:'json',
      data: {"id" : id},
      })
      .done (function(data) { 
        //alert("Return: " + data.status); 
        if(data.status == 'success'){
            location.reload();
        }else if(data.status == 'error'){
            alert("<?php echo getLabel("label.manage_report.error_query"); ?>");
        }
      })
      .fail(function(){ alert("<?php echo getLabel("label.manage_report.error_delete"); ?>"); });
    } 
  }

  $('#AddButton').on('click', function (StoreMessage) {
    window.open ('./form_add_report.php','_self',false);
  });
</script>

<?php 
  $db->close();
  include("../../footer.php");
?>