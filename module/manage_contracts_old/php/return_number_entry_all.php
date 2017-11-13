<?php include('../../../include/config.php');

    try {
        $bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
    } catch(Exception $e) {
		 echo "Connection failed: " . $e->getMessage();
        exit('Impossible de se connecter à la base de données.');
    }

	$tables_name = array ('contract_context', 'contract', 'company', 'time_period', 'kpi', 'step_group', 'contract_context_application');
	$my_values = array();
	for ($i = 0; $i < count($tables_name); $i++){
		$sql = "SELECT COUNT(*) FROM " . $tables_name[$i];

		$req = $bdd->query($sql);
		$number = $req->fetch();
		$my_value[$i] = $number['COUNT(*)'];
	}
	echo json_encode($my_value);
?>
