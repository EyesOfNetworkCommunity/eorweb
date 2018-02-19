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

<!-- DateRangePicker JavaScript -->
<script src="/bower_components/moment/min/moment-with-locales.min.js"></script>
<script src="../../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- DateRangePicker Call -->
<script>
	moment.locale('fr');

	$(document).ready(function() {
		$('#validity_date').daterangepicker(
		{
			dateLimit: {
				days: 30
			},
		    locale: {
		    	firstDay: 1,
		    	format: 'DD/MM/YYYY',
				applyLabel: dictionnary['action.apply'],
				cancelLabel: dictionnary['action.clear'],
				customRangeLabel: dictionnary['label.custom'],
				applyClass: "btn-primary",
		    }
		}, function(start, end) {
			if ( (start.format('MM') == end.format('MM')) && (start.format('YYYY') == end.format('YYYY')) ){
				document.getElementById('message').innerHTML = "";
				document.getElementById('launch').removeAttribute("disabled");
				$('#startDate').val(start.format('YYYYMMDD'));
				$('#endDate').val(end.format('YYYYMMDD'));
			} else {
				document.getElementById('message').innerHTML = "<?php message(0, getLabel("message.kettle_apps.date_error"), "critical"); ?>";
				document.getElementById('launch').setAttribute("disabled", true);
			}
		});
		$('#launch').click(function(event){
			event.preventDefault();
			$.get(
				'./php/get_kettle_id.php',
				{
					begin_date: $('#startDate').val(),
					end_date: $('#endDate').val()
				},
				function ReturnID(id){
					$(location).attr('href', 'http://<?php echo $_SERVER['SERVER_NAME']."/module/module_frame/index.php?url=".urlencode("/kettle/jobStatus/?name=JOB_MAIN_DATE&id=")?>' + id);	
				},
				'text'
			);
		});
	});
	
</script>
