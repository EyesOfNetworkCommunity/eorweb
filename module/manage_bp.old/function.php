<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Michael Aubertin
# VERSION 4.2
# APPLICATION : eorweb for eyesofreport project
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

// Récupère le nick_name d'une source.
// @param $db_name (String) -> Nom de la source.
// @return (String) -> Le nick_name de la source.
function getNickName($db_name) {
    global $database_vanillabp;
    $result = sqlrequest($database_vanillabp, "SELECT nick_name FROM bp_sources WHERE db_names='$db_name'");
    while ($line = mysqli_fetch_array($result)){
        $nick_name=$line[0];
    }
    return $nick_name;
}

//
// Récupère le thruk_idx d'une source.
// @param $source_name (String) -> Nom de la source choisie.
// @return (String) -> Le thruk_idx associé à la source.
function getThrukId($source_name) {
    global $database_vanillabp;
    $result = sqlrequest($database_vanillabp, "SELECT thruk_idx FROM bp_sources WHERE db_names='$source_name'");
    while ($line = mysqli_fetch_array($result)){
        $thruk_idx=$line[0];
    }
    return $thruk_idx;
}

function sqlArrayDatabase($database,$request) {
        $result = sqlrequest($database,$request);
        $values = array();
            for ($i=0; $i<mysqli_num_rows($result); ++$i) {
            $values[] = mysqli_fetch_assoc($result);
        }
        return $values ;
}

function deleteAll($tableBp,$Source){
    foreach($tableBp as $bp){
        $result = sqlArrayDatabase($Source,"SELECT bp_name FROM bp_links WHERE bp_link='$bp[bp_name]'");
        sqlrequest($Source,"DELETE FROM bp_links WHERE bp_name='$bp[bp_name]'");
        sqlrequest($Source,"DELETE FROM bp_services WHERE bp_name='$bp[bp_name]'");
        sqlrequest($Source,"DELETE FROM bp WHERE name='$bp[bp_name]'");
        deleteAll($result, $Source);
    }
}

function deleteOne($bp,$deleted,$Source){
    if ( $deleted != "" ) {
        $deleted .= ",$bp";
    } else {
        $deleted = "$bp";
    }
    sqlrequest($Source,"DELETE FROM bp_links WHERE bp_name='$bp'");
    sqlrequest($Source,"DELETE FROM bp_services WHERE bp_name='$bp'");
    sqlrequest($Source,"DELETE FROM bp WHERE name='$bp'");
    return $deleted;
}

function display_bp($bp,$bp_racine,$source_bp) {
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

    $sql_type = "SELECT type, description, min_value , priority FROM bp WHERE name='".$bp."'";

    if(!$result_type = $db->query($sql_type)){
        die('There was an error running the query 1 [' . $db->error . ']');
    }

    while($row = $result_type->fetch_assoc()){   
        $rule_type = $row['type'];
        $desc_bp = $row['description'];
        $min_value = $row['min_value'];
        $priority = $row['priority'];
    } 

    if ($min_value != "") {
        $min_value = " ".$min_value;
    }

    $result_type->free();
    mysqli_close($db);

    if ($bp != "") {
        print "<ul class=\"tree-toggle nav-header glyphicon-link glyphicon\">
                    <b class=\"condition_presentation\">".$bp." ".$desc_bp."</b> (".$rule_type."".$min_value.")
                </ul>";
        }
    }

function display_son($bp_racine,$bp_racine_source) {
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
    $sql_bp = "SELECT bp_link FROM bp_links WHERE bp_name = '".$bp_racine."'";
    $sql_service = "SELECT host,service FROM bp_services WHERE bp_name = '".$bp_racine."'";

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

    //print_r(mysqli_num_rows($result_service));

    while($row = $result_service->fetch_assoc()){   
        array_push($t_service_son,$row['host'].";".$row['service']);
    }
    $result_service->free();
    mysqli_close($db);

    if(sizeof($t_bp_son) > 0 ) {
        for ($i = 0; $i < sizeof($t_bp_son); $i++) {
            echo "<ul class=\"nav nav-list tree\">";
            display_bp($t_bp_son[$i],$bp_racine,$bp_racine_source);
            display_son($t_bp_son[$i],$bp_racine_source);
            echo "</ul>";
        }
    }
    if(sizeof($t_service_son) > 0 ) {
        for ($i = 0; $i < sizeof($t_service_son); $i++) {
            echo "<ul>";
            display_service($t_service_son[$i],$bp_racine);
            echo "</ul>";
        }
    }
}

function display_global_son($bp_racine) {
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

    $sql_bp = "SELECT bp_link, bp_source FROM bp_links WHERE bp_name = '".$bp_racine."'";

    if(!$result_bp = $db->query($sql_bp)){
        die('There was an error running the query 4 [' . $db->error . ']');
    }

    while($row = $result_bp->fetch_assoc()){   
        array_push($t_bp_son,$row['bp_link']);
        array_push($t_bp_son_source,$row['bp_source']);
    } 

    $result_bp->free();

    if(sizeof($t_bp_son) > 0 ) {
        for ($i = 0; $i < sizeof($t_bp_son); $i++) {
            echo "<ul class=\"s tree\">";
            display_bp($t_bp_son[$i],$bp_racine,$t_bp_son_source[$i]."_nagiosbp");
            if($t_bp_son_source[$i] == "global"){
                display_global_son($t_bp_son[$i]);
            }   
            else {
                display_son($t_bp_son[$i],$t_bp_son_source[$i]."_nagiosbp");
            }
            echo "</ul>\n";
        }
    }
}

function display_service($host_service,$name/*,$source_global,$source,$prio*/) {
    print "<ul><span class=\"nav-header glyphicon glyphicon-cog\" style=\"display: inline;\"> ".$host_service."</span></ul>";
}

function create_infra_access($bp_uname, $bp_source) {
    global $database_vanillabp;
    $name = $bp_uname;
    if ($bp_source == "global_nagiosbp") {
        $bp_source = "global"; 
    }

    sqlrequest($database_vanillabp, "INSERT INTO bp_category (bp_name,bp_source,category) VALUES ('".$name."_CI','".$bp_source."','Core Infrastructure')");
    sqlrequest($database_vanillabp, "INSERT INTO bp_category (bp_name,bp_source,category) VALUES ('".$name."_CA','".$bp_source."','Customer Access')");
    sqlrequest($database_vanillabp, "INSERT INTO bp (name,description, priority, type, command, url, min_value, is_define) VALUES ('".$name."_CI','".$name."_CI',5,'ET','','','',1)");
    sqlrequest($database_vanillabp, "INSERT INTO bp (name,description, priority, type, command, url, min_value, is_define) VALUES ('".$name."_CA','".$name."_CA',5,'ET','','','',1)");
    sqlrequest($database_vanillabp, "INSERT INTO bp_links (bp_name,bp_link, bp_source) VALUES ('".$name."','".$name."_CI','".$bp_source."')");
    sqlrequest($database_vanillabp, "INSERT INTO bp_links (bp_name,bp_link, bp_source) VALUES ('".$name."','".$name."_CA','".$bp_source."')");
    sqlrequest($database_vanillabp, "INSERT INTO bp_services (bp_name,bp_link, bp_source) VALUES ('".$name."','".$name."_CI','".$bp_source."')");
    sqlrequest($database_vanillabp, "INSERT INTO bp_services (bp_name,bp_link, bp_source) VALUES ('".$name."','".$name."_CA','".$bp_source."')");
}