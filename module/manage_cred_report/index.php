<?php
/*
#########################################
#
# Copyright (C) 2015 EyesOfNetwork Team
# DEV NAME : Michael Aubertin
# VERSION 4.2
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

<?php
  include("../../include/include_module.php");
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

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="refresh" content="10800">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Michael Aubertin">
    <link rel="icon" href="../../favicon.ico">

    <title>Report Administration</title>

    <!-- Core CSS -->
    <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/message.css" rel="stylesheet">
    <link href="./css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link href="./css/mysortable.css" rel="stylesheet" type="text/css">
    <link href="./css/mike_table.css" rel="stylesheet" type="text/css">
    
  </head>

  <body>
    <div class="container">        
          <h2 class="form-signin-heading">Report credentials</h2>
          <div class="well well-sm">
              <div class="table-responsive">          
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th width=5%>Edit</th>
                      <th>Report Name</th>
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
              </div>
           </div>
  </div> <!-- /container -->
</div>
</body>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../bower_components/jqueryui/jquery-ui.min.js"></script>

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
     $sql = "
            SELECT report_id 
            FROM reports ORDER BY report_rptfile;
        ";

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
</html>

<?php
  
  $db->close();
?>
