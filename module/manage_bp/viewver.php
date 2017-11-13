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
  
  
  function display_bp($bp,$bp_racine,$source_bp)
    {
      
      global $database_vanillabp;
      global $database_host;
      global $database_username;
      global $database_password;
      $db = new mysqli($database_host, $database_username, $database_password, $source_bp);

      if($db->connect_errno > 0){
        die('Unable to connect to database 1 [' . $db->connect_error . ']');
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
          die('There was an error running the query 1 [' . $db->error . ']');
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
       print "<b class=\"condition_presentation\">".$rule_type.$min_value."</b> ".$desc_bp."</font>&nbsp;&nbsp;\n";
       print "<font size=\"1\" color=\"#262635\">(".$bp.")</font></label>\n";
       echo "\n";
    }

  function display_service($host_service,$bp_racine)
    {
       print "\n";
       print "<ul>&nbsp;&nbsp;&nbsp;<label class=\"tree-toggle nav-header glyphicon glyphicon-cog\"> ".$host_service;
       print "</label></ul>";
       print "\n";
    }

  function display_son($bp_racine,$bp_racine_source)
    {
      global $database_nagios;
      global $database_vanillabp;
      global $database_host;
      global $database_username;
      global $database_password;
      $db = new mysqli($database_host, $database_username, $database_password, $bp_racine_source);

      if($db->connect_errno > 0){
        die('Unable to connect to database 2 [' . $db->connect_error . ']');
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
          die('There was an error running the query 2 [' . $db->error . ']');
      }

      while($row = $result_bp->fetch_assoc()){   
        array_push($t_bp_son,$row['bp_link']);
      } 

      $result_bp->free();

      if(!$result_service = $db->query($sql_service)){
          die('There was an error running the query 3 [' . $db->error . ']');
      }
	  
	  print_r(mysql_num_rows($result_service));

      while($row = $result_service->fetch_assoc()){   
        array_push($t_service_son,$row['host'].";".$row['service']);
		//die('Bp services ['.$t_service_son,$row['host'].';'.$row['service']);
      }
      $result_service->free();
      mysqli_close($db);

      if(sizeof($t_bp_son) > 0 ) {
        for ($i = 0; $i < sizeof($t_bp_son); $i++) {
            echo "<ul class=\"nav nav-list tree\">";
            echo "\n";
            display_bp($t_bp_son[$i],$bp_racine,$bp_racine_source);
            display_son($t_bp_son[$i],$bp_racine_source);
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
	
function display_global_son($bp_racine)
    {

      global $database_nagios;
      global $database_vanillabp;
      global $database_host;
      global $database_username;
      global $database_password;
      $db = new mysqli($database_host, $database_username, $database_password, $database_vanillabp);

      if($db->connect_errno > 0){
        die('Unable to connect to database 3 [' . $db->connect_error . ']');
      }

      $t_bp_son = array();
	  $t_bp_son_source = array();
	  
	  $sql_bp = "
		SELECT bp_link, bp_source
		FROM bp_links 
		WHERE bp_name = '".$bp_racine."'
	  ";

      if(!$result_bp = $db->query($sql_bp)){
          die('There was an error running the query 4 [' . $db->error . ']');
      }

      while($row = $result_bp->fetch_assoc()){   
        array_push($t_bp_son,$row['bp_link']);
		array_push($t_bp_son_source,$row['bp_source']);
		//die('bp_link [' . $row["bp_link"] . ']');
		//die('bp_link [' . $row["bp_source"] . ']');
      } 

      $result_bp->free();

      if(sizeof($t_bp_son) > 0 ) {
        for ($i = 0; $i < sizeof($t_bp_son); $i++) {
            echo "<ul class=\"nav nav-list tree\">";
            echo "\n";
            
			display_bp($t_bp_son[$i],$bp_racine,$t_bp_son_source[$i]."_nagiosbp");
            
			if($t_bp_son_source[$i] == "global"){
				display_global_son($t_bp_son[$i]);
			}	
			else {
				display_son($t_bp_son[$i],$t_bp_son_source[$i]."_nagiosbp");
			}
            
			echo "</ul>";
            echo "\n";
        }
      }
    }


/// MAIN

  $HTMLTREE ="";
   
  $db = new mysqli($database_host, $database_username, $database_password, $database_vanillabp);

  if($db->connect_errno > 0){
    die('Unable to connect to database 4 [' . $db->connect_error . ']');
  }

// Note pour Benoit BP devra ici etre changé pour la base global.
  // Je filtre sur les 2 derniers layers d'apps pour des raisons de rapidité d'affichage et de sens fonctionnel.
  $sql = "	
      SELECT name 
      FROM bp 
      WHERE name 
      NOT IN (SELECT bp_link FROM bp_links)
      ORDER BY priority DESC
  ";
  
  /* Block commented by Benoit Village 2016-04-05
	$sql = "	
      SELECT name 
      FROM bp 
      WHERE name 
      NOT IN (SELECT bp_link FROM bp_links) 
      AND ( priority = (select MAX(priority) from bp) OR priority = (select (MAX(priority)-1) from bp))
      ORDER BY priority DESC
  ";*/

  if(!$result = $db->query($sql)){
    die('There was an error running the query 5 [' . $db->error . ']');
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
    <!-- <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'> -->
    <link href="./css/mike_tree.css" rel="stylesheet" type="text/css">
    <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">

  </head>

  <body>
    <div class="container">
          <h2 class="form-signin-heading">Business process tree</h2>          
                    <div id="body">

                     

                                <?php 
                                    for ($i = 0; $i < sizeof($t_bp_racine); $i++) {
 
                                        echo "<div class=\"well well-sm\">";
                                        echo "<ul class=\"nav nav-list tree\">";
                                        display_bp($t_bp_racine[$i],$t_bp_racine[$i],'global_nagiosbp');
                                        display_global_son($t_bp_racine[$i]);
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


