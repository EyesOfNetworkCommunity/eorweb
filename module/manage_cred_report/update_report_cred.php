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
  
  $report_id = $_GET['report_id'];
  if (!isset($report_id)) {
        die('Unable to connect to database [' .$report_id. ']');
  }
  
  $tgt = $_GET['tgt'];
  if (!isset($tgt)) {
        die('Unable to connect to database [' .$tgt. ']');
  }
  
  $tgt_array = explode("_", $tgt);
  
  if ($tgt_array[2] == "format") {
          $sql_report_avail = "SELECT * 
                              FROM join_report_format 
                              WHERE output_format_id=".$tgt_array[4]."
                              AND report_id=".$report_id.";";
                              
          if(!$result_report_avail = mysqli_query($db,$sql_report_avail)){
            die('There was an error running the query [' . $db->error . ']');
          }
          $num_rows = mysqli_num_rows($result_report_avail);
          if ($num_rows > 0){ 
            #si pas vide je delete.
            $sql_add = "DELETE FROM join_report_format WHERE output_format_id=".$tgt_array[4]." AND report_id=".$report_id.";";
  
            if(!$result_sql = $db->query($sql_add)){
                die('There was an error running the DELETE query [' . $db->error . ']');
            }
          } else {
            #si vide je set.
            $sql_add = "INSERT INTO join_report_format values ('','".$report_id."','".$tgt_array[4]."');";
  
            if(!$result_sql = $db->query($sql_add)){
                die('There was an error running the INSERT query [' . $db->error . ']');
            }
          }
  } elseif ($tgt_array[2] == "avail") {
        # Test si vide.
         $sql_report_avail = "SELECT * 
                              FROM join_report_cred 
                              WHERE group_id=".$tgt_array[1]."
                              AND report_id=".$report_id.";";
                              
          if(!$result_report_avail = mysqli_query($db,$sql_report_avail)){
            die('There was an error running the query [' . $db->error . ']');
          }
          $num_rows = mysqli_num_rows($result_report_avail);
          if ($num_rows > 0){ 
            #si pas vide je delete.
            $sql_add = "DELETE FROM join_report_cred WHERE group_id=".$tgt_array[1]." AND report_id=".$report_id.";";
  
            if(!$result_sql = $db->query($sql_add)){
                die('There was an error running the DELETE query [' . $db->error . ']');
            }
          } else {
            #si vide je set.
            $sql_add = "INSERT INTO join_report_cred values ('".$report_id."','".$tgt_array[1]."');";
  
            if(!$result_sql = $db->query($sql_add)){
                die('There was an error running the INSERT query [' . $db->error . ']');
            }
          }
          
  } else {
      echo "Target Not supported. And message not visible :P.";
  }
  
  $db->close();
  
  

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
    
    </head>

  <body onload="InitLoad()">
  
  </body>
<script type="text/javascript">  
function InitLoad() {
      <?php
        //echo "alert(\"Redirect\");";
        echo "window.open ('./form_edit_cred.php?report_id=".$report_id."','_self',false);";
      ?>
  }
</script>
</html>
