<?php include('./php/common.inc.php'); ?>

<div class="container container-title bg-primary">
	<h3>Créer une unité<h3>
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
			<label style="font-weight:normal;" for="name" class="col-md-4 control-label">Nom : </label>
			<div class="col-md-7 input-name">
				<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
			</div>
			<div class="col-md-1 full-left">
				<span class="glyphicon glyphicon-asterisk"></span>
			</div>
		</div>	
	</div>

	<div class="row">
		<div class="form-group has-feedback div-short-name">
			<label style="font-weight:normal;" for="short_name" class="col-md-4 control-label">Nom court : </label>
			<div class="col-md-7 input-short-name">
				<input type="text" class="form-control" id="short_name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
			</div>
			<div class="col-md-1 full-left">
				<span class="glyphicon glyphicon-asterisk"></span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group has-feedback div-optional">
			<label style="font-weight:normal;" for="optional" class="col-md-4 control-label">Option : </label>
			<div class="col-md-7 input-optional">
				<input type="text" class="form-control" id="optional" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group has-feedback div-comment">
			<label style="font-weight:normal;" for="comment" class="col-md-4 control-label">Commentaire : </label>
			<div class="col-md-7 input-comment">
				<input type="text" class="form-control" id="coment" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
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
				table_name: 'unit',
				id_number: UrlParam('id_number')
			},
			function return_values(values){
				$('#name').val(values['NAME']);
				$('#short_name').val(values['SHORT_NAME']);
				$('#optional').val(values['OPTIONAL']);
				$('#comment').val(values['COMMENT']);
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
					table_name: 'unit',
					name: $('#name').val(),
					id_number: UrlParam('id_number'),
					short_name: $("#short_name").val(),
					optional: $("#optional").val(),
					comment: $("#comment").val()
				},
				function ShowMsg(value){
					if (value == "true"){
						DisplayAlertSuccess('Unité sauvegardé', "unit_view.php");
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
					table_name: 'unit',
					name: $("#name").val(),
					short_name: $("#short_name").val(),
					optional: $("#optional").val(),
					comment: $("#comment").val()
				},
				function GotoContextView(value){
					if (value == "true"){
						DisplayAlertSuccess('Unité sauvegardé', "unit_view.php");
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


