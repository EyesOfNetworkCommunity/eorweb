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
?>

<div id="page-wrapper">

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.manage_bp.process_title"); ?></h1>
		</div>
	</div>

	<div id="error-message"></div>

	<?php
	if (isset($_GET['app'])) {
		$type_app = true;
	} else {
		$type_app = false;
	}

	if(isset($_GET['bp_name'])){
		$bp_name = $_GET['bp_name'];
	}

	if (isset($_GET['source'])) {
		$source = $_GET['source'];
	}

	if(! empty($bp_name) &&  ! empty($source)){
		try {
			$bdd = new PDO("mysql:host=$database_host;dbname=$source", $database_username, $database_password);
		}
		catch(Exception $e) {
			echo "Connection failed: " . $e->getMessage();
			exit('Impossible de se connecter à la base de données.');
		}

		$sql = "SELECT * FROM bp WHERE name = '" . $bp_name . "'";
		$req = $bdd->query($sql);
		$info = $req->fetch();
		$bp_desc = $info['description'];
		$bp_url = $info['url'];
		$bp_prior = $info['priority'];
		$bp_type = $info['type'];
		$bp_command = $info['command'];
		$bp_minvalue = $info['min_value'];
	} 
	?>

    <div class="panel panel-default">
        <div class="panel-heading">
			<?php 
			if ($type_app) { 
				echo getLabel("action.add_new_app");
			}
			else {
				echo getLabel("action.add_new_component");	
			}
			?>
		</div>
        <div class="panel-body">
        	<form class="form-horizontal col-md-8 col-md-offset-2">
                
                <?php
                if (!$type_app || !empty($bp_name)) {
                    ?>
                    <div class="row">
                        <div class="form-group">
                            <label style="font-weight:normal" class="col-xs-3 control-label"><?php echo getLabel("label.manage_bp.database_source"); ?></label>
                            <div class="col-xs-8">
                                <select class="form-control" name="source_name" <?php if(isset($bp_name)) { echo "disabled"; } ?>>
                                <?php 
                                $db_list = sqlrequest($database_vanillabp,"SELECT db_names, nick_name FROM bp_sources");
                                while ($row = mysqli_fetch_array($db_list)) {
                                    $selected = "";
                                    if(isset($_GET["source"]) && $_GET["source"] == $row['db_names']) { 
                                        $selected = "selected=\"selected\""; 
                                    }
                                    echo "<option value=\"".$row[0]."\" ".$selected.">". $row[1] . "</option>";
                                } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                
                <div class="row">
                	<div class="form-group">
                    	<label style="font-weight:normal" class="col-xs-3 control-label"><?php echo getLabel("label.manage_bp.unique_name"); ?></label>
                        <div class="col-xs-8">
							<input type="hidden" id="uniq_name_orig" value="<?php echo (isset($bp_name)?$bp_name:''); ?>">
                        	<input type="text" class="form-control" id="uniq_name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')" value="<?php echo (isset($bp_name)?$bp_name:''); ?>">
                        </div>
        			</div>
                </div>

        		<div class="row">
                    <div class="form-group">
                        <label style="font-weight:normal" class="col-xs-3 control-label"><?php echo getLabel("label.manage_bp.process_name"); ?></label>
                        <div class="col-xs-8">
                            <input type="text" class="form-control" id="process_name" onkeyup="this.value=this.value.replace(/[^éèàêâç0-9a-zA-Z-_ \/\*]/g,'')" value="<?php echo (isset($bp_desc)?$bp_desc:''); ?>">
                        </div>
                    </div>
                </div>
				
				<div class="row" <?php if (!$type_app){ echo "style=display:none;"; } ?>>
					<div class="row form-group">
						<label style="font-weight:normal" class="col-xs-3 control-label"><?php echo getLabel("label.manage_bp.category"); ?></label>
						<div class="col-md-9">
							<input type='checkbox' class='checkbox' id="category">
						</div>
					</div>
				</div>

				<div class="row" <?php if ($type_app){ echo "style=display:none;"; } ?>>
					<div class="form-group">
						<label style="font-weight:normal" class="col-xs-3 control-label"><?php echo getLabel("label.manage_bp.display"); ?></label>
						<div class="col-xs-8">
							<select class="form-control" name="display">
								<option value="None"><?php echo getLabel("label.none"); ?></option>
								<?php
								if ($type_app){ ?>
									<option value="1" selected="selected">1</option>
								<?php } 
								else {
									for ($i=0; $i <= 5; $i++) { ?>
										<option value="<?php echo $i; ?>" <?php if(isset($bp_prior) && $i == $bp_prior){ ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
									<?php } 
								} ?>
							</select>
						</div>
					</div>
				</div>

        		<div class="row">
                    <div class="form-group" <?php if ($type_app){ echo "style=display:none;"; } ?>>
                        <label style="font-weight:normal" class="col-xs-3 control-label"><?php echo getLabel("label.manage_bp.url"); ?></label>
                        <div class="col-xs-8">
                            <input type="text" class="form-control" id="url" value="<?php echo (isset($bp_url)?$bp_url:''); ?>">
                        </div>
                    </div>
                </div>

        		<div class="row" <?php if ($type_app){ echo "style=display:none;"; } ?>>
                    <div class="form-group">
                        <label style="font-weight:normal" class="col-xs-3 control-label"><?php echo getLabel("label.manage_bp.command"); ?></label>
                        <div class="col-xs-8">
                            <input type="text" class="form-control" id="command" value="<?php echo (isset($bp_command)?$bp_command:''); ?>">
                        </div>
                    </div>
                </div>

				<div class="row" <?php if ($type_app){ echo "style=display:none;"; } ?>>
					<div class="form-group">
						<label style="font-weight:normal" class="col-xs-3 control-label"><?php echo getLabel("label.manage_bp.type"); ?></label>
						<div class="col-xs-8">
							<select class="form-control" name="type" onchange="javascript:disable_min(this);">
								<?php
								if ($type_app){ ?>
									<option selected="selected">ET</option>
								<?php } 
								else {
									$list_type = array('ET','OU','MIN');
									foreach($list_type as $type){
										if(isset($bp_type) && $type == $bp_type){ ?>
											<option selected="selected"><?php echo $type; ?></option>
										<?php } else { ?>
											<option><?php echo $type; ?></option>
										<?php }
									} 
								} ?>
							</select>
						</div>
						</div>
				</div>

				<div class="row" id="container_select_minimum" <?php if ($type_app){ echo "style=display:none;"; } ?>>
					<div class="form-group">
						<label style="font-weight:normal" class="col-xs-3 control-label"><?php echo getLabel("label.manage_bp.min_value"); ?></label>
						<div class="col-xs-8">
							<select class="form-control" name="min_value" disabled>
								<option><?php echo (isset($bp_minvalue)?$bp_minvalue: 0); ?></option>
								<?php
								for ($i=1; $i <= 9; $i++) { 
									if (isset($bp_minvalue) && $i != $bp_minvalue) { ?>
										<option><?php echo $i; ?></option>
									<?php } else {
                                        if (!isset($bp_minvalue)) {?>
                                            <option><?php echo $i; ?></option>
                                    <?php }
                                    }
								} ?>
							</select>
						</div>
					</div>
				</div>

        		<div class="row" style="margin:auto;">
                    <div class="form-group">
        				<button class="btn btn-primary col-xs-offset-3" type="submit" id="submit" <?php echo (isset($bp_name)?'':'disabled'); ?>>
                        <?php
        				if(empty($bp_name)) {
                            echo getLabel("action.create");
						} else {
							echo getLabel("action.update");
						} ?>
            			</button>
                        <a href="index.php" class="btn btn-default"><?php echo getLabel("action.cancel"); ?></a>
        			</div>
        		</div>

        	</form>
        </div>
    </div>
</div>

<?php include("../../footer.php"); ?>