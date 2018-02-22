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
				table_name: 'kpi',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
				$('#id_unit_comput').val(values['ID_UNIT_COMPUT']);
				$('#id_unit_presentation').val(values['ID_UNIT_PRESENTATION']);
                                                                        
				$.get(
				  './php/select_name_by_id.php',
				  {
					table_name: 'unit',
					id_number: values['ID_UNIT_COMPUT']
				  },
				  function return_name(name){
					$('#name_unit_comput').html(name['NAME']+'  <span class="caret"></span></button>');
				  },
				  'json'
				);
		
				$.get(
				  './php/select_name_by_id.php',
				  {
					table_name: 'unit',
					id_number: values['ID_UNIT_PRESENTATION']
				  },
				  function return_name(name){
					$('#name_unit_presentation').html(name['NAME']+'  <span class="caret"></span></button>');
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
			table_name:'unit',
			id: 'ID_UNIT'
		},
		function ReturnName(values){
			if(values.length == 0){
				$('#global_form').css("display", "none");
				DisplayAlertMissing("Vous devez créer une fiche d'unité de mesure avant de pouvoir créer un Indicateur");
			}

			else{
				$('#global_form').css("display", "block");
				$.each(values, function(v, k){
					$name = k['NAME'];
					$id = k['ID_UNIT'];
					$('#ul_comput').append('<li><a class="dropdown-item" id="comput_'+$name+'_'+$id+'" href="javascript:void(0);" onclick="ChangeValue(id);">' + $name + '</a></li>');
					$('#ul_presentation').append('<li><a class="dropdown-item" id="presentation_'+$name+'_'+$id+'" href="javascript:void(0);" onclick="ChangeValue(id);">' + $name + '</a></li>');
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
					table_name: 'kpi',
					name: $('#name').val(),
					id_number: UrlParam('id_number'),
					id_unit_comput: $('#id_unit_comput').val(),
					id_unit_presentation: $('#id_unit_presentation').val()
				},
				function ShowMsg(value){
					switch (value) {
						case 'true':
							DisplayAlertSuccess(dictionnary["message.manage_contracts.kpi_saved"],"ok","#global_form","kpi_view.php");
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
					table_name: 'kpi',
					name: $("#name").val(),
					id_unit_comput: $("#id_unit_comput").val(),
					id_unit_presentation: $("#id_unit_presentation").val()
				},
				function GotoContextView(value){
					switch (value) {
						case 'true':
							DisplayAlertSuccess(dictionnary["message.manage_contracts.kpi_saved"],"ok","#global_form","kpi_view.php");
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
	});

});

function ChangeValue(value){
	$array_name_id = value.split("_");
	$object_name = $array_name_id[0]
	$name = $array_name_id[1];
	$id = $array_name_id[2];

	if($object_name == "comput"){
		$('#name_unit_comput').html($name+'  <span class="caret"></span></button>');
		$('#id_unit_comput').val($id);
	}
	else if($object_name == "presentation"){
		$('#name_unit_presentation').html($name+'  <span class="caret"></span></button>');
		$('#id_unit_presentation').val($id);
	}
}