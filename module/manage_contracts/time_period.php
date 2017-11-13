<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<h3>Création d'une période de temps</h3>
</div>
<br>
<div class="pull-right col-md-5">
        <label style="font-weight:lighter;" class="control-label">Les champs marqués d'une </label>
        <span class="glyphicon glyphicon-asterisk" style="font-size:10px;color:#707070;"></span>
        <label style="font-weight:lighter;" class="control-label"> sont obligatoires</label>
</div>

<form class="form-horizontal col-md-7 marge" id="global_form">
	<div class="row pad-top">
		<div class="form-group has-feedback div-name">
			<label style="font-weight:normal;" for="name" class="col-md-4 control-label">Nom de la période de temps : </label>
			<div class="col-md-7 input-name">
				<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>	
	</div>

	<div class="row">
		<div class="form-group has-feedback div-desc">
			<label style="font-weight:normal;" for="desc" class="col-md-4 control-label">Description : </label>
			<div class="col-md-7 input-desc">
				<input type="text" class="form-control" id="desc" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*éèàêâç]/g,'')">
			</div>
		</div>	
	</div>

	<div class="form-group marge">
		<legend>Créer une entrée</legend>
	</div>

	<div class="row">
		<div class="form-group has-feedback div-entry">
			<label style="font-weight:normal;" for="name_entry" class="col-md-4 control-label">Jour : </label>
			<div class="col-md-4 input-entry">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_entry">Jour
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_entry">
				</ul>
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group has-feedback">
			<label style="font-weight:normal;" for="start_hour" class="col-md-4 control-label">Heure de début : </label>
			<div class="col-md-2">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="start_hour">Heure
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_start_hour">
				</ul>
			</div>
			<div class="col-md-2">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="start_min">Minute
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_start_min">
				</ul>
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group has-feedback">
			<label style="font-weight:normal;" for="end_hour" class="col-md-4 control-label">Heure de fin : </label>
			<div class="col-md-2">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="end_hour">Heure
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_end_hour">
				</ul>
			</div>

			<div class="col-md-2">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="end_min">Minute
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_end_min">
				</ul>
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
			<div class="col-md-3">
				<button class="form-group btn btn-primary pull-right" type="submit" id="submit_entry">Ajouter une entrée
					<span class="glyphicon glyphicon-ok" style="color:#4f4;"></span>
				</button>
			</div>
		</div>
	</div>

	<input type="text" class="form-control" style="display:none" id="name_entry_hide">
	<input type="text" class="form-control" style="display:none" id="start_hour_hide">
	<input type="text" class="form-control" style="display:none" id="start_min_hide">
	<input type="text" class="form-control" style="display:none" id="end_hour_hide">
	<input type="text" class="form-control" style="display:none" id="end_min_hide">

	<div class="form-group marge">
		<legend>Liste des entrées</legend>
	</div>

	<div class="container col-md-10 marge">
	<table class="table" style="display:none" id="container_time_period">
	    <thead>
		    <tr>
		        <th>Jour</th>
		        <th>Heure début</th>
		        <th>Heure fin</th>
				<th>Suppression</th>
		    </tr>
	    </thead>
		<tbody id="body_table">
		</tbody>
	</table>
	</div>

	<div class="container col-md-10 marge pad-top" style="padding-bottom:50px;" id="div_button_submit">
	<tr>
	<button class="col-md-8 form-group btn btn-primary" type="submit" id="submit">Envoyer
		<span class="glyphicon glyphicon-ok" style="color:#4f4;"></span>
	</button>
	</div>
</form>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="./js/library.js"></script>

<script>
$(document).ready(function() {
	$counter = 0;
	$global_array = {};

	if (UrlParam('id_number') != false){
		$('#container_time_period').css('display', 'inline');
		$.get(
			'./php/view_entry.php',
			{
				table_name: 'time_period',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
				$('#desc').val(values['ALIAS']);
			},
			'json'
		);
		$.get(
			'./php/get_values_timeperiod_entry.php',
			{
				table_name: 'timeperiod_entry',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$.each(values, function(v, k){
					$counter++;
					$get_entry = k['ENTRY'];
					$get_start_hour_full = k['H_OPEN'];
					$get_end_hour_full = k['H_CLOSE'];
					$get_start_hour = $get_start_hour_full.split(":")[0];
					$get_start_min = $get_start_hour_full.split(":")[1];
					$get_end_hour = $get_end_hour_full.split(":")[0];
					$get_end_min = $get_end_hour_full.split(":")[1];

					$('#body_table').append('<tr id="'+$counter+'"><td>' + $get_entry + '</td><td>' + $get_start_hour + ':' +$get_start_min + '</td><td>' + $get_end_hour + ':' + $get_end_min + '</td><td><button type="button" class="btn btn-danger" id="'+$counter+'" onclick=RemoveEntry(id)><span class="glyphicon glyphicon-remove"></span></button></td></tr>');

					var arr = [$get_entry,$get_start_hour,$get_start_min,$get_end_hour,$get_end_min];

					$global_array[$counter] = arr;
				});
			},
			'json'
		);
	}

	AddDays();

	for(var i= 0; i < 24; i++){
		if(i < 10){
			i = '0' + i;
		}
		$('#ul_start_hour').append('<li><a class="dropdown-item" id="starthour_'+i+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + i + '</a></li>');
		$('#ul_end_hour').append('<li><a class="dropdown-item" id="endhour_'+i+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + i + '</a></li>');
	}

	for (var i= 0; i < 60; i++){
		if(i < 10){
			i = '0' + i;
		}
		$('#ul_start_min').append('<li><a class="dropdown-item" id="startmin_'+i+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + i + '</a></li>');
		$('#ul_end_min').append('<li><a class="dropdown-item" id="endmin_'+i+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + i + '</a></li>');
	}

	$('#submit').click(function(event){
		event.preventDefault();
		if (UrlParam('id_number') != false){
			$.get(
				'./php/update_entry.php',
				{
					table_name: 'time_period',
					name: $('#name').val(),
					alias: $("#desc").val(),
					values: $global_array,
					id_number: UrlParam('id_number')
				},
				function ShowMsg(value){
					if (value == "true"){
						DisplayAlertSuccess('Période de temps sauvegardée', "time_period_view.php");
					}
					else if (value == "false"){
						DisplayAlertWarning('Veuillez saisir les champs obligatoire');
					}
					else {
						DisplayAlertWarning('Impossible de se connecter à la base de données');
					}
				}
			);
		}

		else{
			$.get(
				'./php/new_entry.php',
				{
					table_name: 'time_period',
					name: $("#name").val(),
					alias: $("#desc").val(),
					values: $global_array
				},
				function GotoContextView(value){
					if (value == "true"){
						DisplayAlertSuccess('Période de temps sauvegardée', "time_period_view.php");
					}
					else if (value == "false"){
						DisplayAlertWarning('Veuillez saisir les champs obligatoire');
					}
					else {
						DisplayAlertWarning('Impossible de se connecter à la base de données');
					}
          return false;
				}
			);
		}
	});

	$('#submit_entry').click(function(event){
		event.preventDefault();
		if($('#name_entry_hide').val() == "" || $('#start_hour_hide').val() == "" || $('#start_min_hide').val() == "" || $('#end_hour_hide').val() == "" || $('#end_min_hide').val() == ""){
      DisplayAlertWarning('tous les champs ne sont pas remplis');
			return false;
		}

		else{
			if($('#start_hour_hide').val() > $('#end_hour_hide').val()){
				DisplayAlertWarning('Les valeurs des entrées sont incohérentes');
				return false;
			}

			if($('#start_hour_hide').val() == $('#end_hour_hide').val() && $('#start_min_hide').val() >= $('#end_min_hide').val()){
				DisplayAlertWarning('Les valeurs des entrées sont incohérentes');
				return false;
			}

			var status;
			$.each($global_array, function(v, k){
				console.log(k);
				if(k[0] == $('#name_entry_hide').val()){
					if($('#start_hour_hide').val() < k[3] || $('#start_hour_hide').val() == k[3] && $('#start_min_hide').val() <= k[4]){
						DisplayAlertWarning("L'entrée se chevauchent avec une entrée précédente");
						status = 'stop';
						return false;
					}
				}
			});

			if(status == 'stop'){
				return false;
			}
      
      $counter++;

			if($('#div_button_submit').is(':hidden')){
                                $('#div_button_submit').css('display', 'inline');
                        }

			if($('#container_time_period').is(':hidden')){
				$('#container_time_period').css('display', 'inline');
			}
			$('#body_table').append('<tr id="'+$counter+'"><td>' + $('#name_entry_hide').val() + '</td><td>' + $('#start_hour_hide').val() + ':' + $('#start_min_hide').val() + '</td><td>' + $('#end_hour_hide').val() + ':' + $('#end_min_hide').val() + '</td><td><button type="button" class="btn btn-danger" id="'+$counter+'" onclick=RemoveEntry(id)><span class="glyphicon glyphicon-remove"></span></button></td></tr>');

			var arr = [$('#name_entry_hide').val(), $('#start_hour_hide').val(), $('#start_min_hide').val(), $('#end_hour_hide').val(), $('#end_min_hide').val()];

			$global_array[$counter] = arr;
			$('#name_entry').html('Jour <span class="caret"></span>');
		}
	});

});

function AddDays(value_day){
	var days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

	for(var i= 0; i < days.length; i++){
		var day = days[i];

		if(value_day == day){
			continue;
		}
		
		$('#ul_entry').append('<li id="day_'+day+'"><a class="dropdown-item" id="day_'+day+'" href="javascript:void(0);" onclick="ChangeValue(id);">' + day + '</a></li>');
	}
}
	

function ChangeValue(values){
	var array_values = values.split("_");
	var object_name = array_values[0];
	var value = array_values[1];

	if(object_name == 'day'){
		$('#name_entry').html(value + ' <span class="caret"></span>');
		$('#name_entry_hide').val(value);
	}
	else if(object_name == 'starthour'){
		$('#start_hour').html(value + ' <span class="caret"></span>');
		$('#start_hour_hide').val(value);
	}
	else if(object_name == 'startmin'){
		$('#start_min').html(value + ' <span class="caret"></span>');
		$('#start_min_hide').val(value);
	}
	else if(object_name == 'endhour'){
		$('#end_hour').html(value + ' <span class="caret"></span>');
		$('#end_hour_hide').val(value);
	}
	else if(object_name == 'endmin'){
		$('#end_min').html(value + ' <span class="caret"></span>');
		$('#end_min_hide').val(value);
	}
}

function RemoveEntry(value){
  $array = {};
  $index = 0;
  var count = $.map($global_array, function(n, i) { return i; }).length;

	$('tr[id="' + value +'"]').remove();

  for ($i = 1; $i <= count; $i++){
    if($i != parseInt(value)){
      $index++;
      $array[$index] = $global_array[$i];
      continue;
    }
  }

	$global_array = $array;
	$counter = $index;
  console.log($global_array);
  console.log($counter);
}

</script>

</body>

</html>


