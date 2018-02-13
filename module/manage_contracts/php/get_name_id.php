<?php include('../../../include/config.php');
	$table_name = $_GET['table_name'];
	$id = $_GET['id'];

	try {
		$bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}

	if($table_name == 'contract_context_application'){
		$sql = "SELECT APPLICATION_NAME FROM " . $table_name;
	}

	else{
		$sql = "SELECT NAME,".$id. " FROM " . $table_name;
	}
  
	$req = $bdd->query($sql);
	$values = $req->fetchall();

    echo json_encode($values);
?>
