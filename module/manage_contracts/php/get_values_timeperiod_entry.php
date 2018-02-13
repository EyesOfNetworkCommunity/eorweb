<?php include('../../../include/config.php');
	$table_name = $_GET['table_name'];
	$id = $_GET['id_number'];

	try {
		$bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}

	$sql = "SELECT * FROM " . $table_name . " where ID_TIME_PERIOD = " . $id;

	$req = $bdd->query($sql);
	$values = $req->fetchall();

    echo json_encode($values);
?>
