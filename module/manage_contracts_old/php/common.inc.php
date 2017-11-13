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
		<link href="css/design.css" rel="stylesheet">
		<link href="../../bower_components/jquery-ui/themes/base/jquery-ui.min.css" rel="stylesheet">
		<link href="../../bower_components/datatables/media/css/jquery.dataTables.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>
		<nav class="navbar navbar-default drop-property">
			<div class="container">
				<ul class="nav navbar-nav">

					<li class="dropdown" role="menu" aria-labelledby="dLabel">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><h6>Contexte de contrat
						<span class="caret"></span></h6></a>
						<ul class="dropdown-menu drop-property">
							<li><a href="contract_context.php"><h6>Créer
								<span class="glyphicon glyphicon-pencil"></span></h6>
							</a></li>
							<li><a href="contract_context_view.php"><h6>Visionner
								<span class="glyphicon glyphicon-eye-open"></span></h6>
							</a></li>
						</ul>
					</li>

					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><h6>Contrat
						<span class="caret"></span></h6></a>
						<ul class="dropdown-menu drop-property">
							<li><a href="contract.php"><h6>Créer
								<span class="glyphicon glyphicon-pencil"></span></h6>
							</a></li>
							<li><a href="contract_view.php"><h6>Visionner
								<span class="glyphicon glyphicon-eye-open"></span></h6>
							</a></li>
						</ul>
					</li>

					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><h6>Entreprise
						<span class="caret"></span></h6></a>
						<ul class="dropdown-menu drop-property">
							<li><a href="company.php"><h6>Créer
								<span class="glyphicon glyphicon-pencil"></span></h6>
							</a></li>
							<li><a href="company_view.php"><h6>Vsionner
								<span class="glyphicon glyphicon-eye-open"></span></h6>
							</a></li>
						</ul>
					</li>

					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><h6>Période de temps
						<span class="caret"></span></h6></a>
						<ul class="dropdown-menu drop-property">
							<li><a href="time_period.php"><h6>Créer
								<span class="glyphicon glyphicon-pencil"></span></h6>
							</a></li>
							<li><a href="time_period_view.php"><h6>Visionner
								<span class="glyphicon glyphicon-eye-open"></span></h6>
							</a></li>
						</ul>
					</li>

					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><h6>Indicateur
						<span class="caret"></span></h6></a>
						<ul class="dropdown-menu drop-property">
							<li><a href="kpi.php"><h6>Créer
								<span class="glyphicon glyphicon-pencil"></span></h6>
							</a></li>
							<li><a href="kpi_view.php"><h6>Visionner
								<span class="glyphicon glyphicon-eye-open"></span></h6>
							</a></li>
						</ul>
					</li>

					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><h6>Seuils
						<span class="caret"></span></h6></a>
						<ul class="dropdown-menu drop-property">
							<li><a href="step_group.php"><h6>Créer
								<span class="glyphicon glyphicon-pencil"></span></h6>
							</a></li>
							<li><a href="step_group_view.php"><h6>Visionner
								<span class="glyphicon glyphicon-eye-open"></span></h6>
							</a></li>
						</ul>
					</li>

					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><h6>Application
						<span class="caret"></span></h6></a>
						<ul class="dropdown-menu drop-property">
							<li><a href="contract_context_application.php"><h6>Créer
								<span class="glyphicon glyphicon-pencil"></span></h6>
							</a></li>
							<li><a href="contract_context_application_view.php"><h6>Visionner
								<span class="glyphicon glyphicon-eye-open"></span></h6>
							</a></li>
						</ul>
					</li>

				</ul>
  			</div>
		</nav>
