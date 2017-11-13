<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<h3>Selectionner une entreprise<h3>
</div>

<div class="container pad-top col-md-12" id="container_table" style="display:none">
        <table class="tablesorter" id="myTable">
	    <thead>
		    <tr>
				<th width="5%"></th>
		        <th width="70%">Nom</th>
				<th width="10%">Edition</th>
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
	/*$.get(
    './php/delete_entry.php',
    {
      table_name: "company",
      id_number: id
    },
    function ReturnStatus(value){
      console.log(value);
        $(location).attr('href',"company_view.php");
    }
  );*/
	DisplayPopupRemove("Supprimer l'entreprise sélectionner ?", "company", id);
}

</script>

</body>
</html>
