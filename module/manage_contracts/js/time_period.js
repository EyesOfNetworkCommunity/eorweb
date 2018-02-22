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
	
	if (UrlParam('id_number') != false){
		$('#input_list').css('display', 'block');
		$('#container_time_period').css('display', 'inline');
		$.get(
			'./php/view_entry.php',
			{
				table_name: 'time_period',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
				$('#desc').val(values['ALIAS']);
			},
			'json'
		);
		$.get(
			'./php/get_values_timeperiod_entry.php',
			{
				table_name: 'timeperiod_entry',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$.each(values, function(v, k){
					$counter++;
					$get_entry = $eor_days[k['ENTRY']],
					$get_start_hour_full = k['H_OPEN'];
					$get_end_hour_full = k['H_CLOSE'];
					$get_start_hour = $get_start_hour_full.split(":")[0];
					$get_start_min = $get_start_hour_full.split(":")[1];
					$get_end_hour = $get_end_hour_full.split(":")[0];
					$get_end_min = $get_end_hour_full.split(":")[1];

					$('#body_table').append('<tr id="'+$counter+'"><td>' + $get_entry + '</td><td>' + $get_start_hour + ':' +$get_start_min + '</td><td>' + $get_end_hour + ':' + $get_end_min + '</td><td><button type="button" class="btn btn-danger" id="'+$counter+'" onclick=RemoveEntry(id)><span class="glyphicon glyphicon-remove"></span></button></td></tr>');

					var arr = [k['ENTRY'],$get_start_hour,$get_start_min,$get_end_hour,$get_end_min];

					$global_array[$counter] = arr;
				});
			},
			'json'
		);
	}

	AddDays();

	for(var i= 0; i < 24; i++){
		if(i < 10){
			i = '0' + i;
		}
		$('#ul_start_hour').append('<li><a class="dropdown-item" id="starthour_'+i+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + i + '</a></li>');
		$('#ul_end_hour').append('<li><a class="dropdown-item" id="endhour_'+i+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + i + '</a></li>');
	}

	for (var i= 0; i < 60; i++){
		if(i < 10){
			i = '0' + i;
		}
		$('#ul_start_min').append('<li><a class="dropdown-item" id="startmin_'+i+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + i + '</a></li>');
		$('#ul_end_min').append('<li><a class="dropdown-item" id="endmin_'+i+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + i + '</a></li>');
	}

	$('#submit').click(function(event){
		event.preventDefault();
		if (UrlParam('id_number') != false){
			$.get(
				'./php/update_entry.php',
				{
					table_name: 'time_period',
					name: $('#name').val(),
					alias: $("#desc").val(),
					values: $global_array,
					id_number: UrlParam('id_number')
				},
				function ShowMsg(value){
					switch (value) {
						case 'true':
							DisplayAlertSuccess(dictionnary["message.manage_contracts.time_period_saved"],"ok","#global_form","time_period_view.php");
							break;
						case 'false':
							DisplayAlert(dictionnary["message.error.required_fields"],"critical","#global_form");
							break;
						default:
							DisplayAlert("Unknown Error","critical","#global_form");
					}
				}
			);
		}

		else{
			$.get(
				'./php/new_entry.php',
				{
					table_name: 'time_period',
					name: $("#name").val(),
					alias: $("#desc").val(),
					values: $global_array
				},
				function GotoContextView(value){
					switch (value) {
						case 'true':
							DisplayAlertSuccess(dictionnary["message.manage_contracts.time_period_saved"],"ok","#global_form","time_period_view.php");
							break;
						case 'false':
							DisplayAlert(dictionnary["message.error.required_fields"],"critical","#global_form");
							break;
						default:
							DisplayAlert("Unknown Error","critical","#global_form");
					}
        			return false;
				}
			);
		}
	});

	$('#submit_entry').click(function(event){
		event.preventDefault();
		if($('#name_entry_hide').val() == "" || $('#start_hour_hide').val() == "" || $('#start_min_hide').val() == "" || $('#end_hour_hide').val() == "" || $('#end_min_hide').val() == ""){
			DisplayAlert(dictionnary["message.error.required_fields"],"critical","#global_form");
			return false;
		} else {
			if($('#start_hour_hide').val() > $('#end_hour_hide').val()){
				DisplayAlert(dictionnary["message.error.bad_values"],"critical","#global_form");
				return false;
			}
			if($('#start_hour_hide').val() == $('#end_hour_hide').val() && $('#start_min_hide').val() >= $('#end_min_hide').val()){
				DisplayAlert(dictionnary["message.error.bad_values"],"critical","#global_form");
				return false;
			}

			var status;
			$.each($global_array, function(v, k){
				if(k[0] == $('#name_entry_hide').val()){
					if($('#start_hour_hide').val() < k[3] || $('#start_hour_hide').val() == k[3] && $('#start_min_hide').val() <= k[4]){
						DisplayAlert(dictionnary["message.error.bad_period"],"critical","#global_form");
						status = 'stop';
						return false;
					}
				}
			});

			if(status == 'stop'){
				return false;
			}
      
      		$counter++;
      		$("#input_list").show();
			if($('#container_time_period').is(':hidden')){
				$('#container_time_period').css('display', 'block');
			}
			$('#body_table').append('<tr id="'+$counter+'"><td>' + $('#name_entry_hide').val() + '</td><td>' + $('#start_hour_hide').val() + ':' + $('#start_min_hide').val() + '</td><td>' + $('#end_hour_hide').val() + ':' + $('#end_min_hide').val() + '</td><td><button type="button" class="btn btn-danger" id="'+$counter+'" onclick=RemoveEntry(id)><span class="glyphicon glyphicon-remove"></span></button></td></tr>');

			var arr = [Object.keys($eor_days).find(key => $eor_days[key] === $('#name_entry_hide').val()), $('#start_hour_hide').val(), $('#start_min_hide').val(), $('#end_hour_hide').val(), $('#end_min_hide').val()];

			$global_array[$counter] = arr;
			$('#name_entry').html(dictionnary["label.contracts_menu.period_create_day"]+' <span class="caret"></span>');
		}
	});

});

function AddDays(value_day){
	var days = [ dictionnary["label.contracts_menu.period_create_day_value_monday"], dictionnary["label.contracts_menu.period_create_day_value_tuesday"], 
	dictionnary["label.contracts_menu.period_create_day_value_wednesday"], dictionnary["label.contracts_menu.period_create_day_value_thursday"],
	 dictionnary["label.contracts_menu.period_create_day_value_friday"], dictionnary["label.contracts_menu.period_create_day_value_saturday"],
	  dictionnary["label.contracts_menu.period_create_day_value_sunday"] ];

	for(var i= 0; i < days.length; i++){
		var day = days[i];

		if(value_day == day){
			continue;
		}
		
		$('#ul_entry').append('<li id="day_'+day+'"><a class="dropdown-item" id="day_'+day+'" href="javascript:void(0);" onclick="ChangeValue(id);">' + day + '</a></li>');
	}
}
	

function ChangeValue(values){
	var array_values = values.split("_");
	var object_name = array_values[0];
	var value = array_values[1];

	if(object_name == 'day'){
		$('#name_entry').html(value + ' <span class="caret"></span>');
		$('#name_entry_hide').val(value);
	}
	else if(object_name == 'starthour'){
		$('#start_hour').html(value + ' <span class="caret"></span>');
		$('#start_hour_hide').val(value);
	}
	else if(object_name == 'startmin'){
		$('#start_min').html(value + ' <span class="caret"></span>');
		$('#start_min_hide').val(value);
	}
	else if(object_name == 'endhour'){
		$('#end_hour').html(value + ' <span class="caret"></span>');
		$('#end_hour_hide').val(value);
	}
	else if(object_name == 'endmin'){
		$('#end_min').html(value + ' <span class="caret"></span>');
		$('#end_min_hide').val(value);
	}
}

function RemoveEntry(value){
  $array = {};
  $index = 0;
  var count = $.map($global_array, function(n, i) { return i; }).length;

	$('tr[id="' + value +'"]').remove();

  for ($i = 1; $i <= count; $i++){
    if($i != parseInt(value)){
      $index++;
      $array[$index] = $global_array[$i];
      continue;
    }
  }

	$global_array = $array;
	$counter = $index;
}