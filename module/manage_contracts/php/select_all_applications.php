<?php include('../../../include/config.php');
	try {
		$bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}
 
	$sql = "select distinct SUBSTRING(bp_name,1,CHAR_LENGTH(bp_name)-3) as name from bp_category order by name";
  
	$req = $bdd->query($sql);
	$values = $req->fetchall();

    echo json_encode($values);
?>
