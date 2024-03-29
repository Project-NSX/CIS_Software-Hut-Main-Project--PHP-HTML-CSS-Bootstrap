<!-- Variable used to highlight the appropriate button on the navbar -->
<?php $page = 'HOSDR';
require 'includes/header.php';
require 'includes/verify_hos_role.php';
?>
<!--HTML HERE-->

<h2><?php echo $lang['Head of School - Denied Requests'] ?></h2>
<?php require 'includes/navbars/nav_picker.php'; ?>
<!--This page needs to show application pending approval from HR-->

<?php
require_once 'includes/database.php';
//SQL statement to retrieve columns from database table
$supervisorApproved = "SELECT v.visitId, v.visitorId, v.summary, v.financialImplications, v.startDate, v.endDate, v.visitAddedDate, v.supervisorApprovedDate, va.fName, va.lName, va.homeInstitution, va.department, va.visitorType, va.visitorTypeExt, v.iprIssues, v.iprFile FROM visit v, user u, school s, visitingAcademic va WHERE v.hostAcademic = u.username AND u.school_id = s.schoolId AND va.visitorId = v.visitorId AND u.school_id = '{$_SESSION['school_id']}' AND v.supervisorApproved LIKE '1' AND v.hostAcademic NOT LIKE '{$_SESSION['username']}' ORDER BY v.visitAddedDate DESC";
$supervisorApprovedresult = $link->query($supervisorApproved);
if ($supervisorApprovedresult->num_rows > 0) {
    //if 1 or more record exists, display the record by running the following code
    echo $lang['hosDenTitle'];
    echo "<div id='accordion'>";
    while ($row = $supervisorApprovedresult->fetch_assoc()) {
        //saves the database field value to a variable
        $visitId = $row["visitId"];
        $visitorId = $row["visitorId"];
        $headingId = "heading" . $visitId . $visitorId;
        $collapseId = "collapse" . $visitId . $visitorId;
        $collapseIdHash = "#collapse" . $visitId . $visitorId;
        $fName = $row["fName"];
        $lName = $row["lName"];
        $homeInt = $row["homeInstitution"];
        $department = $row["department"];
        $summary = $row["summary"];
        $financialImp = $row["financialImplications"];
        $visitorType = $row["visitorType"];
        $visitorTypeEXT = $row["visitorTypeExt"];
        $visitStart = $row["startDate"];
        $visitEnd = $row["endDate"];
        $Dateadded = $row["visitAddedDate"];
        $startDisplay = date("d/m/Y", strtotime($visitStart)); //formats the data as we want it to be displayed
        $endDisplay = date("d/m/Y", strtotime($visitEnd)); //formats the data as we want it to be displayed
        $addedDisplay = date("d/m/Y", strtotime($Dateadded)); //formats the data as we want it to be displayed
        $supervisorApprovedDate = $row["supervisorApprovedDate"];
        $suppervisorApproveDisplay = date("d/m/Y", strtotime($supervisorApprovedDate)); //formats the data as we want it to be displayed
        $iprIssues = $row['iprIssues'];
        $iprFile = $row['iprFile'];
        ?>
        <!-- Used a Card as an Accordion to display information in a condensed way -->
        <div class="card">
            <!-- Display key information in the header -->
            <div class="card-header" id="<?php echo $headingId ?>" <button id="button1" class="btn btn-link collapsed" data-toggle="collapse" data-target=" <?php echo $collapseIdHash ?>" aria-expanded="false" aria-controls=" <?php echo $collapseId ?>">
                <div class="row">
                    <div class='col-sm'><b><?php echo $lang['Name'] ?>: </b> <?php echo $fName . " " . $lName ?></div>
                    <div class='col-sm'><b><?php echo $lang['Home Institution'] ?>: </b> <?php echo $homeInt ?></div>
                    <div class='col-sm'><b><?php echo $lang['Department'] ?>: </b> <?php echo $department ?></div>
                </div>
                <div class="row">
                    <div class='col-md-1 offset-md-11' style="text-align: right;"><?php echo $lang['seeMore'] ?> &#x25BC</div>
                </div>
            </div>
            <!-- Display more detail in the body which is displayed after the card's header is pressed -->
            <div id="<?php echo $collapseId ?>" class="collapse" aria-labelledby="<?php echo $headingId ?>" data-parent="#accordion">
                <div class="card-body">
                    <h5 class='card-title'><?php echo $lang['Visit Summary'] ?></h5>
                    <p class='card-text'><?php echo $summary ?></p>
                    <h5 class='card-title'><?php echo $lang['Financial Implications'] ?></h5>
                    <p class='card-text'><?php echo $financialImp ?></p>
                    <h5 class='card-title'><?php echo $lang['Visitor Type'] ?></h5>
                    <p class='card-text'><?php echo $visitorType ?> &#8195; <?php echo $visitorTypeEXT ?></p>
                    <h5 class='card-title'><?php echo $lang['Visit Start & End Dates'] ?></h5>
                    <p class='card-text'><b><?php echo $lang['Start'] ?>:</b> <?php echo $startDisplay ?> &#8195; <b><?php echo $lang['End'] ?>:</b> <?php echo $endDisplay ?></p>
                    <h5 class='card-title'><?php echo $lang['Date & Time of Initial Submission'] ?></h5>
                    <p class='card-text'><?php echo $addedDisplay ?> </p>
                    <h5 class='card-title'><?php echo $lang['Date & Time of Approval'] ?></h5>
                    <p class='card-text'><?php echo $suppervisorApproveDisplay ?> </p>
                    <?php if ($iprIssues == 1) {
                        echo $lang['IPR'];
                        echo "<p class='card-text'><a href='ipr/$iprFile' download>$iprFile</a>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <br>
    <?php
}
echo "</div>";
} else { }
$link->close();

?>

<?php require 'includes/footer.php'; ?>