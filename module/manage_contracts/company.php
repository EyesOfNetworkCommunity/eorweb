<?php 
include("../../header.php");
include("../../side.php");
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-md-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.company_title"); ?></h1>
		</div>
	</div>
	<div class="container">
		<form class="form-horizontal col-md-7 marge" id="global_form">
			<div class="row pad-top">
				<div class="form-group has-feedback div-name">
					<label style="font-weight:normal;" for="name" class="col-md-4 control-label"><?php echo getLabel("label.manage_contracts.company_name"); ?></label>
					<div class="col-md-7 input-name">
						<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
					<div class="form-group col-md-1">
						<span class="glyphicon glyphicon-asterisk" style="font-size:15px;"></span>
					</div>
				</div>	
			</div>
			<button class="form-group btn btn-primary pull-right" type="submit" id="submit">Envoyer
				<span class="glyphicon glyphicon-ok" style="color:#4f4;"></span>
			</button>
		</form>
	</div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
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


<?php
include("../../footer.php");
?>