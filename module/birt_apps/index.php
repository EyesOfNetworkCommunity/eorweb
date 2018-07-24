<?php
include("../../header.php");
include("../../side.php");

global $database_eorweb;
global $database_host;
global $database_username;
global $database_password;

$db = new mysqli($database_host, $database_username, $database_password, $database_eorweb);

if($db->connect_errno > 0){
    $response_array['status'] = getLabel("message.error");
}
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
                if(!$result = $db->query($sql)){
                    die("echo getLabel(\"label.manage_report.query_error\")". $db->error . ']');
                }
                while($row = $result->fetch_assoc()){
                    $sql2 ="SELECT report_name,output_format.type FROM join_report_format 
                    INNER JOIN reports ON reports.report_id = join_report_format.report_id 
                    INNER JOIN output_format ON join_report_format.output_format_id = output_format.format_id 
                    WHERE reports.report_name='".$row['report_name']."' ORDER BY report_name;";
                    if(!$result2 = $db->query($sql2)){
                        die("echo getLabel(\"label.manage_report.query_error\")". $db->error . ']');
                    }
             
                    if ($row["report_name"] != "Analyse incidents serveurs mensuel" && $row["report_name"] != "Analyse erreurs chargements nocturne" && $row["report_name"] != "Disponibilite applicative avancee mensuel" && $row["report_name"] != "Disponibilite applicative mensuel" && $row["report_name"] != "Analyse incidents serveurs mensuel tableur" && $row["report_name"] != "Analyse incidents applicatif journalier" && $row["report_name"] != "Disponibilite portefeuille de service mensuel") {
                        ?><tr>
                            <td><?php echo $row['report_name']; ?></td>
                        <?php
                    } else {
                        $report_name = strtolower(str_replace(' ', '_', $row["report_name"]));
                        ?>
                        <tr>
                            <td><?php echo getLabel("label.manage_report.name_".$report_name); ?></td>
                        <?php
                    }
                    echo "<td>";
                    
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
</div>

<?php 
include("../../footer.php");
?>
