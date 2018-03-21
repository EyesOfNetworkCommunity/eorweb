/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Bastien PUJOS
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
	// Add
	$('#rule_host_button').on('click',function(){
		var o = new Option($("#rule_host1").val(),$("#rule_host1").val(),true,true);
		$("#remediation_actions_id").append(o);
	});

	// Delete
	$('#rule_host_button_del').on('click',function(){
		$("#remediation_actions_id").find('option:selected').remove();
		$("#remediation_actions_id").find("option").attr('selected','selected');
	});

	$('#source').autocomplete({
		source: './php/auto_completion.php',
		select: function(event, ui){
			source_name = ui.item.value;
			
			$('#host').autocomplete({ 				
				source: './php/auto_completion.php?source_type=hosts&source_name='+source_name,
				select: function(event, di){
					val = di.item.value;
					
					$('#service').autocomplete({ 
						source: './php/auto_completion.php?source_host='+val+'&source_type=services&source_name='+source_name
					});
				}
			});
		}
	});
});

		