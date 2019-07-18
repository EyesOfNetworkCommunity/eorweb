<?php
include("../../header.php");
include("../../side.php");

?>

<div id="page-wrapper">
    <div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo getLabel("label.birt_app.title"); ?></h1>
		</div>
    </div>
    <div class="table-responsive">          
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo getLabel("label.birt_app.generate_title");?></th>
                    <th><?php echo getLabel("label.birt_app.format");?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grp_id= $_COOKIE['group_id'];
                if(isset($_GET["etl"])) {
                    $sql ="SELECT * FROM join_report_cred INNER JOIN reports ON reports.report_id = join_report_cred.report_id 
                    WHERE group_id='".$grp_id."' AND reports.type = 'technic' ORDER BY report_name;";
                } else {
                    $sql ="SELECT * FROM join_report_cred INNER JOIN reports ON reports.report_id = join_report_cred.report_id 
                    WHERE group_id='".$grp_id."' AND reports.type != 'technic' ORDER BY report_name;";
                }
                $result = sqlrequest($database_eorweb,$sql);
                while($row = $result->fetch_assoc()){
                    $sql2 ="SELECT report_name,output_format.type FROM join_report_format 
                    INNER JOIN reports ON reports.report_id = join_report_format.report_id 
                    INNER JOIN output_format ON join_report_format.output_format_id = output_format.format_id 
                    WHERE reports.report_name='".$row['report_name']."' ORDER BY report_name;";
                    $result2 = sqlrequest($database_eorweb,$sql2);
                    echo" <tr>
                    <td>".$row['report_name']."</td>
                    <td>";
                    while($row2 = $result2->fetch_assoc()){
                        $selReport=$row['report_rptfile'];
                        $srvname= $_SERVER['SERVER_NAME'];
                        echo "<a href=\"http://".$srvname."/birt/run?__report=".$selReport."&__format=".strtolower($row2['type'])."\" target=\"_blank\">".$row2['type']."</a> ";
                    }"</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <form id="global" method="post">
        <h1><?php echo getLabel("label.manage_report.generate_reports");?></h1>
        <div class="row form-group">
            <div class="col-md-6">
                <div class="input-group">
                    <label>Entrez un mois</label>
                    <input type="text" class="form-control" name="monthDate" id="monthDate" value="" placeholder="MM/YYYY">
                </div>
                    <span class="text-warning">
                    <?php
                      if (isset($_POST['generate'])) {
                        echo "Regénération des rapports en cours pour la date du ".$_POST['monthDate'].", soyez patients!";
                        shell_exec("/usr/bin/nohup /srv/eyesofreport/etl/scripts/script_generation_contract.sh ".$_POST['monthDate']." >/dev/null 2>&1 &");
                        shell_exec("/usr/bin/nohup /srv/eyesofreport/etl/scripts/script_generation_application.sh ".$_POST['monthDate']." >/dev/null 2>&1 &");
                        shell_exec("/usr/bin/nohup /srv/eyesofreport/etl/scripts/script_generation_incident_analysis.sh ".$_POST['monthDate']." >/dev/null 2>&1 &");
                      }
                    ?>
                   </span>
            </div>
        </div>
        <button type="submit" name="generate" class="btn btn-primary" id="generate"><?php echo getLabel("label.manage_report.generate_reports");?></button>
    </form>
</div>

<?php 
include("../../footer.php");
?>
