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
  
                  
  $sql = "
              SELECT * 
              FROM reports WHERE report_id=".$report_id.";
          ";

          if(!$result = $db->query($sql)){
              die('There was an error running the query [' . $db->error . ']');
          }
          while($row = $result->fetch_assoc()){ 
            $report_name = $row['report_name'];
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
          <h2 class="form-signin-heading">Report credentials settings </h2>
          <h3><?php echo $report_name ?></h3>
          <div class="well well-sm">
              <div class="table-responsive">          
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>User group</th>
                      <th>Available</th>
                      <?php
                      $sql = "
                                  SELECT * 
                                  FROM output_format;
                              ";
        
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
                        $sql_group = " SELECT * 
                                        FROM groups;
                                    ";
                        if(!$result_group = $db->query($sql_group)){
                                        die('There was an error running the query [' . $db->error . ']');
                        }
                        while($row_group = $result_group->fetch_assoc()){
                             echo " <tr>\n"; 
                             echo "    <td id=\"group_id_".$row_group['group_id']."\">".$row_group['group_descr']."</td>\n";
                              /**************** Available (list from join_report_cred ****************/
                              $sql_report_avail = "SELECT * 
                                                    FROM join_report_cred 
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
                                $sql_format = "
                                            SELECT * 
                                            FROM output_format;
                                        ";
                  
                                        if(!$result_format = $db->query($sql_format)){
                                            die('There was an error running the query [' . $db->error . ']');
                                        }
                                        while($row_format = $result_format->fetch_assoc()){ 
                                          # Test current Settings....
                                          $sql_format_inDB = "SELECT * 
                                                              FROM join_report_format 
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
        /********* Global Avail *************/
        while (list ($key, $val) = each ($grp_avail) ) {
          echo "<script type=\"text/javascript\">\n";
          echo "$('#".$val."').on('click',function() {\n";
            echo " \n";  // row was clicked
            echo "window.open ('./update_report_cred.php?report_id=".$report_id."&tgt=".$val."','_self',false);";
            echo "});\n";
            echo "</script>\n";
        } 
        reset($grp_avail);
        
        /********* By Format ******************/
        while (list ($key, $val) = each ($grp_format) ) {
          echo "<script type=\"text/javascript\">\n";
          echo "$('#".$val."').on('click',function() {\n";
            echo " \n";  // row was clicked
            echo "window.open ('./update_report_cred.php?report_id=".$report_id."&tgt=".$val."','_self',false);";
            echo "});\n";
            echo "</script>\n";
        }
        reset($grp_format);


  ?>
</html>

<?php
  
  $db->close();
?>
