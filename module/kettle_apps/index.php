<?php 
include ("../../header.php"); 
?>

<link rel="stylesheet" type="text/css" href="./css/design.css" />
<link rel="stylesheet" type="text/css" href="../../bower_components/bootstrap-daterangepicker/daterangepicker.css" />

<div class="row">
	<h1 class="page-header marge"><?php echo getLabel("label.kettle_apps.title"); ?></h1>
</div>

<div class="text-primary container pad-top">
	<h4>Main workflow</h4>
</div>

<form class="form-horizontal col-md-6 col-md-offset-3" id="global">
	<div class="row form-group">
		<div class="form-group has-feedback div-validity-date">
			<label style="font-weight:normal;" class="col-md-4 control-label"><?php echo getLabel("label.kettle_apps.time_period_select"); ?></label>
			<div class="col-md-7 input-validity-date input-group">
				<input type="text" class="form-control" readonly id="validity_date">
				<span class="input-group-addon">
                	<span class="glyphicon glyphicon-calendar"></span>
            	</span>
			</div>
		</div>
		<div id="startDate"></div>
		<div id="endDate"></div>
	</div>
	<div class="row col-md-4 col-md-offset-6">
		<button class="form-group btn btn-primary" type="submit" id="launch">Launch WorkFlow
			<span class="glyphicon glyphicon-ok" style="color:#4f4;"></span>
		</button>
	</div>
</form>

<script src="/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DateRangePicker JavaScript -->
<script src="/bower_components/moment/min/moment.min.js"></script>
<script src="/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>


<script>
	$(document).ready(function() {
		$('#validity_date').daterangepicker(
		{
			dateLimit: {
				days: 29
			},
		    locale: {
		    	firstDay: 1,
		    	format: 'DD/MM/YYYY'
		    }
		}, function(start, end) {
			if ( (start.format('MM') == end.format('MM')) && (start.format('YYYY') == end.format('YYYY')) ){
				$('#startDate').val(start.format('YYYYMMDD'));
				$('#endDate').val(end.format('YYYYMMDD'));
			} else {
				document.getElementById('startDate').innerHTML = "<?php message(0, "selectionnez deux dates du meme mois", "critical"); ?>";
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
					$(location).attr('href', 'http://<?php echo $_SERVER['SERVER_NAME'] ?>/kettle/jobStatus/?name=JOB_MAIN_DATE&id=' + id);	
				},
				'text'
			);
		});
	});

	
</script>

</div>