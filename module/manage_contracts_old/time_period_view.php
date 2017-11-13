<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
		<h3>Sélectionner une période de temps<h3>
</div>

<div class="container pad-top col-md-12" id="container_table" style="display:none">
        <table class="tablesorter" id="myTable">
	    <thead>
		    <tr>
				<th width=5%></th>
		        <th>Nom</th>
            	<th>Périodes de temps définis</th>
				<th width=10%>Edition</th>
		    </tr>
	    </thead>
		<tbody id="body_table">
		</tbody>
	</table>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../bower_components/jquery-ui/jquery-ui.min.js"></script>
<script src="../../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="./js/library.js"></script>

<script>
$(document).ready(function() {
  $global_array = {};
	$counter = 0;

	$.get(
		'./php/display_entry.php',
		{
			table_name: 'time_period'
		},
		function return_values(values){
			$.each(values, function(v, k){
				$id = k['ID_TIME_PERIOD'];
				$name =k['NAME'];
        
				$global_array[$counter] = [$id,$name];
				$counter++;
			});
      
			$count = 0;
			for(var i = 0; i < $counter; i++){
				$.get(
					'./php/get_values_timeperiod_entry.php',
					{
						table_name: 'timeperiod_entry',
						id_number: $global_array[i+''][0]
					},
					function return_time_period(time_period){
						$id = $global_array[$count+''][0];
						$name = $global_array[$count+''][1];

						$concatenation_time_period = "";
						$index = 0;
						$.each(time_period, function(v, k){
							$day = k['ENTRY'];
							$h_open = k['H_OPEN'];
							$h_close = k['H_CLOSE'];

							if($index == 4){
								$concatenation_time_period = $concatenation_time_period + '    <span class="glyphicon glyphicon-option-horizontal" style="vertical-align:bottom"></span>'
								return false;
							}

							$concatenation_time_period = $concatenation_time_period + '  <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-tag"></span> ' +$day+ ', ' +$h_open+ ' ' +$h_close+'</button>';

							$index++;
            			});
						$('#body_table').append('<tr><td><span class="glyphicon glyphicon-share-alt text-warning"></span></td><td>' + $name + '</td><td>'+$concatenation_time_period+'</td><td><button type="button" class="btn btn-primary" id="'+$id+'" onclick=EditSelection(id)><span class="glyphicon glyphicon-pencil"></span></button>  <button type="button" class="btn btn-danger" id="'+$id+'" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
        
						$count++;
					},
					'json'
				);
			}
			$timer_update_table = ($counter /30) *1000
                        if ($timer_update_table < 200){
                                $timer_update_table = 200;
                        }
                        setTimeout(function(){
				$('#container_table').css("display", "inline");
                                $('#myTable').DataTable();
                                },
                                $timer_update_table
                        );
		},
		'json'
	);
});

function EditSelection(id){
	$(location).attr('href',"time_period.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove("Supprimer la période de temps sÃ©lectionnée ?", "time_period", id);
}

</script>

</body>
</html>
