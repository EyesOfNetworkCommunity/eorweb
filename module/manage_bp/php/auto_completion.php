<?php

if(isset($_GET['term']) && isset($_GET['source_name'])) {

	include("../../../include/config.php");

	global $database_vanillabp;
	global $database_username;
	global $database_password;

	// Mot tapé par l'utilisateur
	$term = $_GET['term'];
	$source_name = $_GET['source_name'];
	$suggestions=array();

	try {
		$bdd = new PDO('mysql:host=localhost;dbname='.$database_vanillabp, $database_username, $database_password);
		$bdd_thruk = new PDO('mysql:host=localhost;dbname=thruk',$database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}

	// Find thruk idx
	$sql = "SELECT thruk_idx FROM bp_sources WHERE db_names=?";
	$req = $bdd->prepare($sql);
	$req->execute(array($source_name));
	$thruk_idx = $req->fetch()[0];

	// Find hosts
	$sql = "SELECT host_name FROM ".$thruk_idx."_host WHERE host_name like ? ORDER BY host_name";
	$req = $bdd_thruk->prepare($sql);
	$req->execute(array("%".$term."%"));

	while ($row = $req->fetch()) {
		$suggestions[] = $row['host_name'];
	}
	echo json_encode($suggestions);

}

?>
