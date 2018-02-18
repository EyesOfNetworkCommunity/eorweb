<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Michael Aubertin
# VERSION 4.2
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

include("../../header.php");
include("../../side.php");
include("./function.php");
  
  global $database_vanillabp;
  global $database_host;
  global $database_username;
  global $database_password;

  $t_bp_racine = array();
  $HTMLTREE ="";
  $db = new mysqli($database_host, $database_username, $database_password, $database_vanillabp);

  if($db->connect_errno > 0){
    die('Unable to connect to database 4 [' . $db->connect_error . ']');
  }

// Note pour Benoit BP devra ici etre changé pour la base global.
  // Je filtre sur les 2 derniers layers d'apps pour des raisons de rapidité d'affichage et de sens fonctionnel.
  $sql = "SELECT name FROM bp WHERE name NOT IN (SELECT bp_link FROM bp_links) ORDER BY priority DESC";
  
  /* Block commented by Benoit Village 2016-04-05
	$sql = "SELECT name FROM bp WHERE name NOT IN (SELECT bp_link FROM bp_links) 
      AND ( priority = (select MAX(priority) from bp) OR priority = (select (MAX(priority)-1) from bp)) ORDER BY priority DESC";*/

  if(!$result = $db->query($sql)){
    die('There was an error running the query 5 [' . $db->error . ']');
  }

  while($row = $result->fetch_assoc()){   
    array_push($t_bp_racine,$row['name']);
  } 

  $result->free();
  mysqli_close($db);
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header"><?php echo getLabel("label.manage_contracts.contract_context_view_title"); ?></h1>
        </div>
    </div>
    <div class="row" id="body">
        <div class="col-md-12">
            <?php 
            for ($i = 0; $i < sizeof($t_bp_racine); $i++) {
                echo "<div class=\"well well-sm\" style=\"overflow-x:auto;\">";
                echo "<ul class=\"nav nav-list tree\">";
                display_bp($t_bp_racine[$i],$t_bp_racine[$i],$database_vanillabp);
                display_global_son($t_bp_racine[$i]);
                echo "</ul>";
                echo "</div>";
            } ?>
        </div>
    </div>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../bower_components/jquery-ui/jquery-ui.min.js"></script>


<script>
$(document).ready(function () {
  $('.tree-toggle').hover(function() {
    $(this).css('cursor','pointer');
  });
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

<?php
include("../../footer.php");
?>
