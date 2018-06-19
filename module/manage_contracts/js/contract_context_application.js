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

	if ($("#name_contract_context").val() != "") {
		ChangeValue($("#name_contract_context").val());
	}

	$.get(
		'./php/get_name_id.php',
		{
			table_name:'contract_context',
			id: 'ID_CONTRACT_CONTEXT'
		},
		function ReturnName(values){
			if(values.length == 0){
				DisplayAlert(dictionnary["message.manage_contracts.application.no_context"],"warning","#global_form");
			} else {
				$.each(values, function(v, k){
					$name = k['NAME'];
					$id = k['ID_CONTRACT_CONTEXT'];
					$("#ul_context").append('<li><a class="dropdown-item" id="'+$name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $name + '</a></li>');
				});
			}
		},
		'json'
	);

  $.get(
		'./php/select_all_applications.php',
		function ReturnAllApplications(values){
			$.each(values, function(v, k){
				$name = k['name'];
				$("#ul_application").append('<li><a class="dropdown-item" id="'+$name+'" href="javascript:void(0);" onclick="ChangeApplication(id);">' + $name + '</a></li>');
			});
    	},
		'json'
	);

	$("#submit").click(function(event){
		event.preventDefault();
		$.get(
			'./php/new_entry.php',
			{
				table_name: 'contract_context_application',
				id_contract_context: $("#id_contract_context").val(),
				applications: $global_array
			},
			function GotoContextView(value){
				if (value == "true"){
					DisplayAlertSuccess(dictionnary["message.manage_contracts.contract_context_application_saved"],"ok","#global_form","contract_context_application_view.php");
				}
				else if (value == "false"){
					DisplayAlert(dictionnary["message.error.required_fields"],"critical","#global_form");
				}
				else {
					DisplayAlert("Unknown Error","critical","#global_form");
				}
			}
		);
	});

  $("#submit_entry").click(function(event){
		event.preventDefault();
		if($("#id_contract_context").val() == ""){
			DisplayAlert(dictionnary["message.manage_contracts.application.no_context_selected"],"warning","#global_form");
			return false;
		}
		if($("#application_name_hide").val() == ""){
			DisplayAlert(dictionnary["message.manage_contracts.application.no_application_selected"],"warning","#global_form");
			return false;
		} else {
			var status;
			$.each($global_array, function(v, k){
				if(k == $('#application_name_hide').val()){
					DisplayAlert(dictionnary["message.manage_contracts.application.already_add"],"warning","#global_form");
					status = 'stop';
					return false;
				}
			});
			if(status == 'stop'){
				return false;
			}
			$counter++;
			$name = $('#application_name_hide').val();
			if($("#application_list").is(':hidden')) {
				$("#application_list").show();
			}	
			if($('#container_application').is(':hidden')){
				$('#container_application').show();
			}
			$('#body_table').append('<tr id="'+$name+'"><td>' + $name + '</td><td><button type="button" class="btn btn-danger" id="'+$name+'" onclick=RemoveEntry(id)><span class="glyphicon glyphicon-remove"></span></button></td></tr>');
			$global_array[$counter] = $('#application_name_hide').val();
			$('#application_name').html(dictionnary["label.manage_contracts.contract_context_select_application"] +' <span class="caret"></span>');
		}
	});
});


$("#name_contract_context").click(function(event){
	$('#body_table').children().remove();
	$global_array = {};
	$counter=0;
	
	
});


function ChangeValue(value){
	$array_name_id = value.split("_-_");
	$name = $array_name_id[0];
	$id = $array_name_id[1];

	$("#application_list").show();	
	$("#name_contract_context").html($name+'  <span class="caret"></span></button>');
	$("#id_contract_context").val($id);
	$.get(
		'./php/select_application_by_context_id.php',
		{
			table_name: "contract_context_application",
			id: $id
		},
		function ReturnAllApplications(values){
			if($('#container_application').is(':hidden')){
				$('#container_application').css('display', 'inline');
			}
			$.each(values, function(v, k){
				$name = k['APPLICATION_NAME'];
				$('#body_table').append('<tr id="'+$name+'"><td>' + $name + '</td><td><button type="button" class="btn btn-danger" id="'+$name+'" onclick=RemoveEntry(id)><span class="glyphicon glyphicon-remove"></span></button></td></tr>');
				$counter++;
				$global_array[$counter] = $name;
			});
			$('#application_name').html(dictionnary["label.manage_contracts.contract_context_select_application"] +' <span class="caret"></span>');
		},
		'json'
	);
}

function ChangeApplication(app){
	$("#application_name").html(app+'  <span class="caret"></span></button>');
	$("#application_name_hide").val(app);
}

function RemoveEntry(value){
	$array = {};
	$index = 0;
	var count = $.map($global_array, function(n, i) { return i; }).length;

	$('tr[id="' + value +'"]').remove();

	for ($i = 1; $i <= count; $i++){
		if($global_array[$i] != value){
			$index++;
			$array[$index] = $global_array[$i];
			continue;
		}
	}

	$global_array = $array;
	$counter = $index;
}
