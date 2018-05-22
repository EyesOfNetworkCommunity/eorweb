<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Jean-Philippe LEVY
# VERSION : 5.2
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
			<h1 class="page-header"><?php echo getLabel("label.manage_bp.title"); ?></h1>
		</div>
	</div>
	
	<?php
	global $database_vanillabp;
	global $database_host;
	global $database_username;
	global $database_password;

	$t_bp_racine = array();
	$bp_showed = array();

function display_bp($bp,$bp_racine,$source) {
	global $database_host;
	global $database_username;
	global $database_password;

	$db = new mysqli($database_host, $database_username, $database_password, $source);

	if($db->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$rule_type = "";
	$desc_bp = "";
	$min_value = "";
	$priority = "";

	$sql_type = "SELECT type, description, min_value , priority FROM bp WHERE name='".$bp."'";

	if(!$result_type = $db->query($sql_type)){
		die('There was an error running the query [' . $db->error . ']');
	}

	while($row = $result_type->fetch_assoc()){   
		$rule_type = $row['type'];
		$desc_bp = $row['description'];
		$min_value = $row['min_value'];
		$priority = $row['priority'];
	} 

	if($min_value > 0) {
		$min_value = " ".$min_value;
	}

	$result_type->free();
	mysqli_close($db);

	if(!empty($desc_bp)) {
		$display_bp=$desc_bp;
	} else {
		$display_bp=$bp;
	} ?>	

	<li>
		<div class="tree-toggle">
			<div class="tree-line">
				<i class="glyphicon-link glyphicon"></i>
				<b class="bp_presentation"><?php echo $display_bp; ?></b>
				<b class="condition_presentation"><?php echo substr($source,0,-9); ?></b>
				<b class="condition_presentation"><?php echo "display".$priority; ?></b>
				<b class="condition_presentation"><?php echo $rule_type." ".$min_value; ?></b>
				<input name="bp-name" type="hidden" value="<?php echo $bp; ?>">
				<input name="bp-source" type="hidden" value="<?php echo $source; ?>">
				<input name="bp-priority" type="hidden" value="<?php echo $priority; ?>">
				<button name="delete-bp" type="button" class="btn_presentation pull-right btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i></button>
				<a role="button" class="btn_presentation pull-right btn btn-xs btn-info" href="add_application.php?bp_name=<?php echo $bp; ?>&source=<?php echo $source; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
				<a role="button" class="btn_presentation pull-right btn btn-xs btn-success" href="add_services.php?bp_name=<?php echo $bp; ?>&display=<?php echo $priority; ?>&source=<?php echo $source; ?>"><i class="glyphicon glyphicon-plus"></i></a>
			</div>
		</div>
	</li>
<?php }

function display_service($host_service,$bp_racine) {
	$service_name = explode(";", $host_service);
	$service_name = strtolower($service_name[1]);

	?>
	<li class="end">
		<div id="<?php echo $bp_racine."::".$host_service; ?>" class="tree-toggle">
			<i class="nav-header glyphicon glyphicon-eye-open"></i>
			<?php echo $host_service."\n"; ?>
		</div>
	</li>	
<?php }

function display_son($bp_racine,$source) {
	global $database_host;
	global $database_username;
	global $database_password;

	$db = new mysqli($database_host, $database_username, $database_password, $source);
	if($db->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	
	$t_bp_son = array();
	$t_service_son = array();

	$sql_bp = "SELECT bp_link FROM bp_links WHERE bp_name = '".$bp_racine."'";
	$sql_service = "SELECT host,service FROM bp_services WHERE bp_name = '".$bp_racine."'  ORDER BY host,service";

	if(!$result_bp = $db->query($sql_bp)){
		die('There was an error running the query [' . $db->error . ']');
	}

	while($row = $result_bp->fetch_assoc()){   
		array_push($t_bp_son,$row['bp_link']);
	} 

	$result_bp->free();

	if(!$result_service = $db->query($sql_service)){
		die('There was an error running the query [' . $db->error . ']');
	}

	while($row = $result_service->fetch_assoc()){   
		array_push($t_service_son,$row['host'].";".$row['service']);
	}
	
	$result_service->free();
	mysqli_close($db);

	if(sizeof($t_bp_son) > 0 ) {
		for ($i = 0; $i < sizeof($t_bp_son); $i++) { ?>
			<li class="son">
				<ul class="nav nav-list tree">
				<?php
					display_bp($t_bp_son[$i],$bp_racine,$source);
					display_son($t_bp_son[$i],$source);
				?>
				</ul>
			</li>
		<?php }
	}

	if(sizeof($t_service_son) > 0 ) {
		for ($i = 0; $i < sizeof($t_service_son); $i++) {
			display_service($t_service_son[$i],$bp_racine);
		}
	}
}

function display_global_son($bp_racine, $source) {
    global $database_nagios;
    global $database_host;
    global $database_username;
    global $database_password;
    global $bp_showed;
    
    $db = new mysqli($database_host, $database_username, $database_password, $source);
    if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $t_bp_son = array();
    $bp_source = array();

    $sql_bp = "SELECT bp_link, bp_source FROM bp_links WHERE bp_name = '".$bp_racine."'";

    if(!$result_bp = $db->query($sql_bp)){
        die('There was an error running the query [' . $db->error . ']');
    }

    while($row = $result_bp->fetch_assoc()){   
        array_push($t_bp_son,$row['bp_link']);
        array_push($bp_source,$row['bp_source']);
	} 

    $result_bp->free();

    if(sizeof($t_bp_son) > 0 ) {
        for ($i = 0; $i < sizeof($t_bp_son); $i++) { ?>
        	<li class="son">
				<ul class="nav nav-list tree">
					<?php
					display_bp($t_bp_son[$i],$bp_racine,$bp_source[$i]."_nagiosbp");
					array_push($bp_showed,$t_bp_son[$i]);
					if($bp_source[$i] == "global"){
						display_global_son($t_bp_son[$i],$bp_source[$i]."_nagiosbp");
					} else {
						display_son($t_bp_son[$i],$bp_source[$i]."_nagiosbp");
					} ?>
				</ul>
			</li>
        <?php }
    }
}

function display_other_source_bp(){
    global $database_vanillabp;
    global $database_host;
    global $database_username;
    global $database_password;
    global $bp_showed;

	$db = new mysqli($database_host, $database_username, $database_password, $database_vanillabp);
	$sql_source = "SELECT db_names FROM bp_sources";
	
	if(!$result_source = $db->query($sql_source)){
		die('There was an error running the query [' . $db->error . ']');
	}

	while($row_source = $result_source->fetch_assoc()){  
		if ($row_source['db_names'] != "global_nagiosbp") {
			$db_source = new mysqli($database_host, $database_username, $database_password, $row_source['db_names']);
			if($db_source->connect_errno > 0){
				die('Unable to connect to database [' . $db_source->connect_error . ']');
			}
			$sql_parent = "SELECT name FROM bp WHERE name NOT IN (SELECT bp_link FROM bp_links) ORDER BY priority, name";
			if(!$result = $db_source->query($sql_parent)){
				die('There was an error running the query [' . $db_source->error . ']');
			}
			while($row = $result->fetch_assoc()){ 
				if (!in_array($row['name'],$bp_showed)) { ?> 
					<div class="well well-sm">
						<ul class="nav nav-list tree">
							<?php
							display_bp($row['name'],$row['name'],$row_source['db_names']);
							display_son($row['name'],$row_source['db_names']);
							?>
						</ul>
					</div>
				<?php }
			}
		}
	}
	$result_source->free();
	mysqli_close($db_source);
}

	$HTMLTREE ="";
	$db = new mysqli($database_host, $database_username, $database_password, $database_vanillabp);

	if($db->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql = "SELECT name FROM bp WHERE name NOT IN (SELECT bp_link FROM bp_links) ORDER BY priority, name";
	if(!$result = $db->query($sql)){
		die('There was an error running the query [' . $db->error . ']');
	}
	
	while($row = $result->fetch_assoc()){   
		array_push($t_bp_racine,$row['name']);
	} 

	$result->free();
	mysqli_close($db);
	?>
    
	<form>
		
		<div class="form-inline">
			<div class="form-group">
				<div class="btn-group">
					<button id="show-all" class="btn btn-info" type="button"><?php echo getLabel("action.show_all") ?></button>
					<button id="hide-all" class="btn btn-info" type="button"><?php echo getLabel("action.hide_all") ?></button>
				</div><!-- /btn-group -->
				
			</div>
			
			<div class="form-group">
				<div class="input-group">
					<input type="text" class="form-control" id="SearchFor" placeholder="<?php echo getLabel("action.search"); ?>...">
					<span class="input-group-btn">
						<button class="btn btn-info" id="FindIt" type="button"><?php echo getLabel("action.search"); ?></button>
					</span>
				</div><!-- /input-group -->
			</div>
		</div>
		
		<div class="form-inline">
			<div class="form-group">		                   
				<a role="button" id="add-bp-app" class="btn btn-success" href="add_application.php?app">
					<?php echo getLabel("action.add_new_app"); ?>
				</a>
			</div>
			<div class="form-group">
				<a role="button" id="add-bp" class="btn btn-primary" href="add_application.php">
					<?php echo getLabel("action.add_new_component"); ?>
				</a>
			</div> 
		</div>

		<!-- affichage des bps de la source global_nagiosbp -->
		<div id="body" class="pad-top" style="display: none;">
		<?php for ($i = 0; $i < sizeof($t_bp_racine); $i++) { ?>
			<div class="well well-sm">
				<ul class="nav nav-list tree">
					<?php
					display_bp($t_bp_racine[$i],$t_bp_racine[$i],$database_vanillabp);
					display_global_son($t_bp_racine[$i], $database_vanillabp);
					?>
				</ul>
			</div>
		<?php }
		// affichage des bps des autres sources
		display_other_source_bp(); ?>
		</div>
		
	</form>

	<!-- modal for apply conf button -->
	<div id="popup_confirmation" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content panel-info">
				<div class="modal-header panel-heading">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><?php echo getLabel("action.delete"); ?></h4>
				</div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
					<button id="modal-confirmation-apply-conf" type="button" class="btn btn-primary">
						<?php echo getLabel("label.yes"); ?>
					</button>
					<button id="modal-confirmation-del-bp" type="button" class="btn btn-primary">
						<?php echo getLabel("label.yes"); ?>
					</button>
					<button id="action-cancel" type="button" class="btn btn-default" data-dismiss="modal">
						<?php echo getLabel("label.no"); ?>
					</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

</div>

<?php include("../../footer.php"); ?>
