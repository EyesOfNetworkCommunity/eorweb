<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<h3>Selectionner une entreprise<h3>
</div>

<div class="container pad-top col-md-12">
        <table class="tablesorter" id="myTable">
	    <thead>
		    <tr>
				<th width=5%></th>
		        <th>Nom</th>
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

</body>
</html>
