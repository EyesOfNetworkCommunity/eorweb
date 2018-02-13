<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Michael Aubertin
# VERSION 2.1
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
   
  $db = new mysqli($database_host, $database_username, $database_password, $database_eonweb);

  if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
  }  
?>

<div id="page-wrapper">

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><?php echo getLabel("label.mgt_credential.title"); ?></h1>
    </div>
  </div>  
  <div class="table-responsive">          
    <table class="table table-striped">
      <thead>
        <tr>
          <th width=5%><?php echo getLabel("label.mgt_credential.tab_credentials_edit"); ?></th>
          <th><?php echo getLabel("label.mgt_credential.tab_credentials_report_name"); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
                  $sql = "
                      SELECT * 
                      FROM reports ORDER BY report_rptfile;
                  ";

                  if(!$result = $db->query($sql)){
                      die('There was an error running the query [' . $db->error . ']');
                  }
                  while($row = $result->fetch_assoc()){ 
                    echo " <tr>
                                <td id=\"row_report_".$row['report_id']."\"><span class=\"glyphicon glyphicon-pencil\"></td>
                                <td>".$row['report_name']."</td>
                          </tr>";
                  }
          ?>
      </tbody>
    </table>
  </div>

<?php include("../../footer.php"); ?>

<script type="text/javascript">
  $(function () {
    $(".source, .target").sortable({
      connectWith: ".connected"
    });
    $(".source, .target").bind('sortstop', function(e, ui) {
      $('.btn-primary').removeClass('disabled')
    });
  });
</script>


  <?php 
     $sql = "SELECT report_id FROM reports ORDER BY report_rptfile;";
        if(!$result = $db->query($sql)){
            die('There was an error running the query [' . $db->error . ']');
        }
        while($row = $result->fetch_assoc()){ 
            echo "<script type=\"text/javascript\">\n";
            echo "$('#row_report_".$row['report_id']."').on('click',function() {\n";
            echo " \n";  // row was clicked
            echo "window.open ('./form_edit_cred.php?report_id=".$row['report_id']."','_self',false);";
            echo "});\n";
	    echo "$('#row_report_".$row['report_id']."').hover(function(){\n";
            echo "$(this).css('cursor','pointer');";
            echo "});\n";
            echo "</script>\n";
        }
  ?>

<?php
  $db->close();
?>