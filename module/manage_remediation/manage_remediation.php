<?php
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
?>

<!-- DataTables JavaScript -->
<script src="/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script src="/bower_components/datatables-responsive/js/dataTables.responsive.js"></script>
<script src="/js/datatable.js"></script>

<!-- DateRangePicker JavaScript -->
<script src="/bower_components/moment/min/moment.min.js"></script>
<script src="/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

<script>
	// on page load
    $(document).ready(function() {
    
    	var start = moment().format('YYYY-MM-DD HH:mm');
		var end = moment().add(2, 'hours').format('YYYY-MM-DD HH:mm');

		if($("#datepickerStart").val() != ""){
			start = $("#datepickerStart").val();
		}
		
		if($("#datepickerEnd").val() != ""){
			end = $("#datepickerEnd").val();
		}

		var locale = 
		{
			format: 'YYYY-MM-DD HH:mm',
			applyLabel: dictionnary['action.apply'],
			cancelLabel: dictionnary['action.clear'],
			customRangeLabel: dictionnary['label.custom'],
			applyClass: "btn-primary",
			daysOfWeek: [
				dictionnary["calendar.sunday"],
				dictionnary["calendar.monday"],
				dictionnary["calendar.tuesday"],
				dictionnary["calendar.wednesday"],
				dictionnary["calendar.thursday"],
				dictionnary["calendar.friday"],
				dictionnary["calendar.saturday"]
			],
			monthNames: [
				dictionnary["calendar.january"],
				dictionnary["calendar.february"],
				dictionnary["calendar.march"],
				dictionnary["calendar.april"],
				dictionnary["calendar.may"],
				dictionnary["calendar.june"],
				dictionnary["calendar.july"],
				dictionnary["calendar.august"],
				dictionnary["calendar.september"],
				dictionnary["calendar.october"],
				dictionnary["calendar.november"],
				dictionnary["calendar.december"]
			]
		};

		$('#validity_date').daterangepicker(
		{
			dateLimit: {
				days: 30
			},
		    locale: locale,
		    timePicker: true,
			timePicker24Hour: true
		}, function(start,end){
			$("#datepickerStart").val(start.format("YYYY-MM-DD HH:mm"));
			$("#datepickerEnd").val(end.format("YYYY-MM-DD HH:mm"));
		});

		if ($("#datepickerStart").val() != null) {
			$('#validity_date').data('daterangepicker').setStartDate(start);
			$("#datepickerStart").val(start);
		}
		if ($("#datepickerEnd").val() != null) {
			$('#validity_date').data('daterangepicker').setEndDate(end);
			$("#datepickerEnd").val(end);
		}
	});
</script>
