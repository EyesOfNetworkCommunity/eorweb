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

	<body>
    </br>
    <div class="container container-title bg-primary">
      <h3>Eyes Of Report data loader</h3>
    </div>
    
    <div class="text-primary container pad-top">
      <h4>Main workflow</h4>
    </div>
    
    <form class="form-horizontal col-md-7 marge" id="global">
    
      <div class="row form-group">
		    <label style="font-weight:normal;" for="date_debut" class="col-md-8 control-label"><b>Start date (AAAAMMJJ) :</b></label>
		    <div class="col-md-4 input-name">
			    <input type="tel" maxlength="8" class="form-control" id="date_debut" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')">
		    </div>
      </div>
      
      <div class="row form-group">
        <label style="font-weight:normal;" for="date_fin" class="col-md-8 control-label"><b>End date (AAAAMMJJ) :</b></label>
		    <div class="col-md-4 input-name">
			    <input type="tel" maxlength="8" class="form-control" id="date_fin" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')">
		    </div>
	    </div>
      </br>
      <div class="row col-md-6 pull-right">
				<button class="form-group btn btn-primary" type="submit" id="launch">Launch WorkFlow
					<span class="glyphicon glyphicon-ok" style="color:#4f4;"></span>
				</button>
			</div>
    </form>

    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    
    <script>
    $(document).ready(function() {
      $('#launch').click(function(event){
        event.preventDefault();
	$.get(
		'./php/get_kettle_id.php',
		{
			begin_date: $('#date_debut').val(),
			end_date: $('#date_fin').val()
		},
		function ReturnID(id){
			//console.log(id);
			$(location).attr('href', 'http://<?php echo $_SERVER['SERVER_NAME'] ?>/kettle/jobStatus/?name=JOB_MAIN_DATE&id=' + id);
		},
		'text'
	);
      });
    });
    
    </script>

  </body>

</html>

