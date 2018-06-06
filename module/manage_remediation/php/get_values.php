<?php include('../../../include/config.php');
	
	$remediation_action_name = $_POST["remediation_action_name"];

	try {
		$bdd = new PDO("mysql:host=$database_host;dbname=$database_eorweb", $database_username, $database_password);
	} catch(Exception $e) {
		echo "Connection failed: " . $e->getMessage();
		exit('Impossible de se connecter à la base de données.');
	}
	
	$sql = "SELECT service, type, action, DateDebut, DateFin FROM remediation_action INNER JOIN remediation_group WHERE id_group = remediation_group.id AND remediation_action.description = '".$remediation_action_name."'";
  
	$req = $bdd->query($sql);
	$values = $req->fetchall();

    echo json_encode($values);
?>
