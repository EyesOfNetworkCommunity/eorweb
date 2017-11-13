<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<h3>Selectionner un contrat<h3>
</div>

<div class="container pad-top col-md-12" id="container_table" style="display:none">
        <table class="tablesorter" id="myTable">
	    <thead>
		    <tr>
				<th width=5%></th>
		        <th>Nom</th>
		        <th>Description</th>
		        <th>Entreprise</th>
				<th>Date d'expiration</th>
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
			table_name: 'contract'
		},
		function return_values(values){
			$.each(values, function(v, k){
				$id = k['ID_CONTRACT'];
				$contract_name = k['NAME'];
				$id_company = k['ID_COMPANY'];
				$alias = k['ALIAS'];
				$date = k['VALIDITY_DATE'];

				$global_array[$counter] = [$id,$contract_name,$id_company,$alias,$date];
				$counter++;
			});

			$count = 0;
			for(var i = 0; i < $counter; i++){
				$.get(
					'./php/select_name_by_id.php',
					{
						table_name: 'company',
						id_number: $global_array[i+''][2]
					},
					function return_name(name){
						$id = $global_array[$count+''][0];
						$contract_name = $global_array[$count+''][1];
						$alias = $global_array[$count+''][3];
						$date = $global_array[$count+''][4];
						$company = name['NAME'];
						$('#body_table').append('<tr id="tr_'+$id+'"><td><span class="glyphicon glyphicon-share-alt text-warning"></span></td><td>' + $contract_name + '</td><td>'+ $alias + '</td><td>'+ $company + '</td><td>'+ $date + '</td><td><button type="button" class="btn btn-primary" id="'+$id+'" onclick=EditSelection(id)><span class="glyphicon glyphicon-pencil"></span></button>  <button type="button" class="btn btn-danger" id="'+$id+'" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
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
	$(location).attr('href',"contract.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove("Supprimer le contrat sélectionner ?", "contract", id);
}

</script>

</body>
</html>
