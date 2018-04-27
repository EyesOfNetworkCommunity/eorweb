<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Jean-Philippe LEVY
# VERSION : 2.0
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

global $path_nagios_bin;
global $path_nagios_etc;

$array_msg = array (
	0 => "EOR - Standard Error ",
	1 => "EOR - Could not connect to Database ",
	2 => "EOR - Could not find file ",
	3 => "EOR - Could not write in file (verify access) ",
	4 => "EOR - Could not get the value in parameters : ",
	5 => "EOR - Error uploading file ",
	6 => "EOR - Operation successful",
	7 => "EOR - Form error ",
	8 => "EOR - User / Group ",
	9 => "EOR - Graph ",
	10 => "EOR - Name Error",
	11 => "EOR - GED");

$array_modules = array ("glpi","ocsinventory-reports");
	
$array_tools = array (
	"snmpwalk"		 => "tools/snmpwalk.php",
	"show interface" => "tools/interface.php",
	"show port" 	 => "tools/port.php");

$array_group_mgt = array (
    "label.admin_group.select_add" => "add_group",
	"label.admin_group.select_del" => "delete_group",
	"label.admin_group.select_import" => "import_user",
	);

$array_user_mgt = array (
	"label.admin_user.select_add" => "add_user",
	"label.admin_user.select_del" => "delete_user");

$array_bp_mgt = array (
	"add" 				=> "add_process",
	"delete" 			=> "delete_process",
	"delete on cascade" => "cascade_delete",
	"delete all" 		=> "delete_all",
	"duplicate" 		=> "duplicate",
	"back-up file" 		=> "backup");

$array_ged_queues = array("active","sync","history");
		
$array_ged_types = array(
	0 => "label.all",
	1 => "services",
	2 => "snmp trap",
	/* 3 => "performances"); */
);

$array_ged_packets = array (
	"equipment"			=>	array("type"=>true,"key"=>true,"col"=>true),
	"service"			=>	array("type"=>true,"key"=>true,"col"=>true),
	"state"				=>	array("type"=>true,"key"=>true,"col"=>true),
	"owner"				=>	array("type"=>true,"key"=>false,"col"=>true),
	"description"		=>	array("type"=>true,"key"=>false,"col"=>true),
	"ip_address"		=>	array("type"=>true,"key"=>false,"col"=>false),
	"host_alias"		=>	array("type"=>true,"key"=>false,"col"=>false),
	"hostgroups"		=>	array("type"=>true,"key"=>false,"col"=>false),
	"servicegroups"		=>	array("type"=>true,"key"=>false,"col"=>false),
	"comments"			=>	array("type"=>true,"key"=>false,"col"=>false),
	"original-time"		=>	array("type"=>false,"key"=>false,"col"=>true,"db_col"=>"o_sec"),
	"last-time"			=>	array("type"=>false,"key"=>false,"col"=>true,"db_col"=>"l_sec"),
	"acknowledge-time"	=>	array("type"=>false,"key"=>false,"col"=>false,"db_col"=>"a_sec"),
	"occurences"		=>	array("type"=>false,"key"=>false,"col"=>true,"db_col"=>"occ"),
	"source"			=>	array("type"=>false,"key"=>false,"col"=>false,"db_col"=>"src"),
	"type"				=>	array("type"=>false,"key"=>false,"col"=>false),
	"id"				=>	array("type"=>false,"key"=>false,"col"=>false)
);

$array_ged_filters = array (
	"equipment" 	=> "host",
	"service" 		=> "service",
	"description" 	=> "description",
	"hostgroups" 	=> "hostgroup",
	"servicegroups" => "servicegroup",
	"owner" 		=> "owner",
	"src"			=> "source"
);

$array_ged_states = array (
	"ok"		=>	"0",
	"warning"	=>	"1",
	"critical"	=>	"2",
	"unknown"	=>	"3",
);

$array_action_option = array(
	0 => "action.details",
	1 => "action.edit",
	2 => "action.own",
	3 => "action.disown",
	4 => "action.ack",
);

$array_resolve_action_option = array(
	0 => "action.details",
	5 => "action.delete",
);

$array_serv_system = array (
        "Docker" => array (
                "status" => "pidof -o $$ -o %PPID dockerd-current docker",
                "proc_act" => array (
                        "start" => "sudo /bin/systemctl start docker ; sudo /bin/systemctl status docker",
                        "stop" => "sudo /bin/systemctl stop docker ; sudo /bin/systemctl status docker",
                        "restart" => "sudo /bin/systemctl restart docker ; sudo /bin/systemctl status docker",
                        "reload" => "sudo /bin/systemctl reload docker ; sudo /bin/systemctl status docker")),
        "Pentaho" => array (
                "status" => "pidof -o $$ -o %PPID -x spoon.sh",
                "proc_act" => array (
                        "start" => "sudo /bin/systemctl start pentaho ; sudo /bin/systemctl status pentaho",
                        "stop" => "sudo /bin/systemctl stop pentaho ; sudo /bin/systemctl status pentaho",
                        "restart" => "sudo /bin/systemctl restart pentaho ; sudo /bin/systemctl status pentaho",
                        "reload" => "sudo /bin/systemctl reload pentaho ; sudo /bin/systemctl status pentaho")),
	"SNMP agent" => array (
		"status" => "pidof -o $$ -o %PPID -x snmpd",
		"proc_act" => array (
			"start" => "sudo /bin/systemctl start snmpd ; sudo /bin/systemctl status snmpd",
			"stop" => "sudo /bin/systemctl stop snmpd ; sudo /bin/systemctl status snmpd",
			"restart" => "sudo /bin/systemctl restart snmpd ; sudo /bin/systemctl status snmpd",
			"reload" => "sudo /bin/systemctl reload snmpd ; sudo /bin/systemctl status snmpd")),
        "Wildfly" => array (
                "status" => "pidof -o $$ -o %PPID -x standalone.sh",
                "proc_act" => array (
                        "start" => "sudo /bin/systemctl start wildfly ; sudo /bin/systemctl status wildfly",
                        "stop" => "sudo /bin/systemctl stop wildfly ; sudo /bin/systemctl status wildfly",
                        "restart" => "sudo /bin/systemctl restart wildfly ; sudo /bin/systemctl status wildfly",
			"reload" => "sudo /bin/systemctl reload wildfly ; sudo /bin/systemctl status wildfly")),
);

$ged_active_intervals = array(
	"day" 	=> time() - 60*5,
	"week" 	=> time() - 60*15,
	"month" => time() - 60*30,
	"year"	=> time() - 60*60,
);

$ged_history_intervals = array(
	"day" 	=> time() - 86400,
	"week" 	=> time() - 86400*7,
	"month" => time() - 86400*30,
	"year" 	=> time() - 86400*365,
);

$ged_sla_intervals = array(
	"first" 	=> 60*5,
	"second" 	=> 60*10,
	"third" 	=> 60*20,
	"fourth" 	=> "",
);

// sockets definition (for multi-backends !)
$sockets = array(
        "unix::-1:/srv/eyesofnetwork/nagios/var/log/rw/live"
        //"tcp:192.168.197.100:6557:",
        //"tcp:192.168.197.102:6557:"
);

?>
