<?php include('../../../include/config.php');
	$id = $_GET['id'];

	try {
		$bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}

	$sql = "SELECT kpi.NAME,kpi.ID_KPI FROM kpi INNER JOIN step_group WHERE kpi.ID_KPI = step_group.ID_KPI AND ID_STEP_GROUP = " . $id;

	$req = $bdd->query($sql);
	$values = $req->fetchall();

    echo json_encode($values);
?>
