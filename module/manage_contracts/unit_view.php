<?php 
include("../../header.php");
include("../../side.php");
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-md-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.unit_view_title"); ?></h1>
		</div>
	</div>

		<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
			        <th><?php echo getLabel("label.name"); ?></th>
				    <th><?php echo getLabel("label.actions"); ?></th>
			    </tr>
			</thead>
			<tbody id="body_table">
			</tbody>
		</table>
	</div>

	<div class="row">
		<div class="col-md-12">
			<input type="button" class="btn btn-primary" value="<?php echo getLabel("label.manage_contracts.unit_add"); ?>" onclick="location.href='./unit.php';">
		</div>
	</div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="./js/library.js"></script>

<script>
$(document).ready(function() {
	$.get(
		'./php/display_entry.php',
		{
			table_name: 'unit'
		},
		function return_values(values){
			$.each(values, function(v, k){
				$id = k['ID_UNIT'];
				$name_unit =k['NAME'];

				$('#body_table').append('<tr><td><span class="glyphicon glyphicon-share-alt text-warning"></span></td><td>' + $name_unit + '</td><td><button type="button" class="btn btn-primary" id="'+$id+'" onclick=EditSelection(id)><span class="glyphicon glyphicon-pencil"></span></button>  <button type="button" class="btn btn-danger" id="'+$id+'" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
			});
		},
		'json'
	);
	setTimeout(function(){
          $('#myTable').DataTable();
                },
                1000
        );
});

function EditSelection(id){
	$(location).attr('href',"unit.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove("Supprimer l'unité sÃ©lectionner ?", "unit", id);
}

</script>

<?php
include("../../footer.php");
?>