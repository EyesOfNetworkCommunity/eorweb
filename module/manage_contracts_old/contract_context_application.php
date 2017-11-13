<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<h3>Cr&eacute;ation d'une Application<h3>
</div>
<br>
<div class="pull-right col-md-5">
        <label style="font-weight:lighter;" class="control-label">Les champs marqu&eacute;s d'une </label>
        <span class="glyphicon glyphicon-asterisk" style="font-size:10px;color:#707070;"></span>
        <label style="font-weight:lighter;" class="control-label"> sont obligatoires</label>
</div>

<form class="form-horizontal col-md-7 marge" id="global_form" style="display:none">
	<div class="row pad-top">
		<div class="form-group has-feedback div-context">
			<label style="font-weight:normal;" for="name_contract_context" class="col-md-4 control-label">Contexte de contrat : </label>
			<div class="col-md-7 input-context">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_contract_context">S&eacute;lectionner un contexte de contrat
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_context">
				</ul>
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group has-feedback">
			<label style="font-weight:normal;" for="application_name" class="col-md-4 control-label">Application : </label>
			<div class="col-md-7">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="application_name">S&eacute;lectionnez une application
				  <span class="caret"></span>
        </button>
				<ul class="dropdown-menu btn-block" id="ul_application">
				</ul>
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>
	</div>

  <div class="row pull-right">
    <div class="col-md-3">
		  <button class="form-group btn btn-primary" type="submit" id="submit_entry">Ajouter une Application
		    <span class="glyphicon glyphicon-ok" style="color:#4f4;"></span>
		  </button>
		</div>
  </div>

  <div class="col-md-7" style="display:none">
    <input type="text" class="form-control" id="id_contract_context">
	</div>
 
  <div class="col-md-7" style="display:none">
    <input type="text" class="form-control" id="application_name_hide">
	</div>

  <div class="row">
    <div class="form-group marge">
		  <legend>Liste des Applications</legend>
	  </div>
  </div>

	<div class="row col-md-10 marge">
	<table class="table" style="display:none" id="container_application">
	    <thead>
		    <tr>
		        <th>Applications</th>
		        <th>Suppression</th>
		    </tr>
	    </thead>
		<tbody id="body_table">
		</tbody>
	</table>
	</div>

	<button class="form-group btn btn-primary pull-right" type="submit" id="submit">Envoyer
		<span class="glyphicon glyphicon-ok" style="color:#4f4;"></span>
	</button>

</form>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="./js/library.js"></script>

<script>
$(document).ready(function() {
	$global_array = {};
	$counter = 0;

	$.get(
		'./php/get_name_id.php',
		{
			table_name:'contract_context',
			id: 'ID_CONTRACT_CONTEXT'
		},
		function ReturnName(values){
			if(values.length == 0){
				DisplayAlertMissing("Vous devez cr&eacute;er une fiche de contexte de contrat</br>avant de pouvoir cr&eacute;er une Application");	
			}

			else{
				$("#global_form").css("display", "inline");
				$.each(values, function(v, k){
					$name = k['NAME'];
					$id = k['ID_CONTRACT_CONTEXT'];
					$("#ul_context").append('<li><a class="dropdown-item" id="'+$name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $name + '</a></li>');
				});
			}
		},
		'json'
	);

  $.get(
		'./php/select_all_applications.php',
		{
			table_name:"bp"
		},

		function ReturnAllApplications(values){
			$.each(values, function(v, k){
				$name = k['name'];
				$("#ul_application").append('<li><a class="dropdown-item" id="'+$name+'" href="javascript:void(0);" onclick="ChangeApplication(id);">' + $name + '</a></li>');
			});
    },
		'json'
	);

	$("#submit").click(function(event){
		event.preventDefault();
		$.get(
			'./php/new_entry.php',
			{
				table_name: 'contract_context_application',
				id_contract_context: $("#id_contract_context").val(),
				applications: $global_array
			},
			function GotoContextView(value){
				if (value == "true"){
					DisplayAlertSuccess('Application sauvegard&eacute;', "contract_context_application_view.php");
				}
				else if (value == "false"){
					DisplayAlertWarning('Veuillez saisir les champs obligatoire');
				}
				else {
					DisplayAlertWarning('Impossible de se connecter &agrave; la base de donn&eacute;es');
				}
			}
		);
	});

  $("#submit_entry").click(function(event){
		event.preventDefault();
		if($("#application_name_hide").val() == ""){
      DisplayAlertWarning("Vous n'avez pas s&eacute;lectionnez d'application");
			return false;
		}

		else{
			var status;
			$.each($global_array, function(v, k){
				if(k == $('#application_name_hide').val()){
					DisplayAlertWarning("Cette application a d&eacute;j&agrave; &eacute;t&eacute; ajout&eacute;e");
					status = 'stop';
					return false;
				}
			});

			if(status == 'stop'){
				return false;
			}
      
      $counter++;
      $name = $('#application_name_hide').val();

			if($('#container_application').is(':hidden')){
				$('#container_application').css('display', 'inline');
			}
			$('#body_table').append('<tr id="'+$name+'"><td>' + $name + '</td><td><button type="button" class="btn btn-danger" id="'+$name+'" onclick=RemoveEntry(id)><span class="glyphicon glyphicon-remove"></span></button></td></tr>');

			$global_array[$counter] = $('#application_name_hide').val();
			$('#application_name').html('Sélectionnez une application <span class="caret"></span>');
		}
	});

});

function ChangeValue(value){
	$array_name_id = value.split("_-_");
	$name = $array_name_id[0];
	$id = $array_name_id[1];
 
  $("#name_contract_context").html($name+'  <span class="caret"></span></button>');
  $("#id_contract_context").val($id);
  
  $.get(
		'./php/select_application_by_context_id.php',
		{
			table_name: "contract_context_application",
      id: $id
		},

		function ReturnAllApplications(values){
      if($('#container_application').is(':hidden')){
				$('#container_application').css('display', 'inline');
      }
			$.each(values, function(v, k){
				$name = k['APPLICATION_NAME'];
        
        $('#body_table').append('<tr id="'+$name+'"><td>' + $name + '</td><td><button type="button" class="btn btn-danger" id="'+$name+'" onclick=RemoveEntry(id)><span class="glyphicon glyphicon-remove"></span></button></td></tr>');
        
        $counter++;
        $global_array[$counter] = $name;
      });
      $('#application_name').html('S&eacute;lectionnez une application <span class="caret"></span>');
    },
    'json'
   );
}

function ChangeApplication(app){
  $("#application_name").html(app+'  <span class="caret"></span></button>');
  $("#application_name_hide").val(app);
}

function RemoveEntry(value){
  $array = {};
  $index = 0;
  var count = $.map($global_array, function(n, i) { return i; }).length;

	$('tr[id="' + value +'"]').remove();

  for ($i = 1; $i <= count; $i++){
    if($global_array[$i] != value){
      $index++;
      $array[$index] = $global_array[$i];
      continue;
    }
  }

	$global_array = $array;
	$counter = $index;
}

</script>

</body>

</html>


