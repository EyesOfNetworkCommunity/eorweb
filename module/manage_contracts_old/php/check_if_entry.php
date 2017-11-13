<?php include('../../../include/config.php');
	try {
		$bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}
 
	$sql = "SELECT COUNT(*) FROM last_entry";
  
	$req = $bdd->query($sql);
	$number = $req->fetch();
	$number[0] = $number['COUNT(*)'];

    echo json_encode($number);
?>
