<?php 
include("../../header.php");
include("../../side.php");
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-md-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.contract_context_application_title"); ?></h1>
		</div>
	</div>
	<div class="col-md-7">
		<form class="form-horizontal" id="global_form">
			<div class="row pad-top">
				<div class="form-group has-feedback div-context">
					<label style="font-weight:normal;" for="name_contract_context" class="col-md-4 control-label"><?php echo getLabel("label.contract_context"); ?> : </label>
					<div class="col-md-7 input-context">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_contract_context"><?php echo getLabel("label.manage_contracts.contract_context_view_title"); ?>
						<span class="caret"></span></button>
						<ul class="dropdown-menu btn-block" id="ul_context"></ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group has-feedback">
					<label style="font-weight:normal;" for="application_name" class="col-md-4 control-label"><?php echo getLabel("label.application"); ?> : </label>
					<div class="col-md-7">
					<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="application_name"><?php echo getLabel("label.manage_contracts.contract_context_select_application"); ?>
					<span class="caret"></span></button>
					<ul class="dropdown-menu btn-block" id="ul_application"></ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<button class="form-group btn btn-primary" type="submit" id="submit_entry"><?php echo getLabel("label.contracts_menu.application_menu_create_btn_add"); ?>
					<span class="glyphicon glyphicon-ok" style="color:#4f4;"></span>
					</button>
				</div>
			</div>
		</form>
	</div>
	<div id="application_list" class="row" style="display: none;">
		<div class="col-md-12">
			<div class="form-group">
				<h2 class="page-header"><?php echo getLabel("label.contracts_menu.application_menu_create_title_list"); ?></h2>
			</div>
		</div>
		<div class="col-md-7">
			<table class="table table-striped table-hover" id="container_application">
				<thead>
					<tr>
						<th><?php echo getLabel("menu.link.app"); ?></th>
						<th><?php echo getLabel("label.suppress"); ?></th>
					</tr>
				</thead>
				<tbody id="body_table">
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-3">
					<button class="form-group btn btn-primary " type="submit" id="submit"><?php echo getLabel("action.submit"); ?>
						<span class="glyphicon glyphicon-ok" style="color:#4f4;"></span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
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
				DisplayAlertMissing("Vous devez créer une fiche de contexte de contrat</br>avant de pouvoir crÃ©er une Application");	
			} else {
				$("#global_form").css("display", "inline");
				$.each(values, function(v, k){
					$name = k['NAME'];
					$id = k['ID_CONTRACT_CONTEXT'];
					$("#ul_context").append('<li><a class="dropdown-item" id="'+$name+'_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $name + '</a></li>');
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
					DisplayAlertSuccess('Application sauvegardée', "contract_context_application_view.php");
				}
				else if (value == "false"){
					DisplayAlertWarning('Veuillez saisir les champs obligatoire');
				}
				else {
					DisplayAlertWarning('Impossible de se connecter à  la base de données');
				}
			}
		);
	});

  $("#submit_entry").click(function(event){
		event.preventDefault();
		if($("#application_name_hide").val() == ""){
			DisplayAlertWarning("Vous n'avez pas sélectionnez d'application");
			return false;
		} else {
			var status;
			$.each($global_array, function(v, k){
				if(k == $('#application_name_hide').val()){
					DisplayAlertWarning("Cette application a déjà été ajoutée");
					status = 'stop';
					return false;
				}
			});
			if(status == 'stop'){
				return false;
			}
			$counter++;
			$name = $('#application_name_hide').val();
			$("#application_list").toggle();
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
	$array_name_id = value.split("_");
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
      $('#application_name').html('Selectionnez une application <span class="caret"></span>');
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

<?php
include("../../footer.php");
?>