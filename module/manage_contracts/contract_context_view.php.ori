<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<div>
		<h3>Selectionner un contexte de contrat</h3>
	</div>
</div>

<div class="container pad-top col-md-12" id="container_table" style="display:none">
	<table class="tablesorter" id="myTable">
	    <thead>
		    <tr>
			<th width=5%></th>
		        <th>Nom</th>
		        <th>Description</th>
		        <th>Contrat associé</th>
		        <th>Période de temps</th>
		        <th>Indicateur</th>
			<th>Seuils associés</th>
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
			table_name: 'contract_context'
		},
		function return_values(values){
			$.each(values, function(v, k){
				$id = k['ID_CONTRACT_CONTEXT'];

				$global_array[$counter] = [$id];
				$counter++;
			});

			$count = 0;
			for(var i = 0; i < $counter; i++){
				$.get(
					'./php/get_values_contract_context_view.php',
					{
						id_number: $global_array[i+''][0]
					},
					function return_name(values){
						$name_context = values['0'];
						$alias = values['1'];
						$name_contract = values['2'];
						$name_time_period = values['3'];
						$name_kpi = values['4'];
						$name_step_group = values['5'];
						$id = $global_array[$count+''][0];

						$('#body_table').append('<tr id="'+$id+'"><td><span class="glyphicon glyphicon-share-alt text-warning"></span></td><td>' + $name_context + '</td><td>'+ $alias + '</td><td>' + $name_contract + '</td><td>' + $name_time_period + '</td><td>' + $name_kpi + '</td><td>' + $name_step_group + '</td><td><button type="button" class="btn btn-primary" id="'+$id+'" onclick=EditSelection(id)><span class="glyphicon glyphicon-pencil"></span></button>  <button type="button" class="btn btn-danger" id="'+$id+'" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
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
	$(location).attr('href',"contract_context.php?id_number=" + id + "");
}

function RemoveSelection(id){
	console.log(id);
	DisplayPopupRemove("Supprimer le contexte de contrat sélectionner ?", "contract_context", id);
}

</script>

</body>
</html>
