<?php

include('./php/common.inc.php');
include("../../include/config.php");

try {
    $bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
}
catch(Exception $e) {
    echo "Connection failed: " . $e->getMessage();
    exit('Impossible de se connecter à la base de données.');
}

$sql = "select id_company,name as company_name from company";
$req = $bdd->query($sql);
$infos = $req->fetchall();
?>

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
			<?php foreach($infos as $info){
                $id = $info['id_company'];
                $company_name = $info['company_name'];

				print "<tr><td><span class=\"glyphicon glyphicon-share-alt text-warning\"></span></td><td>$company_name</td><td><button type=\"button\" class=\"btn btn-primary\" onclick=EditSelection($id)><span class=\"glyphicon glyphicon-pencil\"></span></button>  <button type=\"button\" class=\"btn btn-danger\" onclick=RemoveSelection($id)><span class=\"glyphicon glyphicon-trash\"></span></button></td></tr>";
			}
			?>
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
	$('#myTable').DataTable();
	$('#container_table').css("display", "inline");
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
