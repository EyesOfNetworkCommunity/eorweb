<?php include('../../../include/config.php');
	try {
		$bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}
 
	$sql = "SELECT DISTINCT name FROM bp  WHERE name NOT IN (SELECT bp_name FROM bp_category) ORDER BY name";
  
	$req = $bdd->query($sql);
	$values = $req->fetchall();

    echo json_encode($values);
?>
