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

$(document).ready(function(){
	var counter = 0;
	$.get(
		'./php/check_if_entry.php',
		function returnNumber(number){
			if(parseInt(number[0]) == 0){
				$('#welcome').css("display", "inline");
			}
			else{
				$('#global_container').show();
				$.get(
					'./php/return_number_entry_all.php',
					function returnNumbers(numbers){
						var high_number = 0;
						for(var i=0; i < numbers.length; i++){
							var number = parseInt(numbers[i]);
							if(number > high_number){
								high_number = number;
							}
						}
						var one_unit = 100 / high_number;
						$('.skillbar').each(function(){
							//$(this).appear(function(){
								var longueur = numbers[parseInt(counter)] *one_unit;
								longueur = longueur + '%';
								$(this).attr('data-percent', numbers[parseInt(counter)]);
								counter++;
								$(this).find('.count-bar').animate({
									width:longueur
								},3000);
								var percent = $(this).attr('data-percent');
								$(this).find('.count').html('<span class="text-warning">' + percent + '</span>');
							//});
						});
					},
					'json'
				);

				$.get(
					'./php/display_entry.php',
					{
						table_name: 'last_entry ORDER BY 1 DESC'
					},
					function returnEntries(entries){
						$.each(entries, function(v, k){
							$last_entry_status = k['STATUS'];
							$last_entry_table_name = k['TABLE_NAME'];
							$last_entry_name = k['NAME'];
							$last_entry_date = k['DATE_ENTRY'];

							if($last_entry_status == 'New'){
								$class_to_add = "text-primary container-title";
							} else {
								$class_to_add = "text-warning container-title";
							}
							if($last_entry_table_name == 'contract_context'){
								$last_entry_table_name = 'Contexte de contrat';
							}
							else if($last_entry_table_name == 'contract'){
								$last_entry_table_name = 'Contrat';
							}
							else if($last_entry_table_name == 'company'){
								$last_entry_table_name = 'Entreprise';
							}
							else if($last_entry_table_name == 'contract_context_application'){
								$last_entry_table_name = 'Application';
							}
							else if($last_entry_table_name == 'kpi'){
								$last_entry_table_name = 'Indicateur';
							}
							else if($last_entry_table_name == 'step_group'){
								$last_entry_table_name = 'Groupe de seuils';
							}
							else if($last_entry_table_name == 'time_period'){
								$last_entry_table_name = 'Plage de service';
							}
				
							$('#body_table').append('<tr><td class="text-primary container-title">'+ $last_entry_status + '</td><td>' + $last_entry_table_name + '</td><td>' + $last_entry_name + '</td><td>' + $last_entry_date + '</td></tr>');
						});
					},
					'json'
				);
			}
		},
		'json'
	);
});
