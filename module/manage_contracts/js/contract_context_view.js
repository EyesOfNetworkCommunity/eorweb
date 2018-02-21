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
			table_name: 'contract_context'
		},
		function return_values(values){
			$.each(values, function(v, k){
				$id = k['ID_CONTRACT_CONTEXT'];

				$global_array[$counter] = [$id];
				$counter++;
			});

			$count = 0;
			for(var i = 0; i < $counter; i++){
				$.get(
					'./php/get_values_contract_context_view.php',
					{
						id_number: $global_array[i+''][0]
					},
					function return_name(values){
						$name_context = values['0'];
						$alias = values['1'];
						$name_contract = values['2'];
						$name_time_period = values['3'];
						$name_kpi = values['4'];
						$name_step_group = values['5'];
						$id = $global_array[$count+''][0];

						$('#body_table').append('<tr id="'+$id+'"><td><span class="glyphicon glyphicon-share-alt text-warning"></span></td><td>' + $name_context + '</td><td>'+ $alias + '</td><td>' + $name_contract + '</td><td>' + $name_time_period + '</td><td>' + $name_kpi + '</td><td>' + $name_step_group + '</td><td><button type="button" class="btn btn-primary" id="'+$id+'" onclick=EditSelection(id)><span class="glyphicon glyphicon-pencil"></span></button>  <button type="button" class="btn btn-danger" id="'+$id+'" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
						$count++;
					},
					'json'
				);
			}
			$timer_update_table = ($counter /30) *1000
                        if ($timer_update_table < 200){
                                $timer_update_table = 200;
                        }
		},
		'json'
	);
});

function EditSelection(id){
	$(location).attr('href',"contract_context.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove(dictionnary["message.manage_contracts.contract_context_suppress"], "contract_context", id, dictionnary["action.delete"],dictionnary["label.yes"],dictionnary["label.no"]);
}
