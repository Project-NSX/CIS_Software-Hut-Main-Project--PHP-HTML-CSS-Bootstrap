<!-- Variable used to highlight the appropriate button on the navbar -->
<?php $page = 'HRAR';
require 'includes/header.php';
require 'includes/verify_hr_role.php';
?>
<!--HTML HERE-->

<h2><?php echo $lang['Human Resources - Approved Requests'] ?></h2>
<?php require 'includes/navbars/nav_picker.php'; ?>

<?php
require_once 'includes/database.php';

//SQL statement to retrieve columns from database table
$supervisorApproved = "SELECT v.visitId, v.visitorId, va.fName, va.lName, va.homeInstitution, va.department, va.email, va.phoneNumber, v.summary, v.visitAddedDate, v.status,  v.financialImplications, va.visitorType, va.visitorTypeExt,  v.startDate, v.endDate, v.supervisorApproved, v.supervisorUsername, v.supervisorApprovedDate, v.hrApproved, v.hrUsername, v.hrApprovedDate, v.hrComment, v.induction, v.iprIssues, v.iprFile  FROM visit v, visitingAcademic va WHERE v.visitorId = va.visitorId AND v.supervisorApproved LIKE '3' AND v.hrApproved LIKE '3' AND v.induction LIKE '0' ORDER BY v.visitAddedDate DESC";
$supervisorApprovedresult = $link->query($supervisorApproved);
if ($supervisorApprovedresult->num_rows > 0) {
//if there is a record, the following code is executed
    echo $lang['hrTitle'];

    echo "<div id='accordion'>";
    while ($row = $supervisorApprovedresult->fetch_assoc()) {
        //save the column values as variables
        $visitId = $row["visitId"];
        $visitorId = $row["visitorId"];
        $headingId = "heading" . $visitId . $visitorId;
        $collapseId = "collapse" . $visitId . $visitorId;
        $collapseIdHash = "#collapse" . $visitId . $visitorId;

        $fName = htmlspecialchars($row["fName"]);
        $lName = htmlspecialchars($row["lName"]);
        $homeInt = htmlspecialchars($row["homeInstitution"]);
        $department = htmlspecialchars($row["department"]);
        $email = htmlspecialchars($row["email"]);
        $phone = htmlspecialchars($row["phoneNumber"]);
        $summary = htmlspecialchars($row["summary"]);
        $visitAdded = htmlspecialchars($row["visitAddedDate"]);
        $financialImp = $row["financialImplications"]; //done
        $visitorType = $row["visitorType"]; //done
        $visitorTypeEXT = htmlspecialchars($row["visitorTypeExt"]); //done
        $visitStart = $row["startDate"]; //done
        $visitEnd = $row["endDate"]; //done
        $startDisplay = date("d/m/Y", strtotime($visitStart)); //format the date to be displayed
        $endDisplay = date("d/m/Y", strtotime($visitEnd)); //format the date to be displayed
        $addedDisplay = date("d/m/Y - g:iA", strtotime($visitAdded)); //format the date to be displayed
        $supervisorApproved = $row["supervisorApprovedDate"];
        $supervisorUname = $row["supervisorUsername"];
        $supervisorApprovedDate = $row["supervisorApprovedDate"];
        $supervisorApprovedDateDisp = date("d/m/Y - g:iA", strtotime($supervisorApprovedDate)); //format the date to be displayed
        $hrApproved = $row["hrApprovedDate"];
        $hrUname = $row["hrUsername"];
        $hrApprovedDate = $row["hrApprovedDate"];
        $hrApprovedDateDisp = date("d/m/Y - g:iA", strtotime($hrApprovedDate)); //format the date to be displayed
        $hrComment = htmlspecialchars($row['hrComment']);
        $iprIssues = $row['iprIssues'];
        $iprFile = $row['iprFile'];
        ?>

        <div class="card">
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
                    <h5 class='card-title'><?php echo $lang['Supervisor Username'] ?></h5>
                    <p class='card-text'><?php echo $supervisorUname ?> </p>
                    <h5 class='card-title'><?php echo $lang['Date & Time of Decision'] ?></h5>
                    <p class='card-text'><?php echo $supervisorApprovedDateDisp ?> </p>
                    <h5 class='card-title'><?php echo $lang['HR Practitioner Username'] ?></h5>
                    <p class='card-text'><?php echo $hrUname ?> </p>
                    <h5 class='card-title'><?php echo $lang['Date & Time of Decision'] ?></h5>
                    <p class='card-text'><?php echo $hrApprovedDateDisp ?> </p>
                    <!-- if the iprIssues variable is 1,  display a link to the ipr file, otherwise don't-->
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
} //if no records are returned, do nothing and therefore print nothing
else { }

$link->close();
?>

<?php require 'includes/footer.php'; ?>