<?php include('../../../include/config.php');
	$table_name = $_GET['table_name'];
	$id_number = $_GET['id_number'];
	$id_name = "id_" . $table_name;
	if ($table_name == "contract_context_application"){
		$id_name = "id_contract_context";
	}
	$id_name = strtoupper($id_name);

	try {
		$bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}

	$sql = "SELECT * FROM " . $table_name .  " WHERE " . $id_name . " = " . $id_number;

	$req = $bdd->query($sql);
	$values = $req->fetch();

    echo json_encode($values);
?>
