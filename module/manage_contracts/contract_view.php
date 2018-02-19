<?php 
include("../../header.php");
include("../../side.php");
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-md-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.contract_view_title"); ?></h1>
		</div>
	</div>
	
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th><?php echo getLabel("label.name"); ?></th>
					<th><?php echo getLabel("label.description"); ?></th>
					<th><?php echo getLabel("label.company"); ?></th>
					<th><?php echo getLabel("label.contracts_menu.contracts_menu_display_tab_date"); ?></th>
					<th><?php echo getLabel("label.actions"); ?></th>
				</tr>
			</thead>
			<tbody id="body_table">
			</tbody>
		</table>
	</div>

	<div class="row">
		<div class="col-md-12">
			<input type="button" class="btn btn-primary" value="<?php echo getLabel("label.manage_contracts.contract_add"); ?>" onclick="location.href='./contract.php';">
		</div>
	</div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
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


<?php
include("../../footer.php");
?>