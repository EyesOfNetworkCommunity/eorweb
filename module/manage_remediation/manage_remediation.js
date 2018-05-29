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
	
	var service_tab = [];
	// Delete
	$('#rule_host_button_del').on('click',function(){
		$("#remediation_actions_id").find('option:selected').remove();
		$("#remediation_actions_id").find("option").attr('selected','selected');
	});
	$('#service_button_del').on('click',function(){
		$("#service_id").find('option:selected').remove();
		$("#service_id").find("option").attr('selected','selected');
		service_tab = [];
	});
	
	source_name = $('#source').val();
	$('#source').on('change', function() {
	  source_name = this.value;
	})
	
	$('#group_name').on('click', function() {
		temp = this.value;
		service_name = temp.split(" - ");
		$('#service_update').val(service_name[1]);
	})

	// autocomplete for the host in remediation_action
	$("#host").on('focusin',function () {
		if (source_name != 'none'){
			$('#host').autocomplete({ 				
				source: './php/auto_completion.php?source_type=hosts&source_name='+source_name,
				minLength: 0
			});
		} else {
			$('#host').autocomplete({source: [""]});
		}
	});
	$("#host").on('click',function () {
		if (source_name != 'none'){
			$("#host").autocomplete("search","");
		}
		// if host is change, service is reset 
		if ($("#host").val() != "") {
			$("#service_id").find('option:selected').remove();
			$("#service_id").find("option").attr('selected','selected');
			service_tab = [];
		}
	});
	
	// autocomplete for the service in remediation_action
	$("#service").on('focusin',function () {
		val = $("#host").val();
		if (val != ""){
			$('#service').autocomplete({ 
				source: './php/auto_completion.php?source_host='+val+'&source_type=services&source_name='+source_name,
				minLength: 0,
				select: function(event, ui) {
					var o = new Option(ui.item.value,ui.item.value,true,true);
					if (!($.inArray(o.value, service_tab) >= 0)) {
					$("#service_id:last").append(o);
						service_tab.push(o.value);
					}
					return false;
				}
			});
		} else {
			$('#service').autocomplete({source: [""]});
		}
	});
	$("#service").on('click',function () {
		if ($("#host").val() != ""){
			$("#service").autocomplete("search","");
		}
	});

	// autocomplete for the remediation_action's name in remediation 
	$("#rule_host1").on('focusin',function () {
		if ( $("input[name=id]").val() != "") {
			remediation_id = $("input[name=id]").val();
		} else {
			remediation_id = -1;	
		}
		if($('input[name=name]').val() != ""){
			$('#rule_host1').autocomplete({
				source: './php/auto_completion.php?source_type=remediation_actions&id=' + remediation_id,
				minLength: 0,
				select: function(event, ui) {
					var o = new Option(ui.item.value,ui.item.value,true,true);
					$("#remediation_actions_id").append(o);
					return false;
				}
			});
		} else {
			$('#rule_host1').autocomplete({source: [""]});
		}
	});
	$("#rule_host1").on('click',function () {
		if($('input[name=name]').val() != ""){
			$("#rule_host1").autocomplete("search","");
		}
	});	
});

		
