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

$sql = "select id_time_period,name from time_period";

$req = $bdd->query($sql);
$infos = $req->fetchall();
?>

<div class="container container-title bg-primary">
		<h3>Sélectionner une période de temps<h3>
</div>

<div class="container pad-top col-md-12" id="container_table" style="display:none">
        <table class="tablesorter" id="myTable">
	    <thead>
		    <tr>
				<th width=5%></th>
		        <th>Nom</th>
            	<th>Périodes de temps définis</th>
				<th width=10%>Edition</th>
		    </tr>
	    </thead>
		<tbody id="body_table">
			<?php
				foreach($infos as $info){
					$id = $info['id_time_period'];
					$timeperiod_name = $info['name'];

					$sql = "select entry,h_open,h_close from timeperiod_entry where id_time_period = '" .$id."'";
					$req = $bdd->query($sql);
					$entries = $req->fetchall();
					$counter = 0;
					$concatenation_time_period = "";
					foreach($entries as $entry){
						$day = $entry['entry'];
						$h_open = $entry['h_open'];
						$h_close = $entry['h_close'];

						$counter += 1;
						if($counter == 4){
							$concatenation_time_period = $concatenation_time_period . "<span class=\"glyphicon glyphicon-option-horizontal\" style=\"vertical-align:bottom\"></span>";
							break;
						}
						else{
							$concatenation_time_period = $concatenation_time_period . "<button type=\"button\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-tag\"></span>" .$day.",".$h_open." ".$h_close."</button>";
						}
					}

					print "<tr><td><span class=\"glyphicon glyphicon-share-alt text-warning\"></span></td><td>$timeperiod_name</td><td>$concatenation_time_period</td><td><button type=\"button\" class=\"btn btn-primary\" onclick=EditSelection($id)><span class=\"glyphicon glyphicon-pencil\"></span></button>  <button type=\"button\" class=\"btn btn-danger\" onclick=RemoveSelection($id)><span class=\"glyphicon glyphicon-trash\"></span></button></td></tr>";
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
	$(location).attr('href',"time_period.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove("Supprimer la période de temps sÃ©lectionnée ?", "time_period", id);
}

</script>

</body>
</html>
