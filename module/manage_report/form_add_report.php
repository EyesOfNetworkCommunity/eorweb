<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Michael Aubertin
# VERSION 2.0
# APPLICATION : eorweb for eyesofnetwork project
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

  if($db->connect_errno > 0){die('Unable to connect to database [' . $db->connect_error . ']');}
?>

<div id="page-wrapper">
  <div class="row">
    <div class="col-md-12">
      <h1 class="page-header"><?php echo getLabel("label.form_report.name");?></h1>
    </div>
  </div>
  <div class="col-md-12">
    <div  id="message"></div>
  </div>
  <div class="col-md-5">  
      <div class="form-group">
          <label for="rpt_name"><?php echo getLabel("label.form_report_name.name");?></label>
          <input type="text" class="form-control" id="rpt_name">
      </div>
      <div class="form-group">
          <label for="rpt_filename"><?php echo getLabel("label.form_report_name.file");?></label>
          <input type="text" class="form-control" id="rpt_filename">
      </div>
      <div class="col-md-6">
          <button class="btn btn-sm btn-primary btn-block" id="AddButton"><?php echo getLabel("label.form_report.button");?></button>
      </div>
  </div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>

<script>
  $('#AddButton').on('click', function (StoreMessage) {
    rpt_name = document.getElementById('rpt_name').value;
    rpt_filename = document.getElementById('rpt_filename').value;
    
    if (rpt_name == '') {
      document.getElementById("message").innerHTML = "<br><p class=\"alert alert-dismissible alert-danger fade in\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><i class=\"fa fa-exclamation-circle\"</i> <?php echo getLabel("label.form_report_name.not_set"); ?></p>";
      return;
    }
    if (rpt_filename == '') {
      document.getElementById("message").innerHTML = "<br><p class=\"alert alert-dismissible alert-danger fade in\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><i class=\"fa fa-exclamation-circle\"</i> <?php echo getLabel("label.form_report_file.not_set"); ?></p>";
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

<?php
  $db->close();
?>

<?php include("../../footer.php"); ?>