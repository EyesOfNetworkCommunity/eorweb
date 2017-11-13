<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<h3>Création d'un contexte de contrat</h3>
</div>
<br>
<div class="pull-right col-md-5">
	<label style="font-weight:lighter;" class="control-label">Les champs marqués d'une </label>
	<span class="glyphicon glyphicon-asterisk" style="font-size:10px;color:#707070;"></span>
	<label style="font-weight:lighter;" class="control-label"> sont obligatoires</label>
</div>

<form class="form-horizontal col-md-7 marge" id="global_form" style="display:none">
	<div class="row pad-top">
		<div class="form-group has-feedback div-name">
			<label style="font-weight:normal;" for="name" class="col-md-4 control-label">Nom du contexte de contrat : </label>
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
				<input type="text" class="form-control" id="desc" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
			</div>
		</div>	
	</div>

	<div class="row">
		<div class="form-group has-feedback div-contract">
			<label style="font-weight:normal;" for="name_contract" class="col-md-4 control-label">Contrat : </label>
			<div class="col-md-7 input-contract">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_contract">Selectionner un Contrat
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_contract">
				</ul>
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group has-feedback div-time">
			<label style="font-weight:normal;" for="name_time_period" class="col-md-4 control-label">Période de temps : </label>
			<div class="col-md-7 input-time">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_time_period">Selectionner une Période de temps
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_time">
				</ul>
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group has-feedback div-kpi">
			<label style="font-weight:normal;" for="name_kpi" class="col-md-4 control-label">Indicateur : </label>
			<div class="col-md-7 input-kpi">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_kpi">Selectionner un Indicateur
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_kpi">
				</ul>
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>
	</div>

	<div class="row" id="display_step_group" style="display:none">
                <div class="form-group has-feedback">
                        <label style="font-weight:normal;" for="name_step_group" class="col-md-4 control-label">Seuils associés à l'indicateur</label>
                        <div class="col-md-7">
                                <button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_step_group">Selectionner un groupe de seuil
                                <span class="caret"></span></button>
                                <ul class="dropdown-menu btn-block" id="ul_step_group">
                                </ul>
                        </div>
                        <div class="control-label form-group col-md-1">
                                <span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
                        </div>
                </div>
        </div>

	<div class="col-md-7" style="display:none">
		<input type="text" class="form-control" id="id_contract">
	</div>

	<div class="col-md-7" style="display:none">
		<input type="text" class="form-control" id="id_time_period">
	</div>

	<div class="col-md-7" style="display:none">
		<input type="text" class="form-control" id="id_kpi">
	</div>

	<div class="col-md-7" style="display:none">
                <input type="text" class="form-control" id="id_step_group">
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
	if (UrlParam('id_number') != false){
		$.get(
			'./php/view_entry.php',
			{
				table_name: 'contract_context',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
				$('#desc').val(values['ALIAS']);
				$('#id_contract').val(values['ID_CONTRACT']);
				$('#id_time_period').val(values['ID_TIME_PERIOD']);
				$('#id_kpi').val(values['ID_KPI']);
				$('#id_step_group').val(values['ID_STEP_GROUP']);
                                                       
				$.get(
				  './php/select_name_by_id.php',
				  {
					table_name: 'contract',
					id_number: values['ID_CONTRACT']
				  },
				  function return_name(name){
					$('#name_contract').html(name['NAME']+'  <span class="caret"></span></button>');
				  },
				  'json'
				);

				$.get(
				  './php/select_name_by_id.php',
				  {
					table_name: 'time_period',
					id_number: values['ID_TIME_PERIOD']
				  },
				  function return_name(name){
					$('#name_time_period').html(name['NAME']+'  <span class="caret"></span></button>');
				  },
				  'json'
				);
		
				$.get(
				  './php/select_name_by_id.php',
				  {
					table_name: 'kpi',
					id_number: values['ID_KPI']
				  },
				  function return_name(name){
					$('#name_kpi').html(name['NAME']+'  <span class="caret"></span></button>');
				  },
				  'json'
				);

				$.get(
                                  './php/select_name_by_id.php',
                                  {
                                        table_name: 'step_group',
                                        id_number: values['ID_STEP_GROUP']
                                  },
                                  function return_name(name){
                                        $('#name_step_group').html(name['NAME']+'  <span class="caret"></span></button>');
                                  },
                                  'json'
                                );
			},
			'json'
		);
	}

	$counter = 0;
	$.get(
		'./php/get_name_id.php',
		{
			table_name:'contract',
			id: 'ID_CONTRACT'
		},
		function ReturnName(values){
			if(values.length == 0){
				$counter = $counter + 1;
			}

			else{
				$.each(values, function(v, k){
					$contract_name = k['NAME'];
					$id = k['ID_CONTRACT'];
					$('#ul_contract').append('<li><a class="dropdown-item" id="contract_-_'+$contract_name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $contract_name + '</a></li>');
				});
			}
		},
		'json'
	);

	$.get(
		'./php/get_name_id.php',
		{
			table_name:'kpi',
			id: 'ID_KPI'
		},
		function ReturnName(values){
			if(values.length == 0){
				$counter = $counter + 1;
			}

			else{
				$.each(values, function(v, k){
					$kpi_name = k['NAME'];
					$id = k['ID_KPI'];
					$('#ul_kpi').append('<li><a class="dropdown-item" id="kpi_-_'+$kpi_name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $kpi_name + '</a></li>');
				});
			}
		},
		'json'
	);

	$.get(
		'./php/get_name_id.php',
		{
			table_name:'time_period',
			id: 'ID_TIME_PERIOD'
		},
		function ReturnName(values){
			if(values.length == 0 || $counter > 0){
				DisplayAlertMissing("Vous devez créer au moins un Indicateur, une période de temps et un contrat<br/>avant de pouvoir créer un contexte de contrat");
			}

			else{
				$('#global_form').css("display", "inline");
				$.each(values, function(v, k){
					$time_name = k['NAME'];
					$id = k['ID_TIME_PERIOD'];
					$('#ul_time').append('<li><a class="dropdown-item" id="time_-_'+$time_name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $time_name + '</a></li>');
				});
			}
		},
		'json'
	);

	$('#submit').click(function(event){
		event.preventDefault();

		if (UrlParam('id_number') != false){
			$.get(
				'./php/update_entry.php',
				{
					table_name: 'contract_context',
					name: $('#name').val(),
					alias: $('#desc').val(),
					id_number: UrlParam('id_number'),
					id_contract: $('#id_contract').val(),
					id_time_period: $('#id_time_period').val(),
					id_kpi: $('#id_kpi').val(),
					id_step_group: $('#id_step_group').val()
				},
				function ShowMsg(value){
					if (value == "true"){
						DisplayAlertSuccess('Contexte de contrat sauvegardé', "contract_context_view.php");
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
					table_name: 'contract_context',
					name: $("#name").val(),
					alias: $("#desc").val(),
					id_contract: $("#id_contract").val(),
					id_time_period: $("#id_time_period").val(),
					id_kpi: $("#id_kpi").val(),
					id_step_group: $('#id_step_group').val()
				},
				function GotoContextView(value){
					if (value == "true"){
						DisplayAlertSuccess('Contexte de contrat sauvegardé', "contract_context_view.php");
					}
					else if (value == "false"){
						DisplayAlertWarning('Veuillez saisir les champs obligatoire');
					}
					else if (value == "no_right"){
                                                DisplayAlertWarning('Un contexte de contrat avec des attributs identiques existe déjà');
                                        }
					else if (value == "no_right_2"){
                                                DisplayAlertWarning('Un contexte de contrat lie le contrat actuellement sélectionné avec une autre période de couverture que celle sélectionnée');
                                        }
					else {
						DisplayAlertWarning('Impossible de se connecter à la base de données');
					}
				}
			);
		}
	});

});

function ChangeValue(value){
	$array_name_id = value.split("_-_");
	$object_name = $array_name_id[0]
	$name = $array_name_id[1];
	$id = $array_name_id[2];

	if($object_name == "contract"){
		$('#name_contract').html($name+'  <span class="caret"></span></button>');
		$('#id_contract').val($id);
		$("div.input-contract").append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
	}
	else if($object_name == "kpi"){
		$('#display_step_group').css('display', 'inline');
		$('#name_kpi').html($name+'  <span class="caret"></span></button>');
		$('#id_kpi').val($id);
		$("div.input-kpi").append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');

		$.get(
           		'./php/get_info_step_group.php',
                	{
                		id_kpi: $id
			},
                	function ReturnName(values){
                        	$.each(values, function(v, k){
                                	$step_group_name = k['NAME'];
                                	$id = k['ID_STEP_GROUP'];
                                	$('#ul_step_group').append('<li><a class="dropdown-item" id="seuil_-_'+$step_group_name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $step_group_name + '</a></li>');
                                });
                	},
                	'json'
        	);

	}
	else if($object_name == "time"){
		$('#name_time_period').html($name+'  <span class="caret"></span></button>');
		$('#id_time_period').val($id);
		$("div.input-time").append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
	}

	else if($object_name == "seuil"){
                $('#name_step_group').html($name+'  <span class="caret"></span></button>');
                $('#id_step_group').val($id);
		console.log($('#id_step_group').val());
        }
}

</script>

</body>

</html>



