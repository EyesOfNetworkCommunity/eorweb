<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<h3>Création d'un Indicateur de performances<h3>
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
			<label style="font-weight:normal;" for="name" class="col-md-4 control-label">Nom de l'Indicateur : </label>
			<div class="col-md-7 input-name">
				<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>	
	</div>

	<div class="row">
		<div class="form-group has-feedback div-comput">
			<label style="font-weight:normal;" for="name_unit_comput" class="col-md-4 control-label">Unité de calcul : </label>
			<div class="col-md-7 input-comput">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_unit_comput">Sélectionner une Unité de calcul
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_comput">
				</ul>
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group has-feedback div-presentation">
			<label style="font-weight:normal;" for="name_unit_presentation" class="col-md-4 control-label">Unité de présentation : </label>
			<div class="col-md-7 input-presentation">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_unit_presentation">Sélectionner une Unité de présentation
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_presentation">
				</ul>
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>
	</div>

<div class="col-md-7" style="display:none">
<input type="text" class="form-control" id="id_unit_comput">
	</div>

<div class="col-md-7" style="display:none">
<input type="text" class="form-control" id="id_unit_presentation">
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
				table_name: 'kpi',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
				$('#id_unit_comput').val(values['ID_UNIT_COMPUT']);
				$('#id_unit_presentation').val(values['ID_UNIT_PRESENTATION']);
                                                                        
				$.get(
				  './php/select_name_by_id.php',
				  {
					table_name: 'unit',
					id_number: values['ID_UNIT_COMPUT']
				  },
				  function return_name(name){
					$('#name_unit_comput').html(name['NAME']+'  <span class="caret"></span></button>');
				  },
				  'json'
				);
		
				$.get(
				  './php/select_name_by_id.php',
				  {
					table_name: 'unit',
					id_number: values['ID_UNIT_PRESENTATION']
				  },
				  function return_name(name){
					$('#name_unit_presentation').html(name['NAME']+'  <span class="caret"></span></button>');
				  },
				  'json'
				);
			},
			'json'
		);
	}

	$.get(
		'./php/get_name_id.php',
		{
			table_name:'unit',
			id: 'ID_UNIT'
		},
		function ReturnName(values){
			if(values.length == 0){
				DisplayAlertMissing("Vous devez créer une fiche d'unité de mesure avant de pouvoir créer un Indicateur");
			}

			else{
				$('#global_form').css("display", "inline");
				$.each(values, function(v, k){
					$name = k['NAME'];
					$id = k['ID_UNIT'];
					$('#ul_comput').append('<li><a class="dropdown-item" id="comput_-_'+$name+'_-_'+$id+'" href="javascript:void(0);" onclick="ChangeValue(id);">' + $name + '</a></li>');
					$('#ul_presentation').append('<li><a class="dropdown-item" id="presentation_-_'+$name+'_-_'+$id+'" href="javascript:void(0);" onclick="ChangeValue(id);">' + $name + '</a></li>');
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
					table_name: 'kpi',
					name: $('#name').val(),
					id_number: UrlParam('id_number'),
					id_unit_comput: $('#id_unit_comput').val(),
					id_unit_presentation: $('#id_unit_presentation').val()
				},
				function ShowMsg(value){
					if (value == "true"){
						DisplayAlertSuccess('Indicateur sauvegardé', "kpi_view.php");
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
					table_name: 'kpi',
					name: $("#name").val(),
					id_unit_comput: $("#id_unit_comput").val(),
					id_unit_presentation: $("#id_unit_presentation").val()
				},
				function GotoContextView(value){
					if (value == "true"){
						DisplayAlertSuccess('Indicateur sauvegardé', "kpi_view.php");
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
	});

});

function ChangeValue(value){
	$array_name_id = value.split("_-_");
	$object_name = $array_name_id[0]
	$name = $array_name_id[1];
	$id = $array_name_id[2];

	if($object_name == "comput"){
		$('#name_unit_comput').html($name+'  <span class="caret"></span></button>');
		$('#id_unit_comput').val($id);
	}
	else if($object_name == "presentation"){
		$('#name_unit_presentation').html($name+'  <span class="caret"></span></button>');
		$('#id_unit_presentation').val($id);
	}
}

</script>

</body>

</html>


