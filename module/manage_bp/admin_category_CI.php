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
?>

<?php
include("../../header.php");
include("../../side.php");

$db = new mysqli($database_host, $database_username, $database_password, $database_vanillabp);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}  
?>

<link href="./design.css" rel="stylesheet" type="text/css">

<div id="page-wrapper">
    <div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Category Core Infrastructure</h1>
		</div>
    </div>

    <h5 style="color:#FFA500"> <span class="glyphicon glyphicon-info-sign" style='font-size: 1.2em'> </span> Only business processes used by at least one global business process can be set as category.</h5>

    <div class="row">
        <div class="col-md-4">
            <button class="btn btn-md btn-primary btn-block disabled" id="StoreButton">Apply this configuration</button>
        </div>
    </div>
    <br>
    <div class="panel panel-default">
        <div style="overflow-y: auto; overflow-x: hidden; height: 800px;"> 
            <div class="row">
                <div class="col-xs-6">
                    <?php
                    $sql = "SELECT db_names FROM bp_sources;";
                    if(!$result = $db->query($sql)){
                        die('There was an error running the query [' . $db->error . ']');
                    }
                    while($row = $result->fetch_assoc()){ 
                        $bp_source_uniq=substr($row['db_names'], 0, -9); // remove _nagiosbp to the name
                        echo "
                        <div class=\"col-md-12\">
                            <h4>Business Process available from source: ".$bp_source_uniq."</h4> 
                        </div>
                        <div class=\"col-md-12\">
                            <div id=\"".$bp_source_uniq."\">
                                <div style=\"overflow-y: auto; overflow-x: hidden; height: 200px;\">                          
                                    <ul class=\"source connected\" id=\"ul_".$bp_source_uniq."\">";
                                        $sql_bp = "SELECT DISTINCT bp_link FROM bp_links WHERE bp_source = '".$bp_source_uniq."'
                                        AND (bp_link like '%_ci' OR bp_link like '%_CI' )
                                        AND bp_link NOT IN (SELECT bp_name FROM bp_category WHERE category = 'Core Infrastructure' 
                                        AND bp_source =  '".$bp_source_uniq."' )  
                                        ORDER BY bp_link";
                                        if(!$result_bp = $db->query($sql_bp)){
                                            die('There was an error running the query [' . $db->error . ']');
                                        }
                                        while($row_bp = $result_bp->fetch_assoc()){ 
                                            echo "<li>".$bp_source_uniq."-->".$row_bp['bp_link']."</li>";
                                        } 
                                        $result_bp->free();
                                    echo "
                                    </ul>
                                </div>
                            </div>
                        </div>";
                    }
                    $result->free();
                    ?>
                </div>
                <div class="col-xs-6">
                    <div class="col-md-12">
                        <h4>Business process set to Category Core Infrastructure</h4>
                    </div>
                    <div class="col-md-12">
                        <ul class="target connected">
                            <?php
                            $sql = "SELECT * FROM bp_category WHERE category = 'Core Infrastructure' ORDER BY bp_source";
                            if(!$result = $db->query($sql)){
                                die('There was an error running the query [' . $db->error . ']');
                            }
                            while($row = $result->fetch_assoc()){ 
                               echo "<li>\n".$row['bp_source']."-->".$row['bp_name']."</li>";
                            } 
                            $result->free();
                            ?>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <h4>Drop zone</h4>
                        <ul class="source connected"></ul>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../bower_components/jquery-ui/jquery-ui.min.js"></script>

<script>
    $(function () {
        $(".source, .target").sortable({
            connectWith: ".connected"
        });
        $(".source, .target").bind('sortstop', function(e, ui) {
            $('.btn-primary').removeClass('disabled')
        });
    });

    $('#StoreButton').on('click', function (StoreMessage) {
        var cat = "Core Infrastructure";
        var datamessage = "";
        $("ul.target").children().each(function() {
            datamessage = datamessage + $(this).text();
            datamessage = datamessage + "|";
        });

        // jQuery ajax post data to php script http://api.jquery.com/jQuery.ajax/
        $.ajax({
            url: "setCategorie.php",
            type : 'POST',
            dataType:'json',
            data: {
                "cat" : cat,
                "message" : datamessage
            },
        })
        .done (function(data) { 
            //alert("Return: " + data.status); 
            if(data.status == 'success'){
                $('.btn-primary').addClass('disabled');
                location.reload();
            } else if(data.status == 'error'){
                alert("Error on query!");
            }
        })
        .fail (function() { alert("Impossible to reach or execution failed of setCategorie.php")   ; });
        });
</script>

<?php
$db->close();
include ("../../footer.php");
?>
