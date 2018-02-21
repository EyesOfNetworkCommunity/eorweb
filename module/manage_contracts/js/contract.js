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

moment.locale('fr');

$(document).ready(function() {
	$('.input-group-addon').hover(function() {
        $(this).css('cursor','pointer');
    });
	if (UrlParam('id_number') != false){
		$.get(
			'./php/view_entry.php',
			{
				table_name: 'contract',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
        		$('#desc').val(values['ALIAS']);
				$('#contract_sdm_intern').val(values['CONTRACT_SDM_INTERN']);
				$('#contract_sdm_extern').val(values['CONTRACT_SDM_EXTERN']);
				$('#id_company').val(values['ID_COMPANY']);
				$('#extern_contract_id').val(values['EXTERN_CONTRACT_ID']);
				$('#validity_date').val(values['VALIDITY_DATE']);

				$.get(
					'./php/select_name_by_id.php',
				  {
					table_name: 'company',
					id_number: values['ID_COMPANY']
				  },
				  function return_name(name){
					$('#name_company').html(name['NAME']+'  <span class="caret"></span></button>');
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
			table_name:'company',
			id: 'ID_COMPANY'
		},
		function ReturnName(values){
			// if(values.length == 0){
			// 	document.getElementById('global_form').innerHTML = "<?php// message(12, getLabel("message.manage_contracts.contract_required_company"), "critical"); ?>";
			// 	setTimeout(function(){
			// 		document.getElementById('global_form').innerHTML = "";
			// 		},
			// 		5000
			// 	);
			// }

			// else{
				$('#global_form').css("display", "block");
				$.each(values, function(v, k){
					$name = k['NAME'];
					$id = k['ID_COMPANY'];
					$('#ul_company').append('<li><a class="dropdown-item" id="'+$name+'_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $name + '</a></li>');
				});
			// }
		},
		'json'
	);

	$('#submit').click(function(event){
		event.preventDefault();
		if (UrlParam('id_number') != false){
			$.get(
				'./php/update_entry.php',
				{
					table_name: 'contract',
					id_number: UrlParam('id_number'),
					name: $('#name').val(),
					alias: $('#desc').val(),
					contract_sdm_intern: $('#contract_sdm_intern').val(),
					contract_sdm_extern: $('#contract_sdm_extern').val(),
					id_company: $('#id_company').val(),
					extern_contract_id: $('#extern_contract_id').val(),
					validity_date: $('#validity_date').val()
				},
				function ShowMsg(value){
					if (value == "true"){
						DisplayAlertSuccess(dictionnary["message.manage_contracts.contract_saved"],"ok","#global_form","contract_view.php");
					}
					else if (value == "false"){
						DisplayAlert(dictionnary["message.error.required_fields"],"critical","#global_form");
					}
					else {
						DisplayAlert("Unknown Error","critical","#global_form");
					}
				}
			);
		}

		else{
			$.get(
				'./php/new_entry.php',
				{
					table_name: 'contract',
					name: $("#name").val(),
					alias: $("#desc").val(),
					contract_sdm_intern: $("#contract_sdm_intern").val(),
					contract_sdm_extern: $("#contract_sdm_extern").val(),
					id_company: $("#id_company").val(),
					extern_contract_id: $("#extern_contract_id").val(),
					validity_date: $("#validity_date").val()
				},
				function GotoContextView(value){
					if (value == "true"){
						DisplayAlertSuccess(dictionnary["message.manage_contracts.contract_saved"],"ok","#global_form","contract_view.php");
					}
					else if (value == "false"){
						DisplayAlert(dictionnary["message.error.required_fields"],"critical","#global_form");
					}
					else {
						DisplayAlert("Unknown Error","critical","#global_form");
					}
				}
			);
		}
	});
});

$('#validity_date').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    weekStart: 1,
    singleDatePicker: true,
	drops: "up"
});

function ChangeValue(value){
	$array_name_id = value.split("_");
	$name_company = $array_name_id[0];
	$id_company = $array_name_id[1];
	$('#name_company').html($name_company+'  <span class="caret"></span></button>');
	$('#id_company').val($id_company);
}
