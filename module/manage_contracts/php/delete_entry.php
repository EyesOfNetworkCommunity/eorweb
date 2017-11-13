<?php include('../../../include/config.php');
	$table_name = $_GET['table_name'];
	$id_number = $_GET['id_number'];
 
	if ($table_name == "contract_context_application"){
		$id_name = "ID_CONTRACT_CONTEXT";
		$information = explode("::", $id_number);
		$application_name = $information[1];
		$id = $information[0];

		$sql = "delete from " . $table_name . " where " . $id_name . " = " . $id . " and APPLICATION_NAME = '" . $application_name ."'";
	}

	else{
		$id_name = "id_" . $table_name;
		$id_name = strtoupper($id_name);

		$sql = "delete from " . $table_name . " where " . $id_name . " = " . $id_number;
	}
	try {
        $bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
    } catch(Exception $e) {
		 echo "Connection failed: " . $e->getMessage();
        //exit('Impossible de se connecter à la base de données.');
    }

	$bdd->exec($sql);
	echo "ok";
	exit;
 
  if($table_name == 'time_period'){
    $new_sql = "delete from timeperiod_entry where ID_TIME_PERIOD = " . $id_number;
    
    $bdd->exec($new_sql);
  }
?>
