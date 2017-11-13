<?php include('../../../include/config.php');
	$id_unit = $_GET['id_unit'];

	try {
		$bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}

	$sql = "select ID_UNIT_COMPUT from kpi where ID_KPI=" . $id_unit;
  
	$req = $bdd->query($sql);
	$value = $req->fetch();
	$id = $value['ID_UNIT_COMPUT'];

	$sql = "select SHORT_NAME from unit where ID_UNIT = " . $id;
	$req = $bdd->query($sql);
	$value = $req->fetch();

	echo json_encode($value);
	exit;
?>
