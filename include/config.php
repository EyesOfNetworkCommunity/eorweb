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

// #######################################
// # General Information
// #######################################
$version="2.2";

// #######################################
// # Database config information
// #######################################
$database_host="localhost";
$database_port="3306";

$database_username="eyesofreport";
$database_password="SaintThomas,2014";
$database_thruk="thruk";
$database_eorweb="eorweb";
$database_nagios="nagiosbp";
$database_vanillabp="global_nagiosbp";

// ###################################################
// # EyesOfReport
// ###################################################

// # Default language format
$langformat="en";

// # Logs options
$dateformat="M j, Y g:i:s A";
$datepurge="-1 month";

// # Menu Config
// You can view tabid in eorweb database
$defaulttab=1;
$defaultpage="./module/birt_apps/index.php";

// # Max number of lines in a tablesorter
$maxlines=500;

// # Page refresh interval
$refresh_time=60;

// Max Display value
$max_display=5;

// Display 0 or not ; Use it like a boolean with values 0/1
$display_zero=1;
 
// Minimun and maximun number for duplicate process.
$min_dup = 1000;
$max_dup = 9999;

// # Cookie domain
$cookie_domain="";

// # Cookie destroy time
$cookie_time=0;
// 4 hour : $cookie_time=4*60*60;

// LDAP
$ldap_search_begins=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9','0','\\28');

// Number of back-up file to use for nagios configuration file.
$max_bu_file = 5;

// # Define All Path
$path_eon="/srv/eyesofnetwork";
$path_eor="/srv/eyesofreport";
$path_eorweb="$path_eon/eorweb";
$path_frame="/module/module_frame/index.php?url=";
$dir_imgcache="cache";
$path_images="/images";
$path_logo="$path_images/logo.png";
$path_logo_custom="$path_images/custom.logo.png";
$path_logo_favicon="$path_images/favicon.png";
$path_logo_favicon_custom="$path_images/custom.favicon.png";
$path_logo_navbar="$path_images/logo-navbar.png";
$path_logo_navbar_custom="$path_images/custom.logo-navbar.png";
$path_languages="$path_eorweb/include/languages";
$path_messages="$path_languages/messages";
$path_messages_custom="$path_languages/custom.messages";
$path_menus="$path_languages/menus";
$path_menus_custom="$path_languages/custom.menus";
$path_menu_limited="$path_languages/menus-limited";
$path_menu_limited_custom="$path_languages/custom.menus-limited";
$path_reports="$path_eorweb/include/reports";
$path_rptdesign="$path_eor/report";

// # NetCAT
$default_minport=1;
$default_maxport=1024;

// # nagios
$path_nagiosbpcfg="/srv/eyesofnetwork/eorweb/module/manage_bp/compat/nagios-bp.conf";
$path_nagiosbpcfg_lock="/tmp/nagios_bp";
$path_nagiosbpcfg_bu="/tmp/nagios-bp.conf";

?>
