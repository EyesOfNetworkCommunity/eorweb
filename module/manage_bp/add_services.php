<?php
/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Jean-Philippe LEVY
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

$bp_name = isset($_GET['bp_name']) ? $_GET['bp_name'] : false;
$display_actually_bp = isset($_GET['display']) ? $_GET['display'] : false;
$source = isset($_GET['source']) ? $_GET['source'] : $database_vanillabp;

try {
    $bdd = new PDO("mysql:host=$database_host;dbname=$source", $database_username, $database_password);
    }
catch(Exception $e) {
	echo "Connection failed: " . $e->getMessage();
	exit('Impossible de se connecter à la base de données.');
}

?>
<div id="page-wrapper">

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header bp_name"><?php echo getLabel("label.manage_bp.business_process");?> : <?php echo $bp_name; ?></h1>
		</div>
    </div>

	<div class="row">
		<div class="col-md-6">
			<?php echo"<form onsubmit=\"return false;\">"; ?>
			<input type="hidden" id="bp_name" name="bp_name" value="<?php echo $bp_name; ?>">
			<input type="hidden" id="bp_display" name="bp_display" value="<?php echo $display_actually_bp; ?>">
			<input type="hidden" id="source_name" name="source_name" value="<?php echo $source; ?>">
			<?php 
				if($display_actually_bp == 0) {	
					?>
					<div id="container_service">
						<label><?php echo getLabel("label.host"); ?></label>
						<div>
							<div class="row col-md-12">
								<div class="input-group">
									<span class="input-group-addon" id="sizing-addon1"><i class="fa fa-server"></i></span>
									<input type="text" class="form-control" id="host" placeholder="Hostname" aria-describedby="sizing-addon1" autocomplete="off">
								</div>
							</div>
						</div>

						<div class="row col-md-12">
							<div class="form-group">
								<label style="font-weight:lighter;font-size:16px;" class="control-label pad-top text-primary" id="process"></label>
							</div>
						</div>
						
						<div class="row col-md-12">
							<div class="form-group">
								<div id="draggablePanelList" class="list-unstyled">
								</div>
							</div>
						</div>
					</div>
				<?php }	 else { ?>
					<div id="container_process">
						<label><?php echo getLabel("label.manage_bp.display"); ?></label>
						<div>
							<div class="row col-md-12">
								<select class="form-control" name="display">
									<option data-hidden="true"><?php echo getLabel("label.manage_bp.select_display"); ?></option>
									<option>0</option>
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
								</select>
							</div>
						</div>
						
						<div class="row col-md-12">
							<div class="form-group">
								<label style="font-weight:lighter;font-size:16px;" class="control-label pad-top text-primary" id="process"></label>
							</div>
						</div>
						
						<div class="row col-md-12">
							<div class="form-group">
								<ul id="draggablePanelListProcess" class="list-unstyled">
								</ul>
							</div>
						</div>
					</div>
				<?php } ?>
		    </form>
		</div>

		<div class="col-md-6">
			<form id="form_drop" class="form-horizontal" style="top:0px">
				<?php $text_display = ($display_actually_bp > 0 ? "Process" : "Services"); ?>
				<label><?php $text_display; echo getLabel("label.manage_bp.linked_to_bp"); $bp_name ?></label>
				<div id="container-drop_zone" class="container-drop_zone">
					<?php 
					if($display_actually_bp > 0){
						if($source == $database_vanillabp) {
							$sql = "SELECT bp_link,bp_source FROM bp_links WHERE bp_name = '$bp_name'";
						} else {
							$sql = "SELECT bp_link FROM bp_links WHERE bp_name = '$bp_name'";
						}
						$req = $bdd->query($sql);
						$count = 0;

						while($row = $req->fetch()){
		               		$bp_name_linked = $row['bp_link'];
							if($source == $database_vanillabp) {
								$bp_name_linked .= "||".$row['bp_source'];
								$bp_source = $row['bp_source'];
							} else {
								$bp_source = substr($source,0,-9);
							}
		               		?>
							<div id="<?php echo "$bp_name::--;;$bp_name_linked"; ?>" class="well well-sm ui-front">
								<button type="button" class="btn btn-xs btn-danger button-addbp" onclick="DeleteService('<?php echo "$bp_name::--;;$bp_name_linked"; ?>','<?php echo $bp_source ?>');">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
								<b><?php echo $row['bp_link']; ?></b>
								<b class="condition_presentation" style="margin-left:5px;"><?php echo $bp_source; ?></b>
							</div>
							<?php $count += 1;
						}
						if($count == 0){
							?>
							<div id="primary_drop_zone" class="ui-widget-content panel panel-info" style="height:50px">
								<div class="text-center panel-body"><?php echo getLabel("label.manage_bp.drop_here"); ?></div>
							</div>
						<?php } 
					} else {
						$old_host = "";
						$old_host_count = 0;
						$sql = "SELECT host,service FROM bp_services WHERE bp_name = '" . $bp_name . "' ORDER BY host, service";
						$req = $bdd->query($sql);
						
						if($req->rowCount() != 0){
							while($row = $req->fetch()){
								$host = $row['host'];
								$service = $row['service'];
								
								if($host != $old_host){
									if($old_host_count!=0) {
										?>
										</div>
										</div>
										<?php  //fermeture du div du host
									}
									?>
									<div id="drop_zone::<?php echo $host; ?>" class="ui-widget-content panel panel-info">
									<div id="panel::<?php echo $host; ?>" class="panel-heading panel-title"><?php echo $host; ?></div>
									<div class="pannel-body">
									<?php
									$old_host=$host;
									$old_host_count++;
								} ?>								
								<div id="<?php echo "$bp_name::$host;;$service"; ?>" class="well well-sm ui-front">
								<button type="button" class="btn btn-xs btn-danger button-addbp" onclick="DeleteService('<?php echo $bp_name."::".$host.";;".$service; ?>','<?php echo $source; ?>');">
								<span class="glyphicon glyphicon-trash"></span>
								</button>
								<b><?php echo $service; ?></b>
								<b class="condition_presentation" style="margin-left:5px;"><?php echo substr($source,0,-9); ?></b>
								</div>
							<?php } ?>
							</div>
							</div>
						<?php }
						if($old_host == ""){ // ca signifie que aucun service n'est ajoute
						?>
							<div id="primary_drop_zone" class="ui-widget-content panel panel-info" style="height:50px">
								<div class="text-center panel-body"><?php echo getLabel("label.manage_bp.drop_here"); ?></div>
							</div>
						<?php }	
					} 
				//fermeture du div container-drop_zone ?>
				</div> 
				<br>
				<div class="btn-group btn-group-justified">
					<a class="btn btn-success" onclick="<?php echo (($display_actually_bp == 0)?'ApplyService();':'ApplyProcess();'); ?>">
						<?php echo getLabel("action.apply"); ?>
					</a>
					<a class="btn btn-primary" onclick="window.location = '/module/manage_bp/index.php';">
						<?php echo getLabel("action.cancel"); ?>
					</a>
				</div>
			</form>
		</div>
	</div>

</div>

<?php include("../../footer.php"); ?>
