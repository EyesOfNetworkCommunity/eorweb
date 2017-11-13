<?php include('../../../include/config.php');

$sql = "SELECT COUNT(*) FROM last_entry";
	$req = $bdd->query($sql);
	$number = $req->fetch();
	$my_value = intval($number['COUNT(*)']);

	if($my_value >= 5){
		$sql = "select ID_LAST_ENTRY from last_entry limit 1";
		$req = $bdd->query($sql);
		$array_id = $req->fetch();
		$id_to_remove = intval($array_id['ID_LAST_ENTRY']);

		$sql = "delete from last_entry where ID_LAST_ENTRY = " .$id_to_remove;
		$bdd->exec($sql);
	}

	$actually_date = date('Y-m-d H:i:s');

	$sql = "insert into last_entry (STATUS,TABLE_NAME,NAME,DATE_ENTRY) values('".$status."','" .$table_name. "', '" .$name. "','" .$actually_date. "')";
	$bdd->exec($sql);
?>
