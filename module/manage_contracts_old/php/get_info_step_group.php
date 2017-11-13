<?php include('../../../include/config.php');
	$id_kpi = $_GET['id_kpi'];

	try {
		$bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}

	$sql = "SELECT NAME,ID_STEP_GROUP FROM step_group where ID_KPI = " . $id_kpi;
  
	$req = $bdd->query($sql);
	$values = $req->fetchall();

    echo json_encode($values);
?>
