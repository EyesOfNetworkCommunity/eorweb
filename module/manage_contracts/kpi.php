<?php
include("../../header.php");
include("../../side.php");
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-md-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.kpi_title"); ?></h1>
		</div>
	</div>

	<div class="row" id="global_form"></div>

		<form>

			<div class="row form-group">
				<div class="col-md-6 has-feedback div-context">
					<label for="name_contract_context"><?php echo getLabel("label.contract_context"); ?></label>
					<div class="input-context">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_contract_context"><?php echo getLabel("label.manage_contracts.contract_context_view_title"); ?>
						<span class="caret"></span></button>
						<ul class="dropdown-menu btn-block" id="ul_context"></ul>
					</div>
				</div>
			</div>

			<div class="row form-group">
				<div class="col-md-6 has-feedback div-name">
					<label for="name"><?php echo getLabel("label.contracts_menu.indicator_create_name"); ?></label>
					<div class="input-name">
						<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
				</div>
			</div>

			<div class="row form-group">
				<div class="col-md-6 has-feedback div-comput">
					<label for="name_unit_comput"><?php echo getLabel("label.contracts_menu.indicator_create_compute"); ?></label>
					<div class="input-comput">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_unit_comput"><?php echo getLabel("label.contracts_menu.indicator_create_compute_value_default"); ?>
						<span class="caret"></span></button>
						<ul class="dropdown-menu btn-block" id="ul_comput"></ul>
					</div>
				</div>
			</div>

			<div class="row form-group">
				<div class="col-md-6 has-feedback div-presentation">
					<label for="name_unit_presentation"><?php echo getLabel("label.contracts_menu.indicator_create_presentation"); ?> </label>
					<div class="input-presentation">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_unit_presentation"><?php echo getLabel("label.contracts_menu.indicator_create_presentation"); ?>
						<span class="caret"></span></button>
						<ul class="dropdown-menu btn-block" id="ul_presentation"></ul>
					</div>
				</div>
			</div>

			<div class="row form-group" style="display:none">
				<div class="col-md-6 has-feedback div-presentation">
					<div class="col-md-7">
						<input type="text" class="form-control" id="id_unit_comput">
					</div>
				</div>
			</div>

			<div class="row form-group" style="display:none">
				<div class="col-md-6 has-feedback div-presentation">
					<div class="col-md-7">
						<input type="text" class="form-control" id="id_unit_presentation">
					</div>
				</div>
			</div>

			<button class="form-group btn btn-primary" type="submit" id="submit"><?php echo getLabel("action.submit"); ?>
			</button>
		</form>
	</div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="./js/bootstrap-datepicker.min.js"></script>
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
				$('#global_form').css("display", "none");
				DisplayAlertMissing("Vous devez créer une fiche d'unité de mesure avant de pouvoir créer un Indicateur");
			}

			else{
				$('#global_form').css("display", "block");
				$.each(values, function(v, k){
					$name = k['NAME'];
					$id = k['ID_UNIT'];
					$('#ul_comput').append('<li><a class="dropdown-item" id="comput_'+$name+'_'+$id+'" href="javascript:void(0);" onclick="ChangeValue(id);">' + $name + '</a></li>');
					$('#ul_presentation').append('<li><a class="dropdown-item" id="presentation_'+$name+'_'+$id+'" href="javascript:void(0);" onclick="ChangeValue(id);">' + $name + '</a></li>');
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
	$array_name_id = value.split("_");
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

<?php
include("../../footer.php");
?>
