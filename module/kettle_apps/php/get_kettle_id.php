<?php

	$begin_date = $_GET['begin_date'];
	$end_date = $_GET['end_date'];	


	$xml_string = stream_get_contents(fopen('http://cluster:root66@localhost:8181/kettle/runJob/?job=/Alimentation/JOB_MAIN_DATE&datebeg=' . $begin_date . '&dateend=' . $end_date, "r"));
	$dom = new DOMDocument;
	$dom->loadXML($xml_string);
	$id = $dom->getElementsByTagName("id")->item(0);
	echo $id->nodeValue;
?>
