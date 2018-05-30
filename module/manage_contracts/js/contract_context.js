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
				table_name: 'contract_context',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
				$('#id_contract').val(values['ID_CONTRACT']);
				$('#id_time_period').val(values['ID_TIME_PERIOD']);
				$('#id_kpi').val(values['ID_KPI']);
				$('#id_step_group').val(values['ID_STEP_GROUP']);

				$.get(
					'./php/select_name_by_id.php',
					{
						table_name: 'contract',
						id_number: values['ID_CONTRACT']
					},
					function return_name(name){
						$('#name_contract').html(name['NAME']+'  <span class="caret"></span></button>');
					},
				'json'
				);

				$.get(
				 	'./php/view_entry.php',
					{
						table_name: 'contract_context',
						id_number: values['ID_CONTRACT_CONTEXT']
					},
					function return_name(name){
						$('#desc').val(name['ALIAS']);
					},
				'json'
				);

				$.get(
					'./php/select_name_by_id.php',
					{
						table_name: 'time_period',
						id_number: values['ID_TIME_PERIOD']
					},
					function return_name(name){
						$('#name_time_period').html(name['NAME']+'  <span class="caret"></span></button>');
					},
					'json'
				);

				$.get(
					'./php/select_name_by_id.php',
					{
						table_name: 'kpi',
						id_number: values['ID_KPI']
					},
					function return_name(name){
						$('#name_kpi').html(name['NAME']+'  <span class="caret"></span></button>');
					},
				  'json'
				);

				$.get(
					'./php/select_name_by_id.php',
					{
						table_name: 'step_group',
						id_number: values['ID_STEP_GROUP']
					},
					function return_name(name){
						$('#name_step_group').html(name['NAME']+'  <span class="caret"></span></button>');
					},
					'json'
				);
			},
			'json'
		);
	}

	$counter = 0;
	$.get(
		'./php/get_name_id.php',
		{
			table_name:'contract',
			id: 'ID_CONTRACT'
		},
		function ReturnName(values){
			if(values.length == 0){
				$counter = $counter + 1;
			}

			else{
				$.each(values, function(v, k){
					$contract_name = k['NAME'];
					$id = k['ID_CONTRACT'];
					$('#ul_contract').append('<li><a class="dropdown-item" id="contract_-_'+$contract_name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $contract_name + '</a></li>');
				});
			}
		},
		'json'
	);

	$.get(
		'./php/get_name_id.php',
		{
			table_name:'kpi',
			id: 'ID_KPI'
		},
		function ReturnName(values){
			if(values.length == 0){
				$counter = $counter + 1;
			}

			else{
				$.each(values, function(v, k){
					$kpi_name = k['NAME'];
					$id = k['ID_KPI'];
					$('#ul_kpi').append('<li><a class="dropdown-item" id="kpi_-_'+$kpi_name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $kpi_name + '</a></li>');
				});
			}
		},
		'json'
	);

	$.get(
		'./php/get_name_id.php',
		{
			table_name:'step_group',
			id: 'ID_STEP_GROUP'
		},
		function ReturnName(values){
			if(values.length == 0){
				$counter = $counter + 1;
			} else {
				$.get(
					'./php/get_name_id.php',
					{
						table_name:'kpi',
						id: 'ID_KPI'
					}
				);
				$.each(values, function(v, k){
					$step_group_name = k['NAME'];
					$id = k['ID_STEP_GROUP'];
					$('#ul_step_group').append('<li><a class="dropdown-item" id="seuil_-_'+$step_group_name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $step_group_name + '</a></li>');
				});
			}
		},
		'json'
	);

	$.get(
		'./php/get_name_id.php',
		{
			table_name:'time_period',
			id: 'ID_TIME_PERIOD'
		},
		function ReturnName(values){
			$('#global_form').css("display", "block");
			$.each(values, function(v, k){
				$time_name = k['NAME'];
				$id = k['ID_TIME_PERIOD'];
				$('#ul_time').append('<li><a class="dropdown-item" id="time_-_'+$time_name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $time_name + '</a></li>');
			});
		},
		'json'
	);

	$('#submit').click(function(event){
		event.preventDefault();

		if (UrlParam('id_number') != false){
			$.get(
				'./php/update_entry.php',
				{
					table_name: 'contract_context',
					name: $('#name').val(),
					alias: $("#desc").val(),
					id_number: UrlParam('id_number'),
					id_contract: $('#id_contract').val(),
					id_time_period: $('#id_time_period').val(),
					id_kpi: $('#id_kpi').val(),
					id_step_group: $('#id_step_group').val()
				},
				function ShowMsg(value){
					if (value == "true"){
						DisplayAlertSuccess(dictionnary["message.manage_contracts.contract_context_saved"],"ok","#global_form","contract_context_view.php");
					}
					else if (value == "false"){
						DisplayAlert(dictionnary["message.error.required_fields"],"critical","#global_form");
					}
					else {
						DisplayAlert(dictionnary["message.error"],"critical","#global_form");
					}
				}
			);
		}

		else{
			$.get(
				'./php/new_entry.php',
				{
					table_name: 'contract_context',
					name: $("#name").val(),
					alias: $("#desc").val(),
					id_contract: $("#id_contract").val(),
					id_time_period: $("#id_time_period").val(),
					id_kpi: $("#id_kpi").val(),
					id_step_group: $('#id_step_group').val()
				},
				function GotoContextView(value){
					if (value == "true"){
						DisplayAlertSuccess(dictionnary["message.manage_contracts.contract_context_saved"],"ok","#global_form","contract_context_view.php");
					}
					else if (value == "false"){
						DisplayAlert(dictionnary["message.error.required_fields"],"critical","#global_form");
					} else {
						if (value == "no_right"){
							DisplayAlert(dictionnary["message.error.manage_contracts_contract_context_alredy_exist"],"critical","#global_form");
						} else {
                        	if (value == "no_right_2"){
								DisplayAlert(dictionnary["message.error.manage_contracts_time_period_reserved"],"critical","#global_form");
                            } else {
								DisplayAlert(dictionnary["message.error"],"critical","#global_form");
							}
						}
					}
				}
			);
		}
	});

});

function ChangeValue(value){
	$array_name_id = value.split("_-_");
	$object_name = $array_name_id[0]
	$name = $array_name_id[1];
	$id = $array_name_id[2];

	if($object_name == "contract"){
		$('#name_contract').html($name+'  <span class="caret"></span></button>');
		$('#id_contract').val($id);
	}
	else if($object_name == "kpi"){
		$('#name_kpi').html($name+'  <span class="caret"></span></button>');
		$('#id_kpi').val($id);
		$('#ul_step_group').empty();

		$.get(
			'./php/get_info_step_group.php',
				{
					id_kpi: $id
			},
			function ReturnName(values){
				$.each(values, function(v, k){
					$step_group_name = k['NAME'];
					$id = k['ID_STEP_GROUP'];
					$('#name_step_group').html(dictionnary["label.manage_contracts.sla_selection"] + '  <span class="caret"></span></button>');
					$('#ul_step_group').append('<li><a class="dropdown-item" id="seuil_-_'+$step_group_name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $step_group_name + '</a></li>');
					$('#id_step_group').val("");
				});
			},
			'json'
		);
	}
	else if($object_name == "time"){
		$('#name_time_period').html($name+'  <span class="caret"></span></button>');
		$('#id_time_period').val($id);
	}

	else if($object_name == "seuil"){
		$('#name_step_group').html($name+'  <span class="caret"></span></button>');
		$('#id_step_group').val($id);
		
		$.get(
			'./php/get_kpi_name.php',
			{
				id: $id
			},
			function return_name(name){
				$('#name_kpi').html(name[0]['NAME']+'  <span class="caret"></span></button>');
				$('#id_kpi').val(name[0]['ID_KPI']);
			},
		  'json'
		);
	}
}