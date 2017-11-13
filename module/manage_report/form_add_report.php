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
          <h2 class="form-signin-heading">Declare new report</h2>
          <div class="well well-sm">
          
                <div class="form-group">
                  <label for="rpt_name">Report name:</label>
                  <input type="text" class="form-control" id="rpt_name">
                </div>
                <div class="form-group">
                  <label for="rpt_filename">Report rpt file name (without path):</label>
                  <input type="text" class="form-control" id="rpt_filename">
                </div>

           <button class="btn btn-sm btn-primary btn-block" id="AddButton">Add report to database</button>
           </div>
  </div> <!-- /container -->
</body>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../bower_components/jqueryui/jquery-ui.min.js"></script>


  
<script type="text/javascript">  
  $('#AddButton').on('click', function (StoreMessage) {
      rpt_name = document.getElementById('rpt_name').value;
      rpt_filename = document.getElementById('rpt_filename').value;
      
      if (rpt_name == '') {
        alert("Report name not Set!");
        location.reload();
        return;
      }
      if (rpt_filename == '') {
        alert("Report file name not Set!");
        location.reload();
        return;
      }
      
      $.ajax({
                url: "add_report.php",
                type : 'POST',
                dataType:'json',
                data: {
                    "rpt_name" : rpt_name,
                    "rpt_filename" : rpt_filename
                   },
            })
            .done (function(data) { 
              //alert("Return: " + data.status); 
              if(data.status == 'success'){
                  window.open ('./index.php','_self',false);
              }else if(data.status == 'error'){
                  alert("Error on query!");
              }
            })
            .fail   (function()     { alert("Impossible to reach or execution failed of add_report.php")   ; });
  });
</script>
</html>

<?php
  
  $db->close();
?>
