<?php include('../../../include/config.php');
	$table_name = $_GET['table_name'];
  $name = $_GET['name'];
	$id_name = "id_" . $table_name;
	$id_name = strtoupper($id_name);

    try {
        $bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
    } catch(Exception $e) {
		 echo "Connection failed: " . $e->getMessage();
        exit('Impossible de se connecter à la base de données.');
    }

    $sql = "SELECT " . $id_name . " FROM " . $table_name .  " WHERE NAME = '". $name ."'";

	$req = $bdd->query($sql);
	$id = $req->fetch();

  echo json_encode($id);
?>
