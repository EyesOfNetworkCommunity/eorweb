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

	<div class="row" id="global_form"></div>
	<form>
		<div class="row form-group">
			<div class="col-md-6">
				<label><?php echo getLabel("label.manage_contracts.company_name"); ?></label>
				<div class="input-name">
					<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
				</div>
			</div>
		</div>
		<button class="form-group btn btn-primary" type="submit" id="submit"><?php echo getLabel("label.manage_contracts.company_add"); ?></button>
	</form>
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
						document.getElementById('global_form').innerHTML = "<?php message(12, getLabel("label.manage_contracts.company_saved"), "ok"); ?>";
						setTimeout(function(){
							$(location).attr('href', "company_view.php");
							},
							2000
						);
					}
					else if (value == "false"){
						document.getElementById('global_form').innerHTML = "<?php message(12, getLabel("message.error.required_fields"), "critical"); ?>";
						setTimeout(function(){
							document.getElementById('global_form').innerHTML = "";
							},
							5000
						);
					}
					else {
						document.getElementById('global_form').innerHTML = "<?php message(1, " : ".getLabel("message.error.database"), "critical"); ?>";
						setTimeout(function(){
							document.getElementById('global_form').innerHTML = "";
							},
							5000
						);
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
						document.getElementById('global_form').innerHTML = "<?php message(12, getLabel("label.manage_contracts.company_saved"), "ok"); ?>";
						setTimeout(function(){
							$(location).attr("href", "company_view.php");
							},
							2000
						);
					}
					else if (value == "false"){
						document.getElementById('global_form').innerHTML = "<?php message(12, getLabel("message.error.required_fields"), "critical"); ?>";
						setTimeout(function(){
							document.getElementById('global_form').innerHTML = "";
							},
							5000
						);
					} else {
						document.getElementById('global_form').innerHTML = "<?php message(1, " : ".getLabel("message.error.database"), "critical"); ?>";
						setTimeout(function(){
							document.getElementById('global_form').innerHTML = "";
							},
							5000
						);
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