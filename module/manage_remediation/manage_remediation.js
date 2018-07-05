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
	var group_tab = [];

	$('#remediation_group_id option').each(function() {
		group_tab.push($(this).val())
	});

	// Delete
	$('#rule_host_button_del').on('click',function(){
		$("#remediation_group_id").find('option:selected').remove();
		$("#remediation_group_id").find("option").prop('selected',true);
		group_tab = [];
		$("#remediation_group_id option").each(function() {
			group_tab.push(this.value);
		});
	});
	$('#service_button_del').on('click',function(){
		$("#service_id").find('option:selected').remove();
		$("#service_id").find("option").prop('selected',true);
		service_tab = [];
		$("#service_id option").each(function() {
		  service_tab.push(this.value);
		});
	});
	
	source_name = $('#source').val();
	$('#source').on('change', function() {
	  source_name = this.value;
	})
	
	$('#action_name').on('click', function() {
		temp = this.value;
		$.ajax({
			url: "./php/get_values.php",
			type : 'POST',
			dataType:'json',
			data: {
				"remediation_action_name" : temp
			},
			success: function(data){
				$('#service').val(data[0][0]);
				$('select[name=type]').val(data[0][1]);
				$('select[name=action]').val(data[0][2]);
				var temp_date = data[0][3].split(" ");
				var day = temp_date[0].split("-");
				var hour = temp_date[1].split(":");
				var date_start = new Date(day[0],day[1],day[2],hour[0],hour[1]);
				$('#validity_date').data('daterangepicker').setStartDate(date_start);
				$('#datepickerStart').val(date_start);
				var temp_date = data[0][4].split(" ");
				var day = temp_date[0].split("-");
				var hour = temp_date[1].split(":");
				var date_end = new Date(day[0],day[1],day[2],hour[0],hour[1]);
				$('#validity_date').data('daterangepicker').setEndDate(date_end);
				$('#datepickerEnd').val(date_end);
			}
		})
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
			$("#service_id").find('option').remove();
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
					if (!($.inArray(o.value, group_tab) >= 0)) {
					$("#remediation_group_id").append(o);
					group_tab.push(o.value);
					}
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

		
