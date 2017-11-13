<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<h3>Liste Applications/Contrats</h3>
</div>

<div class="container pad-top col-md-12" id="container_table" style="display:none">
	<table class="tablesorter" id="myTable">
	    <thead>
		    <tr>
				<th width=5% class="radius_th"></th>
            	<th value="Applications">Applications</th>
              <th>Contrats</th>
            	<th>Période de temps</th>
              <th>Indicateurs</th>
              <th>Seuils</th>
			        <th class="radius_th">Suppression</th>
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
			table_name: 'contract_context_application'
		},
		function return_values(values){
			$.each(values, function(v, k){
				$id = k['ID_CONTRACT_CONTEXT'];
				$name_application =k['APPLICATION_NAME'];
        
        			$global_array[$counter] = [$id,$name_application];
				$counter++;
      			});
      
      			$count = 0;
			for(var i = 0; i < $counter; i++){
				$.get(
					'./php/get_values_contract_context_application_view.php',
					{
						table_name: 'contract_context',
						id_number: $global_array[i+''][0]
					},
					function return_names(names){
                				$contract_name = names['0'];
                    				$time_period_name = names['1'];
                        			$kpi_name = names['2'];
                        			$step_group_name = names['3'];

                        			$id = $global_array[$count+''][0];
            					$name_application = $global_array[$count+''][1];
        
				                $('#body_table').append('<tr><td><span class="glyphicon glyphicon-share-alt text-warning"></span></td><td>' + $name_application + '</td><td>' + $contract_name + '</td><td>' + $time_period_name + '</td><td>' + $kpi_name + '</td><td>' + $step_group_name + '</td><td><button type="button" class="btn btn-danger" id="'+$id+'::'+$name_application+'" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button></td></tr>');

                        			$count++;
			    		},
          				'json'
        			);
      			}
			$timer_update_table = ($counter /30) *1000
                        if ($timer_update_table < 100){
                                $timer_update_table = 100;
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

function RemoveSelection(application_name){
	DisplayPopupRemove("Supprimer l'application sélectionnée ?", "contract_context_application", application_name);
}

</script>

</body>
</html>
