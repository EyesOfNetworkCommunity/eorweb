<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<h3>Création d'une Entreprise</h3>
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
			<label style="font-weight:normal;" for="name" class="col-md-4 control-label">Nom de l'Entreprise: </label>
			<div class="col-md-7 input-name">
				<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
			</div>
			<div class="control-label form-group col-md-1">
				<span class="glyphicon glyphicon-asterisk" style="font-size:13px;color:#707070;"></span>
			</div>
		</div>	
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
				table_name: 'company',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
			},
			'json'
		);
	}

	$('#submit').click(function(event){
		event.preventDefault();

		if (UrlParam('id_number') != false){
			$.get(
				'./php/update_entry.php',
				{
					table_name: 'company',
					name: $('#name').val(),
					id_number: UrlParam('id_number')
				},
				function GotoView(value){
					if (value == "true"){
						DisplayAlertSuccess('Entreprise sauvegardée', "company_view.php");
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
					table_name: 'company',
					name: $("#name").val()
				},
				function GotoView(value){
					if (value == "true"){
						DisplayAlertSuccess('Entreprise sauvegardée', "company_view.php");
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

</script>

</body>

</html>


