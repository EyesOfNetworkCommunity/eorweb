<?php include('../../../include/config.php');
	$table_name = $_GET['table_name'];
  $id_context = $_GET['id_number'];

    try {
        $bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
    } catch(Exception $e) {
		 echo "Connection failed: " . $e->getMessage();
        exit('Impossible de se connecter à la base de données.');
    }

    $sql = "SELECT contract.name AS contract,time_period.name AS time_period,kpi.name AS kpi,step_group.name AS step_group, contract_context.name AS contract_context, contract_context.id_contract_context AS context_id FROM contract_context INNER JOIN contract ON contract_context.id_contract = contract.id_contract INNER JOIN time_period ON contract_context.id_time_period = time_period.id_time_period INNER JOIN kpi ON contract_context.id_kpi = kpi.id_kpi INNER JOIN step_group ON contract_context.id_step_group = step_group.id_step_group WHERE contract_context.id_contract_context = ". $id_context ." ORDER BY contract_context";

	$req = $bdd->query($sql);
	$names = $req->fetch();

  echo json_encode($names);
?>
