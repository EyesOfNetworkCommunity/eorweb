<?php 
include("../../header.php");
include("../../side.php");
?>


<div id="page-wrapper">
	<div class="row">
		<div class="col-md-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_contracts.contract_context_title"); ?></h1>
		</div>
	</div>

	<div class="row" id="global_form"></div>

	<form>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-name">
					<label for="name"><?php echo getLabel("label.manage_contracts.contract_context_name_descr"); ?></label>
					<div class="input-name">
						<input type="text" class="form-control" id="name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
				</div>
			</div>	
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-desc">
					<label for="desc"><?php echo getLabel("label.manage_contracts.contract_context_descr"); ?></label>
					<div class="input-desc">
						<input type="text" class="form-control" id="desc" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')">
					</div>
				</div>
			</div>	
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-contract">
					<label for="name_contract"><?php echo getLabel("label.manage_contracts.contract_context_contract"); ?></label>
					<div class="input-contract">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_contract"><?php echo getLabel("label.manage_contracts.contract_view_title"); ?> <span class="caret"></span></button>
						<ul class="dropdown-menu btn-block" id="ul_contract">
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-time">
					<label for="name_time_period"><?php echo getLabel("label.time_period"); ?></label>
					<div class="input-time">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_time_period"><?php echo getLabel("label.manage_contracts.time_period_view_title"); ?> <span class="caret"></span></button>
						<ul class="dropdown-menu btn-block" id="ul_time">
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="has-feedback div-kpi">
					<label for="name_kpi"><?php echo getLabel("label.indicator"); ?></label>
					<div class="input-kpi">
						<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_kpi"><?php echo getLabel("label.contracts_menu.context_contract_create_indicator_value_default"); ?> <span class="caret"></span></button>
						<ul class="dropdown-menu btn-block" id="ul_kpi">
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row form-group" id="display_step_group" style="display:none">
			<div class="col-md-6">
	            <div class="has-feedback">
                    <label for="name_step_group"><?php echo getLabel("label.manage_contracts.indicator_associate"); ?></label>
                    <div>
                        <button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="name_step_group"><?php echo getLabel("label.manage_contracts.sla_selection"); ?>
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu btn-block" id="ul_step_group"></ul>
                    </div>
	            </div>
	        </div>
	    </div>
		
		<div style="display:none">
			<input type="text" class="form-control" id="id_contract">
			<input type="text" class="form-control" id="id_time_period">
			<input type="text" class="form-control" id="id_kpi">
		    <input type="text" class="form-control" id="id_step_group">
	    </div>
		<div class="row">
			<div class="col-md-4">
				<button class="form-group btn btn-primary" type="submit" id="submit"><?php echo getLabel("action.submit"); ?></button>
				<button class="btn btn-default" type="button" name="back" value="back" onclick="location.href='contract_context_view.php'"><?php echo getLabel("action.cancel") ?>
				</button>
			</div>
		</div>
	</form>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="./js/jquery.appear.js"></script>
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
			// if(values.length == 0 || $counter > 0){
			// 	DisplayAlertMissing("Vous devez créer au moins un Indicateur, une période de temps et un contrat</br>avant de pouvoir créer un contexte de contrat");
			// }

			// else{
				$('#global_form').css("display", "block");
				$.each(values, function(v, k){
					$time_name = k['NAME'];
					$id = k['ID_TIME_PERIOD'];
					$('#ul_time').append('<li><a class="dropdown-item" id="time_-_'+$time_name+'_-_'+$id+'"href="javascript:void(0);" onclick="ChangeValue(id);">' + $time_name + '</a></li>');
				});
			// }
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
					id_number: UrlParam('id_number'),
					id_contract: $('#id_contract').val(),
					id_time_period: $('#id_time_period').val(),
					id_kpi: $('#id_kpi').val(),
					id_step_group: $('#id_step_group').val()
				},
				function ShowMsg(value){
					if (value == "true"){
						document.getElementById('global_form').innerHTML = "<?php message(12, getLabel("message.manage_contracts.contract_context_saved"), "ok"); ?>";
						setTimeout(function(){
							$(location).attr('href', "contract_context_view.php");
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
						document.getElementById('global_form').innerHTML = "<?php message(1, "", "critical"); ?>";
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
						document.getElementById('global_form').innerHTML = "<?php message(12, getLabel("message.manage_contracts.contract_context_saved"), "ok"); ?>";
						setTimeout(function(){
							$(location).attr('href', "contract_context_view.php");
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
						if (value == "no_right"){
                           document.getElementById('global_form').innerHTML = "<?php message(12, getLabel("message.error.manage_contracts_contract_context_alredy_exist"), "critical"); ?>";
							setTimeout(function(){
								document.getElementById('global_form').innerHTML = "";
								},
								6000
							);
						} else {
                        	if (value == "no_right_2"){
                                document.getElementById('global_form').innerHTML = "<?php message(12, getLabel("message.error.manage_contracts_time_period_reserved"), "critical"); ?>";
								setTimeout(function(){
									document.getElementById('global_form').innerHTML = "";
									},
									6000
								);
                            } else {
								document.getElementById('global_form').innerHTML = "<?php message(1, "", "critical"); ?>";
								setTimeout(function(){
									document.getElementById('global_form').innerHTML = "";
									},
									5000
								);
							}
						}
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
	}
	else if($object_name == "kpi"){
		$('#display_step_group').css('display', 'block');
		$('#name_kpi').html($name+'  <span class="caret"></span></button>');
		$('#id_kpi').val($id);

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
	}

	else if($object_name == "seuil"){
                $('#name_step_group').html($name+'  <span class="caret"></span></button>');
                $('#id_step_group').val($id);
		console.log($('#id_step_group').val());
        }
}

</script>

<?php
include("../../footer.php");
?>

