<?php

  include("../../include/config.php");
  
  
  global $database_eonweb;
  global $database_host;
  global $database_username;
  global $database_password;
  
  $db = new mysqli($database_host, $database_username, $database_password, $database_eonweb);
  
  if($db->connect_errno > 0){
      $response_array['status'] = 'error';
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
    <link rel="stylesheet" type="text/css" href="./this_style.css" />
  </head>
  

 <body>
    <div class="container">        
          <h2 class="form-signin-heading">Report edition</h2>
          <div class="well well-sm">
           
          <select id="ReportList" name="ReportList" size="1">
            <?php
              $grp_id= $_COOKIE['group_id'];
             
	      if ($_COOKIE['active_tab'] == 5){
		 $sql ="SELECT * 
                     FROM join_report_cred 
                     INNER JOIN reports ON reports.report_id = join_report_cred.report_id 
                     WHERE group_id='".$grp_id."' and reports.type = 'technic';";
	      }
	      else { 
                $sql ="SELECT * 
                     FROM join_report_cred 
                     INNER JOIN reports ON reports.report_id = join_report_cred.report_id 
                     WHERE group_id='".$grp_id."' and reports.type != 'technic';";
	      }
              
              if(!$result = $db->query($sql)){
                  die('There was an error running the query [' . $db->error . ']');
              }
              while($row = $result->fetch_assoc()){ 
                echo "<option selected=\"selected\" value=\"".$row['report_rptfile']."___".$row['report_name']."\">".$row['report_name']."</option>";
              }
            ?>
          </select>
          <p>
          
          </p>
          <p>
          <input name="visualizeReport" id="ButtonGenerate" type="button" value="Generate" />
          </p>
      </div>
    </div>
</body>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../bower_components/jqueryui/jquery-ui.min.js"></script>

<script type="text/javascript">  
  $('#ButtonGenerate').on('click', function () {
    var value = $('#ReportList').val().split("___");
    var selReport = value[0];
    var nameReport = value[1];
    var url = "./generate.php?reportrpt=" + selReport + "&report_name="+nameReport;
    $(location).attr('href', url);
    //window.open('./generate.php?reportrpt='+selReport,'_self',false);
  });
</script>

</html>
