<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<h3>Création d'un contrat</h3>
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
			<label style="font-weight:normal;" for="name" class="col-md-4 control-label">Nom du contrat : </label>
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
		<div class="form-group has-feedback div-sdm-intern">
			<label style="font-weight:normal;" for="contract_sdm_intern" class="col-md-4 control-label">SDM interne : </label>
			<div class="col-md-7 input-sdm-intern">
				<input type="text" class="form-control" id="contract_sdm_intern" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group has-feedback div-sdm-extern">
			<label style="font-weight:normal;" for="contract_sdm-extern" class="col-md-4 control-label">SDM externe : </label>
			<div class="col-md-7 input-sdm-extern">
				<input type="text" class="form-control" id="contract_sdm_extern" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group has-feedback div-company">
			<label style="font-weight:normal;" for="name_company" class="col-md-4 control-label">Entreprise : </label>
			<div class="col-md-7 input-company">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_company">Selectionner une Entreprise
				<span class="caret"></span></button>
				<ul class="dropdown-menu btn-block" id="ul_company">
				</ul>
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group has-feedback div-extern-id">
			<label style="font-weight:normal;" for="extern_contract_id" class="col-md-4 control-label">Référence externe du contrat : </label>
			<div class="col-md-7 input-extern-id">
				<input type="text" class="form-control" id="extern_contract_id" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group div-validity-date">
			<label style="font-weight:normal;" for="validity_date" class="col-md-4 control-label">Date d'expiration du contrat : </label>
			<div class="col-md-8 input-validity-date input-group date" id="datepicker1">
				<input type="text" class="form-control" readonly id="validity_date">
				<span class="input-group-addon">
                	<span class="glyphicon glyphicon-calendar"></span>
					<span class="glyphicon glyphicon-asterisk"></span>
            	</span>
			</div>
		</div>
	</div>

	<div class="col-md-7">
		<input type="text" class="form-control" id="id_company" style="display:none">
	</div>

	<button class="form-group btn btn-primary pull-right" type="submit" id="submit">Envoyer
		<span class="glyphicon glyphicon-ok" style="color:#4f4;"></span>
	</button>

</form>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="./js/bootstrap-datepicker.min.js"></script>
<script src="./js/library.js"></script>

<script>
$(document).ready(function() {
	if (UrlParam('id_number') != false){
		$.get(
			'./php/view_entry.php',
			{
				table_name: 'contract',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
        			$('#desc').val(values['ALIAS']);
				$('#contract_sdm_intern').val(values['CONTRACT_SDM_INTERN']);
				$('#contract_sdm_extern').val(values['CONTRACT_SDM_EXTERN']);
				$('#id_company').val(values['ID_COMPANY']);
				$('#extern_contract_id').val(values['EXTERN_CONTRACT_ID']);
				$('#validity_date').val(values['VALIDITY_DATE']);

				$.get(
					'./php/select_name_by_id.php',
				  {
					table_name: 'company',
					id_number: values['ID_COMPANY']
				  },
				  function return_name(name){
					$('#name_company').html(name['NAME']+'  <span class="caret"></span></button>');
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
			table_name:'company',
			id: 'ID_COMPANY'
		},
		function ReturnName(values){
			if(values.length == 0){
				DisplayAlertMissing("Vous devez créer une fiche Entreprise avant de pouvoir créer un contrat");
			}

			else{
				$('#global_form').css("display", "inline");
				$.each(values, function(v, k){
					$name = k['NAME'];
					$id = k['ID_COMPANY'];
					$('#ul_company').append('<li><a class="dropdown-item" id="'+$name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $name + '</a></li>');
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
					table_name: 'contract',
					id_number: UrlParam('id_number'),
					name: $('#name').val(),
					alias: $('#desc').val(),
					contract_sdm_intern: $('#contract_sdm_intern').val(),
					contract_sdm_extern: $('#contract_sdm_extern').val(),
					id_company: $('#id_company').val(),
					extern_contract_id: $('#extern_contract_id').val(),
					validity_date: $('#validity_date').val()
				},
				function ShowMsg(value){
					if (value == "true"){
						DisplayAlertSuccess('Contrat sauvegardé', "contract_view.php");
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
					table_name: 'contract',
					name: $("#name").val(),
					alias: $("#desc").val(),
					contract_sdm_intern: $("#contract_sdm_intern").val(),
					contract_sdm_extern: $("#contract_sdm_extern").val(),
					id_company: $("#id_company").val(),
					extern_contract_id: $("#extern_contract_id").val(),
					validity_date: $("#validity_date").val()
				},
				function GotoContextView(value){
					if (value == "true"){
						DisplayAlertSuccess('Contrat sauvegardé', "contract_view.php");
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

	$(function () {
		$('#datepicker1').datepicker({
			format: 'yyyy/mm/dd'
		});
	});

});

function ChangeValue(value){
	$array_name_id = value.split("_-_");
	$name_company = $array_name_id[0];
	$id_company = $array_name_id[1];
	$('#name_company').html($name_company+'  <span class="caret"></span></button>');
	$('#id_company').val($id_company);
}

</script>

</body>

</html>


