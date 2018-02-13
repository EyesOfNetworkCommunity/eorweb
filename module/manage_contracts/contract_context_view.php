<?php 
include("../../header.php");
include("../../side.php");
?>

<div id="page-wrapper">
	<div class="row">
		<h1 class="page-header"><?php echo getLabel("label.manage_contracts.contract_context_view_title"); ?></h1>
	</div>
	<div id="container_table">
		<table class="table table-striped table-hover" id="myTable">
		    <thead class="altTop">
			    <tr>
					<th></th>
			        <th><?php echo getLabel("label.name"); ?></th>
			        <th><?php echo getLabel("label.description"); ?></th>
			        <th><?php echo getLabel("label.manage_contracts.associate_contracts"); ?></th>
			        <th><?php echo getLabel("label.time_period"); ?></th>
			        <th><?php echo getLabel("label.indicator"); ?></th>
					<th><?php echo getLabel("label.manage_contracts.associate_sla"); ?></th>
					<th><?php echo getLabel("label.edit"); ?></th>
			    </tr>
		    </thead>
			<tbody id="body_table">
			</tbody>
		</table>
	</div>
	<div class="col-md-12">
		<input type="button" class="btn btn-primary" value="Add" onclick="location.href='./contract_context.php';">
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
	DisplayPopupRemove("Delete the selected contract context ?", "contract_context", id);
}

 $('#hover').hover(function() {
        $(this).css('cursor','pointer');
    });
</script>

<?php
include("footer.php");
?>
