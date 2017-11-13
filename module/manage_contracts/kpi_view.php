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

$sql = "select kpi.id_kpi,kpi.name as kpi_name,unit.name as unit_name from kpi inner join unit on kpi.id_unit_comput = unit.id_unit";

$req = $bdd->query($sql);
$infos = $req->fetchall();
?>

<div class="container container-title bg-primary">
		<h3>Selectionner un indicateur de performances<h3>
</div>

<div class="container pad-top col-md-12" id="container_table" style="display:none">
        <table class="tablesorter" id="myTable">
	    <thead>
		    <tr>
				<th width=5%></th>
		        <th>Nom</th>
	            <th>Unité</th>
			    <th width=10%>Edition</th>
		    </tr>
	    </thead>
		<tbody id="body_table">
			<?php foreach($infos as $info){
				$id = $info['id_kpi'];
				$kpi_name = $info['kpi_name'];
				$unit_name = $info['unit_name'];
				print "<tr><td><span class=\"glyphicon glyphicon-share-alt text-warning\"></span></td><td>$kpi_name</td><td>$unit_name</td><td><button type=\"button\" class=\"btn btn-primary\" onclick=EditSelection($id)><span class=\"glyphicon glyphicon-pencil\"></span></button>  <button type=\"button\" class=\"btn btn-danger\" onclick=RemoveSelection($id)><span class=\"glyphicon glyphicon-trash\"></span></button></td></tr>";
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
	$(location).attr('href',"kpi.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove("Supprimer l'indicateur sélectionné ?", "kpi", id);
}

</script>

</body>
</html>
