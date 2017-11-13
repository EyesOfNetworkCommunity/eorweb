<div class="container pad-top" id="global_container" style="display:none">
 <div class="well">
	<p class="col-md-6"><b>Nombre d'entrées sauvegardées :</b></p>
	<p class="col-md-6"><b>Dernières entrées sauvegardées :</b></p>

	<div class="container col-md-6">
		<div class="wraper col-md-8 pad-top">
			<div class="skillst4">
				<div class="skillbar">
					<div class="title">Contextes de contrat</div>
					<div class="count-bar color-1">
						<div class="count"></div>
					</div>
				</div>
				<div class="skillbar">
					<div class="title">Contrats</div>
					<div class="count-bar color-2">
						<div class="count"></div>
					</div>
				</div>
				<div class="skillbar">
					<div class="title">Entreprises</div>
					<div class="count-bar color-3">
						<div class="count"></div>
					</div>
				</div>
				<div class="skillbar">
					<div class="title">Périodes de temps</div>
					<div class="count-bar color-4">
						<div class="count"></div>
					</div>
				</div>
				<div class="skillbar">
					<div class="title">Indicateurs</div>
					<div class="count-bar color-5">
						<div class="count"></div>
					</div>
				</div>
				<div class="skillbar">
					<div class="title">Seuils</div>
					<div class="count-bar color-5">
						<div class="count"></div>
					</div>
				</div>
				<div class="skillbar">
					<div class="title">Applications</div>
					<div class="count-bar color-5">
						<div class="count"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container col-md-6">
		<table class="table">
			<thead>
				<tr>
				    <th>Status</th>
				    <th>Entrée</th>
				    <th>Nom de l'entrée</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody id="body_table">
			</tbody>
	</table>
 </div>
</div>

<script src="../js/jquery.appear.js" type="text/javascript"></script>

<script>
$(document).ready(function(){
	var counter = 0;

	$.get(
		'../php/return_number_entry_all.php',
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
				$(this).appear(function(){
					var longueur = numbers[parseInt(counter)] *one_unit;
					longueur = longueur + '%';
					$(this).attr('data-percent', numbers[parseInt(counter)]);
					counter++;
					$(this).find('.count-bar').animate({
						width:longueur
					},3000);
					var percent = $(this).attr('data-percent');
					$(this).find('.count').html('<span>' + percent + '</span>');
				});
			});
		},
		'json'
	);

	$.get(
		'../php/display_entry.php',
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
				}
				else{
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
					$last_entry_table_name = 'Période de temps';
				}
	
				$('#body_table').append('<tr><td class="' + $class_to_add + '">'+ $last_entry_status + '</td><td>' + $last_entry_table_name + '</td><td>' + $last_entry_name + '</td><td>' + $last_entry_date + '</td></tr>');
			});
		},
		'json'
	);
});
</script>
