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

$sql = "select step_group.*,kpi.name as kpi_name from step_group inner join kpi on step_group.id_kpi = kpi.id_kpi";

$req = $bdd->query($sql);
$infos = $req->fetchall();
?>

<div class="container container-title bg-primary">
		<h3>Selectionner un groupe de seuil<h3>
</div>

<div class="container pad-top col-md-12" id="container_table" style="display:none">
        <table class="tablesorter" id="myTable">
	    <thead>
		    <tr>
			<th width=5%></th>
		      	<th>Nom du groupe</th>
			<th>Nom de l'indicateur</th>
			<th>Seuils définis</th>
			<th width=10%>Edition</th>
		    </tr>
	    </thead>
		<tbody id="body_table">
			<?php foreach($infos as $info){
				$id = $info['ID_STEP_GROUP'];
				$name = $info['NAME'];
                $step_number = $info['STEP_NUMBER'];
                $type = $info['TYPE'];
				$kpi = $info['kpi_name'];
				$step_array = "";

				for ($i = 1; $i < $step_number +1; $i++){
                    $step_min = $info["STEP_" .$i. "_MIN"];
                    $step_max = $info["STEP_" .$i. "_MAX"];
                    if($i == 5){
                        $step_array = $step_array . "    <span class=\"glyphicon glyphicon-option-horizontal\" style=\"vertical-align:bottom\"></span>";
                        break;
					}

                    $step_array = $step_array . "<button type=\"button\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-tag\"></span> [" .$step_min. "" .$type. " ; " .$step_max. "" .$type. "[</button>";
                }

					

				print "<tr><td><span class=\"glyphicon glyphicon-share-alt text-warning\"></span></td><td>$name</td><td>$kpi</td><td>$step_array</td><td><button type=\"button\" class=\"btn btn-primary\" onclick=EditSelection($id)><span class=\"glyphicon glyphicon-pencil\"></span></button>  <button type=\"button\" class=\"btn btn-danger\" onclick=RemoveSelection($id)><span class=\"glyphicon glyphicon-trash\"></span></button></td></tr>";
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
	$(location).attr('href',"step_group.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove("Supprimer le groupe de seuils sélectionné ?", "step_group", id);
}

</script>

</body>
</html>
