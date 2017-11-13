<?php include('../../../include/config.php');
	$table_name = $_GET['table_name'];
	$id_number = $_GET['id_number'];
	$id_name = "id_" . $table_name;
	if ($table_name == "contract_context_application"){
		$id_name = "id_contract_context";
	}
	$id_name = strtoupper($id_name);

	if ($table_name == 'contract_context'){
		$name = $_GET['name'];
		if (empty($name)) {
			echo "false";
			exit;
		}
		$alias = $_GET['alias'];
		$id_contract = $_GET['id_contract'];
		if (empty($id_contract)) {
			echo "false";
			exit;
		}
		$id_time_period = $_GET['id_time_period'];
		if (empty($id_time_period)) {
			echo "false";
			exit;
		}
		$id_kpi = $_GET['id_kpi'];
		if (empty($id_kpi)) {
			echo "false";
			exit;
		}

		$id_step_group = $_GET['id_step_group'];
                if (empty($id_step_group)) {
                        echo "false";
                        exit;
                }

		$sql = "update contract_context SET NAME='".$name."',ALIAS='".$alias."',ID_CONTRACT=".$id_contract.",ID_TIME_PERIOD=".$id_time_period.",ID_KPI=".$id_kpi.",ID_STEP_GROUP=".$id_step_group." where ".$id_name." = ".$id_number;
	}

	else if ($table_name == 'kpi'){
		$name = $_GET['name'];
		if (empty($name)) {
			echo "false";
			exit;
		}
		$alias = $_GET['alias'];
		$id_unit_comput = $_GET['id_unit_comput'];
		if (empty($id_unit_comput)) {
			echo "false";
			exit;
		}
		if (! ctype_digit($id_unit_comput)){
			$id_unit_comput = explode(" ", $id_unit_comput);
		}
		$id_unit_presentation = $_GET['id_unit_presentation'];
		if (empty($id_unit_presentation)) {
			echo "false";
			exit;
		}
		if (! ctype_digit($id_unit_presentation)){
			$id_unit_presentation = explode(" ", $id_unit_presentation);
		}

		$sql = "update kpi SET NAME='".$name."',ALIAS='".$alias."',ID_UNIT_COMPUT=".$id_unit_comput.",ID_UNIT_PRESENTATION=".$id_unit_presentation." where ".$id_name." = ".$id_number;
	}

	else if ($table_name == 'unit'){
		$name = $_GET['name'];
		if (empty($name)) {
			echo "false";
			exit;
		}

		$short_name = $_GET['short_name'];
		if (empty($short_name)) {
			echo "false";
			exit;
		}

		$sql = "update unit SET NAME='".$name."',SHORT_NAME='".$short_name."' where ".$id_name." = ".$id_number;
	}

	else if ($table_name == 'company'){
		$name = $_GET['name'];
		if (empty($name)) {
			echo "false";
			exit;
		}

		$sql = "update company SET NAME='".$name."' where ".$id_name." = ".$id_number;
	}

	else if ($table_name == 'time_period'){
		$name = $_GET['name'];
		if (empty($name)) {
			echo "false";
			exit;
		}
		$alias = $_GET['alias'];

		$sql = "update time_period SET NAME='".$name."',ALIAS='".$alias."' where ".$id_name." = ".$id_number;
	}

	else if ($table_name == 'contract_context_application'){
		$id_contract_context = $_GET['id_contract_context'];
		if (empty($id_contract_context)) {
			echo "false";
			exit;
		}
		$application_name = $_GET['application_name'];
		$name = $application_name;
		if (empty($application_name)) {
			echo "false";
			exit;
		}
		$application_source = $_GET['application_source'];
		if (empty($application_source)) {
			echo "false";
			exit;
		}

		$sql = "update contract_context_application SET ID_CONTRACT_CONTEXT=".$id_contract_context.",APPLICATION_NAME='".$application_name."',APPLICATION_SOURCE='".$application_source."' where ".$id_name." = ".$id_number;
	}

	else if ($table_name == 'step_group'){
		$name = $_GET['name'];
		if (empty($name)) {
			echo "false";
			exit;
		}

		$id_kpi = $_GET['id_kpi'];
		if (empty($id_kpi)) {
			echo "false";
			exit;
		}

		$type = $_GET['type'];
		if (empty($type)) {
			echo "false";
			exit;
		}

		$values_step = $_GET['values_step'];
		$step_number = count($values_step);

		$sql = "update step_group SET NAME='".$name."',ID_KPI=".$id_kpi.",STEP_NUMBER=".$step_number.",TYPE='".$type."'";

		for ($i=1; $i <= $step_number; $i++){
			$sql = $sql . ",STEP_" .$i. "_MIN=".$values_step["$i"][0].",STEP_" .$i. "_MAX=".$values_step["$i"][1];
		}

		$sql = $sql . " where " .$id_name." = ".$id_number;
	}

	else if ($table_name == 'contract'){
		$name = $_GET['name'];
		if (empty($name)) {
			echo "false";
			exit;
		}
		$alias = $_GET['alias'];

		$contract_sdm_intern = $_GET['contract_sdm_intern'];
		if (empty($contract_sdm_intern)) {
			$contract_sdm_intern = NULL;
		}
		$contract_sdm_extern = $_GET['contract_sdm_extern'];
		if (empty($contract_sdm_extern)) {
			$contract_sdm_extern = NULL;
		}
		$id_company = $_GET['id_company'];
		if (empty($id_company)) {
			echo "false";
			exit;
		}
		$extern_contract_id = $_GET['extern_contract_id'];
		if (empty($extern_contract_id)) {
			$extern_contract_id = NULL;
		}
		$validity_date = $_GET['validity_date'];
		if (empty($validity_date)) {
			echo "false";
			exit;
		}

		$sql = "update contract SET NAME='".$name."',ALIAS='".$alias."',CONTRACT_SDM_INTERN='".$contract_sdm_intern."',CONTRACT_SDM_EXTERN='".$contract_sdm_extern."',ID_COMPANY=".$id_company.",EXTERN_CONTRACT_ID='".$extern_contract_id."',VALIDITY_DATE='".$validity_date."' where ".$id_name." = ".$id_number;

	}

try {
        $bdd = new PDO("mysql:host=$database_host;dbname=$database_vanillabp", $database_username, $database_password);
    } catch(Exception $e) {
		 echo "Connection failed: " . $e->getMessage();
        exit('Impossible de se connecter à la base de données.');
    }

	$bdd->exec($sql);

	$status = "Update";
	include('../php/save_last_entry.php');

	if ($table_name == 'time_period'){
		$values = $_GET['values'];

		// on supprime les anciennes entrees
		$sql = "delete from timeperiod_entry where ID_TIME_PERIOD = ".$id_number;
		$bdd->exec($sql);

		for ($i = 1; $i <= count($values); $i++){
			$day = $values["$i"][0];
			$h_open = $values["$i"][1].":".$values["$i"][2];
			$h_close = $values["$i"][3].":".$values["$i"][4];

			$sql = "insert into timeperiod_entry (ID_TIME_PERIOD,ENTRY,H_OPEN,H_CLOSE) values(".$id_number.",'".$day."','".$h_open."','".$h_close."')";
			$bdd->exec($sql);
		}
	}

	echo "true";
?>
