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

$sql = "select contract_context.id_contract_context,contract.name as contract_name,time_period.name as timeperiod_name,kpi.name as kpi_name,step_group.name as stepgroup_name,contract_context_application.application_name from contract_context inner join contract on contract_context.id_contract = contract.id_contract inner join time_period on contract_context.id_time_period = time_period.id_time_period inner join kpi on contract_context.id_kpi = kpi.id_kpi inner join step_group on contract_context.id_step_group = step_group.id_step_group inner join contract_context_application on contract_context.id_contract_context = contract_context_application.id_contract_context";

$req = $bdd->query($sql);
$infos = $req->fetchall();
?>

<div class="container container-title bg-primary">
	<h3>Liste Applications/Contrats</h3>
</div>

<div class="container pad-top col-md-12" id="container_table" style="display:none">
	<table class="tablesorter" id="myTable">
	    <thead>
		    <tr>
				<th width=5% class="radius_th"></th>
            	<th value="Applications">Applications</th>
              <th>Contrats</th>
            	<th>Période de temps</th>
              <th>Indicateurs</th>
              <th>Seuils</th>
			        <th class="radius_th">Suppression</th>
		    </tr>
	    </thead>
		<tbody id="body_table">
			<?php foreach($infos as $info){
				$id_contract_context = $info['id_contract_context'];
				$application_name = $info['application_name'];
				$stepgroup_name = $info['stepgroup_name'];
				$kpi_name = $info['kpi_name'];
				$timeperiod_name = $info['timeperiod_name'];
				$contract_name = $info['contract_name'];
				
				print "<tr><td><span class=\"glyphicon glyphicon-share-alt text-warning\"></span></td><td>$application_name</td><td>$contract_name</td><td>$timeperiod_name</td><td>$kpi_name</td><td>$stepgroup_name</td><td><button type=\"button\" class=\"btn btn-danger\" onclick=RemoveSelection('$id_contract_context::$application_name')><span class=\"glyphicon glyphicon-trash\"></span></button></td></tr>";
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
	$('#container_table').css('display', 'inline');
});

function RemoveSelection(application_name){
	DisplayPopupRemove("Supprimer l'application sélectionnée ?", "contract_context_application", application_name);
}

</script>

</body>
</html>
