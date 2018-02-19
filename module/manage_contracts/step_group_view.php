<?php 
include("../../header.php");
include("../../side.php");
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-md-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.step_group__view_title"); ?></h1>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th><?php echo getLabel("label.admin_group.group_name"); ?></th>
					<th><?php echo getLabel("label.contracts_menu.indicator_create_name"); ?></th>
					<th><?php echo getLabel("label.contracts_menu.seuils_display_tab_threshold"); ?></th>
					<th><?php echo getLabel("label.actions"); ?></th>
				</tr>
			</thead>
			<tbody id="body_table">
			</tbody>
		</table>
	</div>

	<div class="row">
		<div class="col-md-12">
			<input type="button" class="btn btn-primary" value="<?php echo getLabel("label.manage_contracts.step_group_add"); ?>" onclick="location.href='./step_group.php';">
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
			table_name: 'step_group'
		},
		function return_values(values){
			$.each(values, function(v, k){
				$id = k['ID_STEP_GROUP'];
				$id_kpi = k['ID_KPI'];
				$name = k['NAME'];
				$step_number = k['STEP_NUMBER'];
				$type = k['TYPE'];
				$step_array = "";

				for (var i = 0; i < $step_number; i++){
					$number = i +1;
					$step_min = k['STEP_' +$number+ '_MIN'];
					$step_max = k['STEP_' +$number+ '_MAX'];
					if(i == 4){
						$step_array = $step_array + '    <span class="glyphicon glyphicon-option-horizontal" style="vertical-align:bottom"></span>';
						break;
					}

					$step_array = $step_array + '  <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-tag"></span> [' +$step_min+ $type +' ; ' +$step_max + $type +'[</button>';
				}
				
				$global_array[$counter] = [$id,$name,$id_kpi,$step_array];
				$counter++;
			});

			$count = 0;
			for(var i = 0; i < $counter; i++){
				$.get(
					'./php/select_name_by_id.php',
					{
						table_name: 'kpi',
						id_number: $global_array[i+''][2]
					},
					function return_name(name){
						$id = $global_array[$count+''][0];
						$name = $global_array[$count+''][1];
						$kpi = name['NAME'];
						$step_values = $global_array[$count+''][3];

						$('#body_table').append('<tr><td><span class="glyphicon glyphicon-share-alt text-warning"></span></td><td>' + $name + '</td><td>' + $kpi + '</td><td>' + $step_values + '</td><td><button type="button" class="btn btn-primary" id="'+$id+'" onclick=EditSelection(id)><span class="glyphicon glyphicon-pencil"></span></button>  <button type="button" class="btn btn-danger" id="'+$id+'" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
        
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
	$(location).attr('href',"step_group.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove("Supprimer le groupe de seuils sélectionné ?", "step_group", id);
}

</script>

<?php
include("../../footer.php");
?>