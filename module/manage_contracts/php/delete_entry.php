<?php include('../../../include/config.php');

	$table_name = $_GET['table_name'];
	$id_number = $_GET['id_number'];
 
  $id_name = "id_" . $table_name;

	if ($table_name == "contract_context_application"){
		$id_name = "application_name";
	}

	$id_name = strtoupper($id_name);

	$sql = "delete from " . $table_name . " where " . $id_name . " = '" . $id_number . "'";
	try {
        $bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
    } catch(Exception $e) {
		 echo "Connection failed: " . $e->getMessage();
        exit('Impossible de se connecter à la base de données.');
    }

	$bdd->exec($sql);
 
  if($table_name == 'time_period'){
    $new_sql = "delete from timeperiod_entry where ID_TIME_PERIOD = " . $id_number;
    
    $bdd->exec($new_sql);
  }
?>
