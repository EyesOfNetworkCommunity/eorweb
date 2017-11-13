<?php include('../../../include/config.php');
  // Mot tapé par l'utilisateur
  $q = $_GET['query'];
	$table_name = $_GET['table_name'];
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

    // Requête SQL
    $requete = "SELECT * FROM " . $table_name .  " WHERE NAME LIKE '". $q ."%' LIMIT 0, 10";

	foreach  ($bdd->query($requete) as $row) {
		$suggestions['suggestions'][] = $row['NAME'];
	}
    echo json_encode($suggestions);
?>
