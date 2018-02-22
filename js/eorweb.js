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

// UGLY FRENCH TRADUCTION FOR TIMEPERIOD ENTRIES
var $eor_days = new Object();
$eor_days["lundi"]=dictionnary["label.contracts_menu.period_create_day_value_monday"];
$eor_days["mardi"]=dictionnary["label.contracts_menu.period_create_day_value_tuesday"];
$eor_days["mercredi"]=dictionnary["label.contracts_menu.period_create_day_value_wednesday"];
$eor_days["jeudi"]=dictionnary["label.contracts_menu.period_create_day_value_thursday"];
$eor_days["vendredi"]=dictionnary["label.contracts_menu.period_create_day_value_friday"];
$eor_days["samedi"]=dictionnary["label.contracts_menu.period_create_day_value_saturday"];
$eor_days["dimanche"]=dictionnary["label.contracts_menu.period_create_day_value_sunday"];
// ----------------------------------------------

function message(msg, type, target){
	
	switch (type) {
		case 'critical':
			type = "danger";
			icon = "fa-exclamation-circle";
			break;
		 case 'warning':
			icon = "fa-warning";
			break;
		default:
			type = "success";
			icon = "fa-check-circle";
	}
	
    var message = ''
        +'<p class="alert alert-dismissible alert-'+type+'">'
        +   '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
        +       '<span aria-hidden="true">&times;</span>'
        +   '</button>'
        +   '<i class="fa '+icon+'"> </i> '+ msg
        +'</p>';
    $(target).html(message);
}

function UrlParam(name){
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results == null){
		return false;
	}
	return results[1];
}

function DisplayAlertSuccess(msg,type,target,url){
	message(msg,type,target);
	setTimeout(function(){
		$(location).attr('href', url);
		},5000
	);
}

function DisplayAlert(msg,type,target){
	message(msg,type,target);
	setTimeout(
		function(){
			$(target).empty();
		},5000
	);
}
