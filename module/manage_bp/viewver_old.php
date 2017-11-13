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
  global $database_vanillabp;
  global $database_host;
  global $database_username;
  global $database_password;

  $t_bp_racine = array();
  
  
  function display_bp($bp,$bp_racine)
    {
      
      global $database_vanillabp;
      global $database_host;
      global $database_username;
      global $database_password;
      $db = new mysqli($database_host, $database_username, $database_password, $database_vanillabp);

      if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
      }

      $rule_type = "";
      $desc_bp = "";
      $min_value = "";
      $priority = "";

      $sql_type = "
      SELECT type, description, min_value , priority
      FROM bp 
      WHERE name='".$bp."'
      ";

      if(!$result_type = $db->query($sql_type)){
          die('There was an error running the query [' . $db->error . ']');
      }

      while($row = $result_type->fetch_assoc()){   
        $rule_type = $row['type'];
        $desc_bp = $row['description'];
        $min_value = $row['min_value'];
        $priority = $row['priority'];
      } 

      if($min_value > 0) {
        $min_value = " ".$min_value;
      }

      $result_type->free();
      mysqli_close($db);

       print "\n";
       print "<label class=\"tree-toggle nav-header glyphicon-link glyphicon\"><font size=\"3\" color=\"black\">\n";
       print "<font size=\"1\" color=\"#262635\"> Display:".$priority."</font> [<b>".$rule_type.$min_value."</b>] ".$bp."</font>&nbsp;&nbsp;\n";
       print "<font size=\"1\" color=\"#262635\">(".$desc_bp.")</font></label>\n";
       echo "\n";
    }

  function display_service($host_service,$bp_racine)
    {
       print "\n";
       print "<ul>&nbsp;&nbsp;&nbsp;<label class=\"tree-toggle nav-header glyphicon glyphicon-eye-open\"> ".$host_service;
       print "</label></ul>";
       print "\n";
    }

  function display_son($bp_racine)
    {

      global $database_nagios;
      global $database_vanillabp;
      global $database_host;
      global $database_username;
      global $database_password;
      $db = new mysqli($database_host, $database_username, $database_password, $database_vanillabp);

      if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
      }

      $t_bp_son = array();
      $t_service_son = array();

      $sql_bp = "
      SELECT bp_link 
      FROM bp_links 
      WHERE bp_name = '".$bp_racine."'
      ";

      $sql_service = "
      SELECT host,service 
      FROM bp_services 
      WHERE bp_name = '".$bp_racine."'
      ";

      if(!$result_bp = $db->query($sql_bp)){
          die('There was an error running the query [' . $db->error . ']');
      }

      while($row = $result_bp->fetch_assoc()){   
        array_push($t_bp_son,$row['bp_link']);
      } 

      $result_bp->free();

      if(!$result_service = $db->query($sql_service)){
          die('There was an error running the query [' . $db->error . ']');
      }

      while($row = $result_service->fetch_assoc()){   
        array_push($t_service_son,$row['host'].";".$row['service']);
      }
      $result_service->free();
      mysqli_close($db);

      if(sizeof($t_bp_son) > 0 ) {
        for ($i = 0; $i < sizeof($t_bp_son); $i++) {
            echo "<ul class=\"nav nav-list tree\">";
            echo "\n";
            display_bp($t_bp_son[$i],$bp_racine);
            display_son($t_bp_son[$i]);
            echo "</ul>";
            echo "\n";
        }
      }
      if(sizeof($t_service_son) > 0 ) {
        for ($i = 0; $i < sizeof($t_service_son); $i++) {
            echo "<li>";
            display_service($t_service_son[$i],$bp_racine);
            echo "</li>\n";
        }
      }
    }


/// MAIN

  $HTMLTREE ="";
   
  $db = new mysqli($database_host, $database_username, $database_password, $database_vanillabp);

  if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
  }

// Note pour Benoit BP devrat ici etre changer pour la base global.
  // Je filtre sur les 2 derniers layers d'apps pour des raisons de rapidité d'affichage et de sens fonctionnel.
  $sql = "
      SELECT name 
      FROM bp 
      WHERE name 
      NOT IN (SELECT bp_link FROM bp_links) 
      AND ( priority = (select MAX(priority) from bp) OR priority = (select (MAX(priority)-1) from bp))
      ORDER BY priority DESC
  ";

  if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
  }
  while($row = $result->fetch_assoc()){   
    array_push($t_bp_racine,$row['name']);
  } 

  $result->free();
  mysqli_close($db);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="refresh" content="10800">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Application Tree</title>

    <!-- Core CSS -->
    <link href="./css/mike_tree.css" rel="stylesheet" type="text/css">
    <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

  </head>

  <body>
    <div class="container">
          <h2 class="form-signin-heading">Business process tree</h2>          
                    <div id="body">

                     

                                <?php 
                                    for ($i = 0; $i < sizeof($t_bp_racine); $i++) {
 
                                        echo "<div class=\"well well-sm\">";
                                        echo "<ul class=\"nav nav-list tree\">";
                                        display_bp($t_bp_racine[$i],$t_bp_racine[$i]);
                                        display_son($t_bp_racine[$i]);
                                        echo "</ul>";
                                        echo "</div>";
 
                                    }
                                ?>


                      
                  <!--  </div> -->
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
$(document).ready(function () {
	$('.tree-toggle').parent().children('ul.tree').toggle(300);
	$('.tree-toggle').click(function () {
		$(this).parent().children('ul.tree').toggle(200);
	});
$("[data-toggle=tooltip]").tooltip();
});

$('#FindIt').on('click', function (e) {
	 $('.tree-toggle').parent().children('ul.tree').toggle(1);
	 findInPage ("monastir");
});

</script>

</html>

