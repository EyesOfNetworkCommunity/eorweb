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
			table_name: 'step_group'
		},
		function return_values(values){
			$.each(values, function(v, k){
				$id = k['ID_STEP_GROUP'];
				$id_kpi = k['ID_KPI'];
				$name = k['NAME'];
				$step_number = k['STEP_NUMBER'];
				$type = k['TYPE'];
				$step_array = "";

				for (var i = 0; i < $step_number; i++){
					$number = i +1;
					$step_min = k['STEP_' +$number+ '_MIN'];
					$step_max = k['STEP_' +$number+ '_MAX'];
					if(i == 4){
						$step_array = $step_array + '    <span class="glyphicon glyphicon-option-horizontal" style="vertical-align:bottom"></span>';
						break;
					}

					$step_array = $step_array + '  <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-tag"></span> ' +$step_min+ $type +' ; ' +$step_max + $type +'</button>';
				}
				
				$global_array[$counter] = [$id,$name,$id_kpi,$step_array];
				$counter++;
			});

			$count = 0;
			for(var i = 0; i < $counter; i++){
				$.get(
					'./php/select_name_by_id.php',
					{
						table_name: 'kpi',
						id_number: $global_array[i+''][2]
					},
					function return_name(name){
						$id = $global_array[$count+''][0];
						$name = $global_array[$count+''][1];
						$kpi = name['NAME'];
						$step_values = $global_array[$count+''][3];

						$('#body_table').append('<tr><td><span class="glyphicon glyphicon-share-alt text-warning"></span></td><td>' + $name + '</td><td>' + $kpi + '</td><td>' + $step_values + '</td><td><button type="button" class="btn btn-primary" id="'+$id+'" onclick=EditSelection(id)><span class="glyphicon glyphicon-pencil"></span></button>  <button type="button" class="btn btn-danger" id="'+$id+'" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
        
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
	$(location).attr('href',"step_group.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove(dictionnary["message.manage_contracts.step_group_suppress"], "step_group", id, dictionnary["action.delete"],dictionnary["label.yes"],dictionnary["label.no"]);
}