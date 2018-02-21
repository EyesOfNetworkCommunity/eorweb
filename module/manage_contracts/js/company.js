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

$(document).ready(function() {
	if (UrlParam('id_number') != false){
		$.get(
			'./php/view_entry.php',
			{
				table_name: 'company',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
			},
			'json'
		);
	}
	$('#submit').click(function(event){
		event.preventDefault();

		if (UrlParam('id_number') != false){
			$.get(
				'./php/update_entry.php',
				{
					table_name: 'company',
					name: $('#name').val(),
					id_number: UrlParam('id_number')
				},
				function GotoView(value){
					switch (value) {
						case 'true':
							DisplayAlertSuccess(dictionnary["message.manage_contracts.company_saved"],"ok","#global_form","company_view.php");
							break;
						case 'false':
							DisplayAlert(dictionnary["message.error.required_fields"],"critical","#global_form");
							break;
						case 'already exist':
							DisplayAlert(dictionnary["message.error.company_exists"],"critical","#global_form");
						break;
						default:
							DisplayAlert("Unknown Error","critical","#global_form");
					}
				}
			);
		} else {
			$.get(
				'./php/new_entry.php',
				{
					table_name: 'company',
					name: $("#name").val()
				},
				function GotoView(value){
					switch (value) {
						case 'true':
							DisplayAlertSuccess(dictionnary["message.manage_contracts.company_saved"],"ok","#global_form","company_view.php");
							break;
						case 'false':
							DisplayAlert(dictionnary["message.error.required_fields"],"critical","#global_form");
							break;
						case 'already exist':
							DisplayAlert(dictionnary["message.error.company_exists"],"critical","#global_form");
						break;
						default:
							DisplayAlert("Unknown Error","critical","#global_form");
					}
				}
			);
		}
	});
});
