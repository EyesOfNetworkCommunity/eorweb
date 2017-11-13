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
  
  $reportrpt = $_GET['reportrpt'];
  if (!isset($reportrpt)) {
        die('Unable to read expected report name.');
  }

  $grp_id= $_COOKIE['group_id'];
  $sql ="SELECT * 
         FROM join_report_cred 
         INNER JOIN reports ON reports.report_id = join_report_cred.report_id 
         WHERE group_id='".$grp_id."';";
  
  if(!$result = $db->query($sql)){
      die('There was an error running the query [' . $db->error . ']');
  }
  /*while($row = $result->fetch_assoc()){ 
    $report_name=$row['report_name'];
  }*/
$report_name=$_GET['report_name'];
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
          <h3><?php echo $report_name; ?></h3>
          <div class="well well-sm">
           
          <select id="FormatList" name="FormatList" size="1">
            <?php            
              $sql ="SELECT report_name,output_format.type 
                     FROM join_report_format 
                     INNER JOIN reports ON reports.report_id = join_report_format.report_id 
                     INNER JOIN output_format ON join_report_format.output_format_id = output_format.format_id 
                     WHERE reports.report_name='".$report_name."';";
              
              if(!$result = $db->query($sql)){
                  die('There was an error running the query [' . $db->error . ']');
              }
              while($row = $result->fetch_assoc()){ 
                echo "<option selected=\"selected\" value=\"".$row['type']."\">".$row['type']."</option>";
              }
            ?>         
          </select>
          <p>
          
          </p>
          <p>
            <input name="visualizeReport" id="RunBirt" type="button" value="Perform your report" />
          </p>

      </div>
    </div>
</body>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../bower_components/jqueryui/jquery-ui.min.js"></script>

<script type="text/javascript">  
  $('#RunBirt').on('click', function () {

    var selReport="<?php echo $reportrpt ?>";
    var selFormat=document.getElementById('FormatList');
    var reqformat=selFormat.options[selFormat.selectedIndex].value;
    var srvname="<?php echo $_SERVER['SERVER_NAME'] ?>";
    
    //alert ("../birt/run?__report='+selReport+'&__format='+reqformat,'_self");
    
    window.open('http://'+srvname+'/birt/run?__report='+selReport+'&__format='+reqformat,'_self',false);
  });
</script>

</html>
