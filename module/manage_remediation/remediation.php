<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Bastien PUJOS
# VERSION : 2.0
# APPLICATION : eorweb for eyesofreport project
#
# LICENCE :
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
#########################################
*/

include("../../header.php");
include("../../side.php");

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.module_eorweb.remediation_new"); ?></h1>
		</div>
	</div>
	
	<?php 
	
	if(!empty($_POST['host'])){
		$remediation_action="";
		foreach($_POST['host'] as $selected){
			/*$sql=sqlrequest($database_notifier,"SELECT id FROM remediation_action WHERE remediationID='".$selected."'");
			if(mysqli_result($sql,0)!=FALSE) {
				$select=mysqli_result($sql,0);
				$rule_method_ids.=$select.",";
			}*/
		}
		$remediation_action=rtrim($remediation_action,",");
	}
	
	// General data
	$user_id = $_COOKIE['user_id'];
	if(isset($_POST["add"])){
		$remediation_id = NULL;
		$date_demand = date("d/m/Y - G:i");
	}elseif(isset($_POST["update"])){
		// Get the remediation id and date of insertion
		
	}
	
	// Get post data
	//$remediation_id=retrieve_form_data("id",null);
	$remediation_name=retrieve_form_data("name",null);  

	if(isset($_POST["add"]) || isset($_POST["update"])) {
		if(!$remediation_name || $remediation_name==""){
			message(7," : Your remediation need a name",'warning');
		}
		elseif(empty($_POST['rule_host']) || $_POST['rule_host']==null){
			message(7," : Your remediation need at least 1 remediation action",'warning');
		}elseif(isset($_POST["add"])){
			
			// insert values for add
			$sql_add = "INSERT INTO remediation VALUES('','".$remediation_name."','".$user_id."','".$date_demand."')";
			$remediation_id = sqlrequest("eorweb",$sql_add,true);
			/*$methodze=explode(",",$rule_method_ids);
			foreach($methodze as $selected){
				sqlrequest($database_notifier,"INSERT INTO remediation_action VALUES('".$remediation_id."', '".$selected."')",true);
			}*/
			
			message(6," : Remediation have been created",'ok');
			
		}elseif(isset($_POST["update"])){
			
			message(6," : Remediation have been updated",'ok');
		}
	}
	
	?> 
				
	<form id="form_remediation" action='./remediation.php' method='POST' name='form_remediation'>
		<input type='hidden' name='user_id' value='<?php echo $user_id?>'>
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.module_eorweb.remediation_name"); ?></label>
			<div class="col-md-9">
				<input class="form-control" type='text' name='name' value="<?php echo $remediation_name; ?>" autofocus>
			</div>
		</div>
		
		<div class="row form-group">
			<label class="col-md-3"><?php echo getLabel("label.module_eorweb.remediation_action_add"); ?></label>
			<div class="col-md-9">
				<div class="form-group input-group">
					<input class="form-control" id="rule_host1" type="text" name="rule_host">
					<span class="input-group-btn">
						<input class="btn btn-success" id="rule_host_button" type="button" value="<?php echo getLabel("action.add");?>">
						<input class="btn btn-danger" id="rule_host_button_del" type="button" value="<?php echo getLabel("action.clear");?>">
					</span>
				</div>
				<select class="form-control" id="host" name="host[]" size=4 multiple="multiple">
				<?php 
					if(isset($remediation_action)){
						$division=explode(",", $remediation_action);
						foreach($division as $selected){
							echo "<option selected='selected' value='".$selected."'>".$selected."</option> ";
						}
					}
				?>
				</select>
			</div>
		</div>
		
		<div class="form-group">
			<?php
				if (isset($remediation_id) && $remediation_id != null) {
					echo "<input class='btn btn-primary' type='submit' name='update' value=".getLabel('action.update').">";
				}
				else {
					echo "<input class='btn btn-primary' type='submit' name='add' value=".getLabel('action.add').">";
				}
				echo "<button class='btn btn-default' style='margin-left: 10px;' type='button' name='back' value='back' onclick='location.href=\"index.php\"'>".getLabel("action.cancel")."</button>";
			?>
		</div>
	</form>

</div>

<?php include("../../footer.php"); ?>


<script type="text/javascript">

   // Add
	$('#rule_host_button').on('click',function(){
		var o = new Option($("#rule_host1").val(),$("#rule_host1").val(),true,true);
		$("#host").append(o);
	});
	
	// Delete
	$('#rule_host_button_del').on('click',function(){
		$("#host").find('option:selected').remove();
		$("#host").find("option").attr('selected','selected');
	});
	
	/*$('#ajout').on('submit', 'form', function(e) {
		var self = this;
		e.preventDefault();
		$("#timeperiods option").prop("selected",true);
		self.submit();
	});*/

</script>
