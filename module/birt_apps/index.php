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
                    WHERE group_id='".$grp_id."' AND reports.type = 'technic';";
                } else {
                    $sql ="SELECT * FROM join_report_cred INNER JOIN reports ON reports.report_id = join_report_cred.report_id 
                    WHERE group_id='".$grp_id."' AND reports.type != 'technic';";
                }
                if(!$result = $db->query($sql)){
                    die("echo getLabel(\"label.manage_report.query_error\")". $db->error . ']');
                }
                while($row = $result->fetch_assoc()){
                    $sql2 ="SELECT report_name,output_format.type FROM join_report_format 
                    INNER JOIN reports ON reports.report_id = join_report_format.report_id 
                    INNER JOIN output_format ON join_report_format.output_format_id = output_format.format_id 
                    WHERE reports.report_name='".$row['report_name']."';";
                    if(!$result2 = $db->query($sql2)){
                        die("echo getLabel(\"label.manage_report.query_error\")". $db->error . ']');
                    }
                    echo" <tr>
                    <td>".$row['report_name']."</td>
                    <td>";
                    while($row2 = $result2->fetch_assoc()){
                        $selReport=$row['report_rptfile'];
                        $srvname= $_SERVER['SERVER_NAME'];
                        echo "<a href=\"http://".$srvname."/birt/run?__report=".$selReport."&__format=".$row2['type']."\" target=\"_blank\">".$row2['type']."</a> ";
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
