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

$sql = "select contract_context.id_contract_context,contract_context.name as name_contract_context,contract_context.alias as alias_contract_context,contract.name as name_contract,time_period.name as name_timeperiod,kpi.name as name_kpi,step_group.name as name_stepgroup from contract_context inner join contract on contract_context.id_contract = contract.id_contract inner join time_period on contract_context.id_time_period = time_period.id_time_period inner join kpi on contract_context.id_kpi = kpi.id_kpi inner join step_group on contract_context.id_step_group = step_group.id_step_group";

$req = $bdd->query($sql);
$infos = $req->fetchall();
?>

<div class="container container-title bg-primary">
	<div>
		<h3>Selectionner un contexte de contrat</h3>
	</div>
</div>

<div class="container pad-top col-md-12" id="container_table" style="display:none">
	<table class="tablesorter" id="myTable">
	    <thead>
		    <tr>
			<th width=5%></th>
		        <th>Nom</th>
		        <th>Description</th>
		        <th>Contrat associé</th>
		        <th>Période de temps</th>
		        <th>Indicateur</th>
			<th>Seuils associés</th>
			<th width=10%>Edition</th>
		    </tr>
	    </thead>
		<tbody id="body_table">
			<?php foreach($infos as $info){
				$name_context = $info['name_contract_context'];
                $alias = $info['alias_contract_context'];
                $name_contract = $info['name_contract'];
                $name_timeperiod = $info['name_timeperiod'];
                $name_kpi = $info['name_kpi'];
                $name_stepgroup = $info['name_stepgroup'];
                $id = $info['id_contract_context'];

				print "<tr><td><span class=\"glyphicon glyphicon-share-alt text-warning\"></span></td><td>$name_context</td><td>$alias</td><td>$name_contract</td><td>$name_timeperiod</td><td>$name_kpi</td><td>$name_stepgroup</td><td><button type=\"button\" class=\"btn btn-primary\" onclick=EditSelection($id)><span class=\"glyphicon glyphicon-pencil\"></span></button><button type=\"button\" class=\"btn btn-danger\" onclick=RemoveSelection($id)><span class=\"glyphicon glyphicon-trash\"></span></button></td></tr>";
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
	$(location).attr('href',"contract_context.php?id_number=" + id + "");
}

function RemoveSelection(id){
	console.log(id);
	DisplayPopupRemove("Supprimer le contexte de contrat sélectionner ?", "contract_context", id);
}

</script>

</body>
</html>
