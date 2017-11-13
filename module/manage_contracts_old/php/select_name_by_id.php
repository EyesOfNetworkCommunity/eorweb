<?php include('../../../include/config.php');
	$table_name = $_GET['table_name'];
  $id_number = $_GET['id_number'];
	$id_name = "id_" . $table_name;
	$id_name = strtoupper($id_name);

    try {
        $bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
    } catch(Exception $e) {
		 echo "Connection failed: " . $e->getMessage();
        exit('Impossible de se connecter à la base de données.');
    }

    $sql = "SELECT NAME FROM " . $table_name . " WHERE " . $id_name . " = " . $id_number;

	$req = $bdd->query($sql);
	$name = $req->fetch();

  echo json_encode($name);
?>
