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
	$radio_value = "";
	$short_name = "";

	if (UrlParam('id_number') != false){
		$('#display_unit_checkbox').css("display", "block");
		$('#display_interval').css("display", "inline");
    	$('#submit_interval').css("display", "block");
		$('#text_entry').css("display", "inline");
		$('#container_interval').css("display", "inline");

		$.get(
			'./php/view_entry.php',
			{
				table_name: 'step_group',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
				$('#id_kpi').val(values['ID_KPI']);
				$radio_value = values['TYPE'];
				$step_number = values['STEP_NUMBER'];

				for (var i = 0; i < $step_number; i++){
					$counter = $counter +1;
					$step_min = values['STEP_' +$counter+ '_MIN'];
					$step_max = values['STEP_' +$counter+ '_MAX'];

					// On camoufle le bouton de suppression de l'interval du dessus
                        		$previous_id_button = $counter -1 +'id';
                        		$current_id_button = $counter +'id';

                        		$('#' + $previous_id_button).css('display','none');

					$('#container_interval').append('<tr id="'+$counter+'"><td style="text-align:center">n°' + $counter + '</td><td style="text-align:center">' + $step_min + $radio_value + '</td><td style="text-align:center">' + $step_max + $radio_value + '</td><td style="text-align:center"><button type="button" class="btn btn-danger" id="'+$current_id_button+'" onclick=RemoveInterval(id)><span class="glyphicon glyphicon-remove"></span></button></td></tr>');

					var arr = [$step_min, $step_max];
					$global_array[$counter] = arr;
				}

				$('#interval_min').val($step_max);
                                                                        
				$.get(
					'./php/select_name_by_id.php',
					{
						table_name: 'kpi',
						id_number: values['ID_KPI']
					},
					function return_name(name){
						$id_number_kpi = name['NAME'] + "_" + values['ID_KPI'];
						ChangeValueSelected($id_number_kpi);
					},
					'json'
				);
			},
			'json'
		);
	}

	$.get(
		'./php/get_name_id.php',
		{
			table_name:'kpi',
			id: 'ID_KPI'
		},
		function ReturnName(values){
			if(values.length == 0){
				DisplayAlertMissing("Vous devez créer un Indicateur avant de pouvoir créer un groupe de seuils");
			}

			else{
				$('#global_form').css("display", "inline");
				$.each(values, function(v, k){
					$name = k['NAME'];
					$id = k['ID_KPI'];
					$('#ul_kpi').append('<li><a class="dropdown-item" id="'+$name+'_'+$id+'" href="javascript:void(0);" onclick="ChangeValueSelected(id);">' + $name + '</a></li>');
				});
			}
		},
		'json'
	);

	$('#submit').click(function(event){
		event.preventDefault();
		if (UrlParam('id_number') != false){
			$.get(
				'./php/update_entry.php',
				{
					table_name: 'step_group',
					name: $("#name").val(),
					id_kpi: $("#id_kpi").val(),
					id_number: UrlParam('id_number'),
					type: $radio_value,
					values_step: $global_array
				},
				function ShowMsg(value){
					switch (value) {
						case 'true':
							DisplayAlertSuccess(dictionnary["message.manage_contracts.step_group_saved"],"ok","#global_form","step_group_view.php");
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
					table_name: 'step_group',
					name: $("#name").val(),
					id_kpi: $("#id_kpi").val(),
					type: $radio_value,
					values_step: $global_array
					
				},
				function GotoContextView(value){
					switch (value) {
						case 'true':
							DisplayAlertSuccess(dictionnary["message.manage_contracts.step_group_saved"],"ok","#global_form","step_group_view.php");
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

	$('#submit_interval').click(function(event){
		event.preventDefault();
		if($('#interval_min').val() == "" || $('#interval_max').val() == "" || $radio_value == ""){
			return false;
		}

		else{
			$counter = $counter +1;

			if($counter > 4){
				return false;
			}

			$interval_min = parseFloat($('#interval_min').val());
			$interval_max = parseFloat($('#interval_max').val());

			if($interval_min >= $interval_max){
				DisplayAlert(dictionnary["message.error.wrong_step_group"],"critical","#global_form");
				return false;
			}

			if($radio_value == '%' && $interval_max > 100){
				DisplayAlert(dictionnary["message.error.wrong_step_group2"],"critical","#global_form");
				return false;
			}

			if($counter == 1){
				$('#name_kpi').prop('disabled', true);
				$('#interval_min').prop('readonly', true);
				if($radio_value == '%'){
					$('#unit_kpi').prop('disabled', true);
				}
				else{
					$('#unit_ratio').prop('disabled', true);
				}
			}
			$('#text_entry').show();
			$('#hidden_button').show();
			if($('#container_interval').is(':hidden')){
				$('#container_interval').css('display', 'block');
			}

			// On camoufle le bouton de suppression de l'interval du dessus
			$previous_id_button = $counter -1 +'id';
			$current_id_button = $counter +'id';

			$('#' + $previous_id_button).css('display','none');
			
			$('#container_interval').append('<tr id="'+$counter+'"><td style="text-align:center">n°' + $counter + '</td><td style="text-align:center">' + $interval_min + $radio_value + '</td><td style="text-align:center">' + $interval_max + $radio_value + '</td><td style="text-align:center"><button type="button" class="btn btn-danger" id="'+$current_id_button+'" onclick=RemoveInterval(id)><span class="glyphicon glyphicon-remove"></span></button></td></tr>');

			var arr = [$interval_min, $interval_max];

			$global_array[$counter] = arr;

			$('#interval_min').val($('#interval_max').val());
		}
	});

});

function CheckRadioButton(id_radio){
	if(id_radio == 'unit_kpi'){
		$('#interval_min').prop('readonly', false);
		$('#interval_min').val("");
		$radio_value = $short_name;
	}
	else if(id_radio == 'unit_ratio'){
		$('#interval_min').val(0);
		$('#interval_min').prop('readonly', true);
		$radio_value = '%';
	}
}

function ChangeValueSelected(values){
	$array_name_id = values.split("_");
    $name = $array_name_id[0];
    $id = $array_name_id[1];
    $('#name_kpi').html($name+'  <span class="caret"></span></button>');
    $('#id_kpi').val($id);

	$.get(
		'./php/get_shortname_unit.php',
		{
			id_unit: $('#id_kpi').val()
		},
		function ReturnNameUnit(name){
			$short_name = name['SHORT_NAME'];
			$('#label_unit_kpi').html('<input type="radio" name="optradio" id="unit_kpi" onchange="CheckRadioButton(id)">Unité ('+$short_name+') ');

			if (UrlParam('id_number') != false){
				if($radio_value == '%'){
					CheckRadioButton('unit_ratio');
					$('#unit_ratio').prop( "checked", true );
					$('#name_kpi').prop('disabled', true);
					$('#unit_kpi').prop('disabled', true);
				}
				else{
					CheckRadioButton('unit_kpi');
					$('#unit_kpi').prop( "checked", true );
					$('#unit_ratio').prop('disabled', true);
					$('#name_kpi').prop('disabled', true);
				}
			}
		},
		'json'
	);

	if($('#display_unit_checkbox').is(':visible')){
		return false;
	}
	else{
		$('#display_unit_checkbox').css("display", "block");
		$('#display_interval').css("display", "inline");
    	$('#submit_interval').css("display", "block");
		
	}
}

function ChangeValue(values){
	var array_values = values.split("_");
	var object_name = array_values[0];
	var value = array_values[1];

	if(object_name == 'min'){
		$('#interval_min').val(value);
	}
	else if(object_name == 'max'){
		$('#interval_max').val(value);
	}
}

function RemoveInterval(value){
	$tr_id_value = value[0];
	$('tr[id="' + $tr_id_value +'"]').remove();

	// On rend visible le bouton de suppression de l'interval du dessus
        $previous_id_button = parseInt($tr_id_value) -1 +'id';
        $('#' + $previous_id_button).css('display','inline');

	$array = {};
  $index = 0;
  var count = $.map($global_array, function(n, i) { return i; }).length;

	$('tr[id="' + value +'"]').remove();

	for ($i = 1; $i <= count; $i++){
		if($i != parseInt(value)){
			$index++;
			$array[$index] = $global_array[$i];
    		}
  	}

	$global_array = $array;
	$counter = $index;

	if(Object.keys($global_array).length == 0){
		$('#container_interval').css('display', 'none');
                $('#name_kpi').prop('disabled', false);
                if($radio_value == '%'){
                        $('#unit_kpi').prop('disabled', false);
			$('#interval_min').val(0);
                }
                else{
                        $('#unit_ratio').prop('disabled', false);
			$('#interval_min').val('');
			$('#interval_min').prop('readonly', false);
                }

        	$('#interval_max').val('');
        }

	else{
		$('#interval_min').val($global_array[$counter][1]);
		$('#interval_max').val('');
	}
}