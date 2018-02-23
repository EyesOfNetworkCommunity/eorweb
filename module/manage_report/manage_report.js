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

function DisplayPopupRemove(text, id, title,Yes,No){
	$('body').append('<div id="popup_confirmation" title="' + title + '"></div>');
	$("#popup_confirmation").html(text);
	$("#popup_confirmation").dialog({
		autoOpen: false,
		buttons: [
			{
				text: Yes,
				click: function(){
					$.ajax({
						url: "delete_report.php",
						type : 'POST',
						dataType:'json',
						data: {"id" : id},
					})
					.done (function(data) { 
						if(data.status == 'success'){
							location.reload();
						} else {
							if(data.status == 'error'){
								alert(dictionnary["label.manage_report.error_query"]);
							}
						}
					})
					.fail(function(){ alert(dictionnary["label.manage_report.error_delete"]); });
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

function RemoveSelection(id){
	DisplayPopupRemove(dictionnary["message.manage_report.report_suppress"], id, dictionnary["action.delete"],dictionnary["label.yes"],dictionnary["label.no"]);
}

$(function () {
    $(".source, .target").sortable({connectWith: ".connected"});
    $(".source, .target").bind('sortstop', function(e, ui) {
    $('.btn-primary').removeClass('disabled')});
});

$(document).ready(function(){

	$('#AddButton').on('click', function (StoreMessage) {
		window.open ('./form_add_report.php','_self',false);
	});

	$('#AddButton_form').on('click', function (StoreMessage) {
		rpt_name = document.getElementById('rpt_name').value;
		rpt_filename = document.getElementById('rpt_filename').value;

		if (rpt_name == '') {
			DisplayAlert(dictionnary["label.form_report_name.not_set"],"critical","#message");
			return;
		}
		if (rpt_filename == '') {
			DisplayAlert(dictionnary["label.form_report_file.not_set"],"critical","#message");
			return;
		}

		$.ajax({
			url: "add_report.php",
			type : 'POST',
			dataType:'json',
			data: {
				"rpt_name" : rpt_name,
				"rpt_filename" : rpt_filename
			},
		})
		.done (function(data) { 
			if(data.status == 'success'){
				window.open ('./index.php','_self',false);
			} else {
				if(data.status == 'error'){
					alert("Error on query!");
				}
			}
		})
		.fail   (function()     { 
			alert("Impossible to reach or execution failed of add_report.php");
		});
	});

	// Remove report
	$(document).on('click', "#table-manage-report button[name=delete]", function(){
		RemoveSelection($(this).attr("id"));
	});
	
});
