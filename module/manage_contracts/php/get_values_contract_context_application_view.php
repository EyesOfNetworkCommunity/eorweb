<?php include('../../../include/config.php');
	$table_name = $_GET['table_name'];
  $id_context = $_GET['id_number'];

    try {
        $bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
    } catch(Exception $e) {
		 echo "Connection failed: " . $e->getMessage();
        exit('Impossible de se connecter à la base de données.');
    }

    $sql = "select contract.name,time_period.name,kpi.name,step_group.name from contract_context inner join contract on contract_context.id_contract = contract.id_contract inner join time_period on contract_context.id_time_period = time_period.id_time_period inner join kpi on contract_context.id_kpi = kpi.id_kpi inner join step_group on contract_context.id_step_group = step_group.id_step_group where contract_context.id_contract_context = ". $id_context;

	$req = $bdd->query($sql);
	$names = $req->fetch();

  echo json_encode($names);
?>
