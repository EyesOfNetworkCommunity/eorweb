<?php 
include("../../header.php");
include("../../side.php");
?>
<link href="./css/design.css" rel="stylesheet">

<div id="page-wrapper">
	<div class="row">
		<h1 class="page-header"><?php echo getLabel("label.manage_contracts.title"); ?></h1>
	</div>
	<div id="welcome" class="row" style="display: none; text-align: center">
		<h3><?php echo getLabel("label.manage_contract.welcome"); ?></h3>
		<p><?php echo getLabel("label.manage_contract.welcome2"); ?></p>
	</div>
	<div id="global_container" class="row" style="display: none">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-12">
							<span><?php echo getLabel("label.manage_contract.record_number"); ?></span>
						</div>
					</div>
				</div>
				<div class="panel panel-body">
					<div class="col-md-12">
						<div class="skillst4">
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.contract_context"); ?></div>
								<div class="count-bar color-1">
									<div class="count"></div>
								</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.contract"); ?></div>
								<div class="count-bar color-2">
									<div class="count"></div>
								</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.company"); ?></div>
								<div class="count-bar color-3">
									<div class="count"></div>
								</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.time_period"); ?></div>
									<div class="count-bar color-4">
								<div class="count"></div>
							</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.indicator"); ?></div>
									<div class="count-bar color-5">
								<div class="count"></div>
							</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.sla"); ?></div>
									<div class="count-bar color-5">
								<div class="count"></div>
							</div>
							</div>
							<div class="skillbar">
								<div class="title"><?php echo getLabel("label.application"); ?></div>
								<div class="count-bar color-5">
									<div class="count"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-12">
							<span><?php echo getLabel("label.manage_contract.record_entries"); ?></span>
						</div>
					</div>
				</div>
				<div class="panel panel-body">					
					<div class="col-md-12">
						<table class="table">
							<thead>
								<tr>
									<th><?php echo getLabel("label.status"); ?></th>
									<th><?php echo getLabel("label.input"); ?></th>
									<th><?php echo getLabel("label.input_name"); ?></th>
									<th><?php echo getLabel("label.date"); ?></th>
								</tr>
							</thead>
							<tbody id="body_table">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="./js/jquery.appear.js"></script>

<script>
$(document).ready(function(){
	var counter = 0;
	$.get(
		'./php/check_if_entry.php',
		function returnNumber(number){
			console.log[number];
			if(parseInt(number[0]) == 0){
				$('#welcome').css("display", "inline");
			}
			else{
				$('#global_container').css("display", "inline");
				$.get(
					'./php/return_number_entry_all.php',
					function returnNumbers(numbers){
						var high_number = 0;
						for(var i=0; i < numbers.length; i++){
							var number = parseInt(numbers[i]);
							if(number > high_number){
								high_number = number;
							}
						}
						var one_unit = 100 / high_number;
						$('.skillbar').each(function(){
							//$(this).appear(function(){
								var longueur = numbers[parseInt(counter)] *one_unit;
								longueur = longueur + '%';
								$(this).attr('data-percent', numbers[parseInt(counter)]);
								counter++;
								$(this).find('.count-bar').animate({
									width:longueur
								},3000);
								var percent = $(this).attr('data-percent');
								$(this).find('.count').html('<span class="text-warning">' + percent + '</span>');
							//});
						});
					},
					'json'
				);

				$.get(
					'./php/display_entry.php',
					{
						table_name: 'last_entry'
					},
					function returnEntries(entries){
						$.each(entries, function(v, k){
							$last_entry_status = k['STATUS'];
							$last_entry_table_name = k['TABLE_NAME'];
							$last_entry_name = k['NAME'];
							$last_entry_date = k['DATE_ENTRY'];

							if($last_entry_status == 'New'){
								$class_to_add = "text-primary container-title";
							} else {
								$class_to_add = "text-warning container-title";
							}
							if($last_entry_table_name == 'contract_context'){
								$last_entry_table_name = 'Contexte de contrat';
							}
							else if($last_entry_table_name == 'contract'){
								$last_entry_table_name = 'Contrat';
							}
							else if($last_entry_table_name == 'company'){
								$last_entry_table_name = 'Entreprise';
							}
							else if($last_entry_table_name == 'contract_context_application'){
								$last_entry_table_name = 'Application';
							}
							else if($last_entry_table_name == 'kpi'){
								$last_entry_table_name = 'Indicateur';
							}
							else if($last_entry_table_name == 'step_group'){
								$last_entry_table_name = 'Groupe de seuils';
							}
							else if($last_entry_table_name == 'time_period'){
								$last_entry_table_name = 'Plage de service';
							}
				
							$('#body_table').append('<tr><td class="text-primary container-title">'+ $last_entry_status + '</td><td>' + $last_entry_table_name + '</td><td>' + $last_entry_name + '</td><td>' + $last_entry_date + '</td></tr>');
						});
					},
					'json'
				);
			}
		},
		'json'
	);
});
</script>

<?php
include("../../footer.php");
?>