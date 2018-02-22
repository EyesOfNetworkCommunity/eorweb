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
	$counter = 0;
	$global_array = {};
	
	$.ajax({
		type: 'GET',
		async: false,
		url: './php/display_entry.php',
		dataType: 'json',
		data: {
			table_name: 'time_period'
		},
		success: function return_values(values){
			$.each(values, function(v, k){
				$id = k['ID_TIME_PERIOD'];
				$name =k['NAME'];
        
				$global_array[$counter] = [$id,$name];
				$counter++;
			});
     
			$count = 0;
			for(var i = 0; i < $counter; i++){
				$.ajax({
					type: 'GET',
					async: false,
					url: './php/get_values_timeperiod_entry.php',
					dataType: 'json',
					data: {
						table_name: 'timeperiod_entry',
						id_number: $global_array[i+''][0]
					},
					success: function return_time_period(time_period){
						$id = $global_array[$count+''][0];
						$name = $global_array[$count+''][1];
						$concatenation_time_period = "";
						$index = 0;
						$.each(time_period, function(v, k){
							$day = $eor_days[k['ENTRY']];
							$h_open = k['H_OPEN'];
							$h_close = k['H_CLOSE'];
							if($index == 4){
								$concatenation_time_period = $concatenation_time_period + '    <span class="glyphicon glyphicon-option-horizontal" style="vertical-align:bottom"></span>'
								return false;
							}

							$concatenation_time_period = $concatenation_time_period + '  <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-tag"></span> ' +$day+ ', ' +$h_open+ ' ' +$h_close+'</button>';

							$index++;
            			});
						$('#body_table').append('<tr><td><span class="glyphicon glyphicon-share-alt text-warning"></span></td><td>' + $name + '</td><td>'+$concatenation_time_period+'</td><td><button type="button" class="btn btn-primary" id="'+$id+'" onclick=EditSelection(id)><span class="glyphicon glyphicon-pencil"></span></button>  <button type="button" class="btn btn-danger" id="'+$id+'" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
						$count++;
					}
				});
			}
		}
	});
});

function EditSelection(id){
	$(location).attr('href',"time_period.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove(dictionnary["message.manage_contracts.time_period_suppress"], "time_period", id, dictionnary["action.delete"],dictionnary["label.yes"],dictionnary["label.no"]);
}
