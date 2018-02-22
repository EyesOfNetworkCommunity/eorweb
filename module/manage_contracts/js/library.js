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

function DisplayPopupRemove(text, tablename, number, title,Yes,No){
	$('body').append('<div id="popup_confirmation" title="' + title + '"></div>');
	$("#popup_confirmation").html(text);
	$("#popup_confirmation").dialog({
		autoOpen: false,
		buttons: [
			{
				text: Yes,
				click: function(){
					ClickOnYes(tablename, number);
				}
			},
			{
				text: No,
				click: function () {
					$(this).dialog("close");
				}
			}
		]
	}).dialog("open");
}

function ClickOnYes(tablename, number){
	$.get(
		'./php/delete_entry.php',
		{
			table_name: tablename,
			id_number: number
		}
	);
	$("#popup_confirmation").dialog("close");
	$(location).attr('href',""+tablename+"_view.php");
}
