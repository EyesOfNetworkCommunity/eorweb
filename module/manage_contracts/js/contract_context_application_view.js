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

	$global_array = {};
	$counter = 0;

	$.get(
		'./php/display_entry.php',
		{
			table_name: 'contract_context_application'
		},
		function return_values(values){
			$.each(values, function(v, k){
				$id = k['ID_CONTRACT_CONTEXT'];
				$name_application =k['APPLICATION_NAME'];
				$global_array[$counter] = [$id,$name_application];
				$counter++;
			});

			$.each($global_array, function(element, value) {
				$.get(
					'./php/get_values_contract_context_application_view.php',
					{
						table_name: 'contract_context',
						id_number: value[0]
					},
					function return_names(names){
						$contract_name = names['contract'];
						$time_period_name = names['time_period'];
						$kpi_name = names['kpi'];
						$step_group_name = names['step_group'];
						$contract_context = names['contract_context'];
						$id_context = names['context_id'];

						$id = value[0];
						$name_application = value[1];

						$('#body_table').append('<tr><td><span class="glyphicon glyphicon-share-alt text-warning"></span>\
							</td><td>' + $name_application + '</td><td>' + $contract_name + '</td><td>' + $time_period_name + '</td>\
							<td>' + $kpi_name + '</td><td>' + $step_group_name + '</td>\
							<td><a class="btn btn-primary" href="./contract_context_application.php?context_name='+ $contract_context + '_-_' + $id_context + '" role="button"><span class="glyphicon glyphicon-pencil"></span></a></td>\
							<td><button type="button" class="btn btn-danger" id="'+$name_application+'" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
					},
					'json'
				);
			}
			);
		},
		'json'
	);
});

function RemoveSelection(application_name){
	DisplayPopupRemove(dictionnary["message.manage_contracts.contract_context_application_suppress"], "contract_context_application", application_name, dictionnary["action.delete"],dictionnary["label.yes"],dictionnary["label.no"]);
}
