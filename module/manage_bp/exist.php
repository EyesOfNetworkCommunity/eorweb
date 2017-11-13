<?php
	include("../../include/config.php");
	include("../../include/arrays.php");
	include("../../include/function.php");
	//Read the file to get the informations.
	$Source = $_POST[source]; //var_dump($_POST["source"]);
	$Valeur = $_POST[valeur]; //var_dump($_POST["valeur"]);
	//echo $Source ." - ". echo $Valeur;
	$SQLRequest="SELECT '$Source' as source, name, description, priority, type,command,url,min_value,is_define FROM bp WHERE name='$_POST[valeur]'";
	// print ("Source: $Source, Valeur: $Valeur\nSQLRequest: $SQLRequest\n\n\n");

	$result = sqlrequest($Source,$SQLRequest);
	$metier = mysql_fetch_assoc($result);
	
	// var_dump($metier);
	if ( $metier ){
		foreach ($metier as $attribut) echo trim($attribut)."\n";
		echo "mod";
		exit ;
	}

	//Check if the name is correct. -- \\\ To match a single \ !
	$regEx = "/[\\\àáâãäåçèéêëìíîïðòóôõöùúûüýÿ!\"#$%&'()*+,.\/:;<=>^`{|}~?@[\]²§€\s]/";

	if ( preg_match($regEx,$_POST['valeur'])) {
		message(10," - Wrong caracteres (No Accents nor Whitespaces nor Ponctuation except - and _ )","warning");
		exit;
	}

	echo "ok";
	exit;
?>