<!DOCTYPE html>

<html lang="fr">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Generate or view contracts">
		<meta name="author" content="Stacy Maillot">
		<title>Contrats</title>

		<link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="../../bower_components/jquery-ui/themes/base/jquery-ui.min.css" rel="stylesheet">
    <link href="css/design.css" rel="stylesheet">

	</head>

	<body onload="InitLoad()">
    
  </body>
<script type="text/javascript"> <!-- affichage au chargement -->
function InitLoad() {
	window.location.replace("http://<?php echo $_SERVER['SERVER_NAME'] ?>/kettle/status/");
}
</script>

</html>

