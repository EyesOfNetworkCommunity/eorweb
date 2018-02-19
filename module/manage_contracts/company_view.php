<?php 
include("../../header.php");
include("../../side.php");
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-md-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.company_view_title"); ?></h1>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th><?php echo getLabel("label.name"); ?></th>
					<th><?php echo getLabel("label.edit"); ?></th>
				</tr>
			</thead>
			<tbody id="body_table">
			</tbody>
		</table>
	</div>

	<div class="row">
		<div class="col-md-12">
			<input type="button" class="btn btn-primary" value="<?php echo getLabel("label.manage_contracts.company_add"); ?>" onclick="location.href='./company.php';">
		</div>
	</div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="./js/library.js"></script>

<script>
$(document).ready(function() {
	$counter = 0;
	$.get(
		'./php/display_entry.php',
		{
			table_name: 'company'
		},
		function return_values(values){
			$.each(values, function(v, k){
				$id = k['ID_COMPANY'];
				$name_company =k['NAME'];
				$('#body_table').append('<tr><td><span class="glyphicon glyphicon-share-alt text-warning"></span></td><td>' + $name_company + '</td><td><button type="button" class="btn btn-primary" id="'+$id+'" onclick=EditSelection(id)><span class="glyphicon glyphicon-pencil"></span></button>  <button type="button" class="btn btn-danger" id="'+$id+'" onclick=RemoveSelection(id)><span class="glyphicon glyphicon-trash"></span></button></td></tr>');
				$counter++; 
			});
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
	
	setTimeout(function(){
		$('#container_table').css("display", "inline");
          	$('#myTable').DataTable();
                },
                2000
        );
});

function EditSelection(id){
	$(location).attr('href',"company.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove("Supprimer l'entreprise sélectionner ?", "company", id);
}

</script>

<?php
include("footer.php");
?>