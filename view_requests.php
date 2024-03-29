<!-- Variable to be used to highlight appropriate button in navbar -->
<?php $page = 'home';
require 'includes/header.php';
require 'includes/deny_hr_role.php'; // Redirects users with the "Human Resources" role to prevent access to this page
require 'includes/deny_va_role.php'; // Redirect visiting academics to prevent access to the page.
?>
<!--Javascript to disable Enter key from submitting-->
<script type="text/javascript">
    function noenter() {
        return !(window.event && window.event.keyCode == 13);
    }
</script>
<style>
    span {
        display: inline-block;
        margin-right: 2.5em;
    }
</style>
<h2><?php echo $lang['Pending Requests'] ?></h2>

<?php require 'includes/navbars/nav_picker.php'; ?>



<?php

require_once 'includes/database.php';
//Cancel Action for section Visit Requests awaiting action
if (isset($_POST['VRAACancel'])) {
    date_default_timezone_set('Europe/London');
    $publish_date = date("Y-m-d H:i:s");
    $VRAACancelQuery = "UPDATE visit SET supervisorApproved = 4, hrApproved = 4, cancelTime = '$publish_date' WHERE visitId = '$_POST[hiddenVRAA]'";
    mysqli_query($link, $VRAACancelQuery);
};
//Cancel Action for section Visit Requests approved by Supervisor
if (isset($_POST['VRABSCancel'])) {
    date_default_timezone_set('Europe/London');
    $publish_date = date("Y-m-d H:i:s");
    $VRABSCancelQuery = "UPDATE visit SET supervisorApproved = 4, hrApproved = 4, cancelTime = '$publish_date' WHERE visitId = '$_POST[hiddenVRABS]'";
    mysqli_query($link, $VRABSCancelQuery);
};
//Cancel Action for section Visit Requests denied by Supervisor
if (isset($_POST['VRDBSCancel'])) {
    date_default_timezone_set('Europe/London');
    $publish_date = date("Y-m-d H:i:s");
    $VRDBSCancelQuery = "UPDATE visit SET supervisorApproved = 4, hrApproved = 4, cancelTime = '$publish_date' WHERE visitId = '$_POST[hiddenVRDBS]'";
    mysqli_query($link, $VRDBSCancelQuery);
};
//Cancel Action for section Visit Requests Approved by Supervisor & HR
if (isset($_POST['VRABSHRCancel'])) {
    date_default_timezone_set('Europe/London');
    $publish_date = date("Y-m-d H:i:s");
    $VRABSHRCancelQuery = "UPDATE visit SET supervisorApproved = 4, hrApproved = 4, cancelTime = '$publish_date' WHERE visitId = '$_POST[hiddenVRABSHR]'";
    mysqli_query($link, $VRABSHRCancelQuery);
};
//Cancel Action for section Visit Requests denied by HR
if (isset($_POST['VRDBHRCancel'])) {
    date_default_timezone_set('Europe/London');
    $publish_date = date("Y-m-d H:i:s");
    $VRDBHRCancelQuery = "UPDATE visit SET supervisorApproved = 4, hrApproved = 4, cancelTime = '$publish_date' WHERE visitId = '$_POST[hiddenVRDBHR]'";
    mysqli_query($link, $VRDBHRCancelQuery);
};

//Cancel Action for section Visit(s) Prompted for Resubmission by HR
if (isset($_POST['RPFRBHRSend'])) {
    date_default_timezone_set('Europe/London');
    $publish_date = date("Y-m-d H:i:s");
    $s_date = $_POST['s_date'];
    $e_date = $_POST['e_date'];
    $summary = $_POST['summary'];
    $financialImp = $_POST['financialImp'];

    if (!empty($_FILES['file']['name'])) {
        $pathinfo = pathinfo($_FILES['file']['name']);
        $base = $pathinfo['filename'];
        $base = preg_replace('/[^a-zA-Z0-9_-]/', "_", $base);
        $base = mb_substr($base, 0, 200);
        $filename = $base . "." . $pathinfo['extension'];
        $destination = "ipr/$filename";
        $i = 1;

        while (file_exists($destination)) {
            $filename = $base . "-$i." . $pathinfo['extension'];
            $destination = "ipr/$filename";
            $i++;
        }
        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
            $iprBool = 1;
            $RPFRBHRSendQuery = "UPDATE visit SET visitAddedDate = '$publish_date', startDate = '$s_date', endDate = '$e_date', summary = '$summary', financialImplications = '$financialImp', iprIssues = '$iprBool', iprFile = '$filename', supervisorApproved = 0, supervisorUsername = NULL, supervisorApprovedDate = NULL, supervisorCOmment = NULL, hrApproved = 0, hrUsername = NULL, hrApprovedDate = NULL, hrComment = NULL WHERE visitId = '$_POST[hiddenRPFRBHR]'";
        }
    }
    if ($iprBool != 1) {
        $iprBool = 0;
        $RPFRBHRSendQuery = "UPDATE visit SET visitAddedDate = '$publish_date', startDate = '$s_date', endDate = '$e_date', summary = '$summary', financialImplications = '$financialImp', iprIssues = '$iprBool', iprFile = NULL, supervisorApproved = 0, supervisorUsername = NULL, supervisorApprovedDate = NULL, supervisorCOmment = NULL, hrApproved = 0, hrUsername = NULL, hrApprovedDate = NULL, hrComment = NULL WHERE visitId = '$_POST[hiddenRPFRBHR]'";
    }
    mysqli_query($link, $RPFRBHRSendQuery);
};
//Cancel Action for section Visit(s) Prompted for Resubmission by Supervisor
if (isset($_POST['RPFRBSSend'])) {
    date_default_timezone_set('Europe/London');
    $publish_date = date("Y-m-d H:i:s");
    $s_date = $_POST['s_date'];
    $e_date = $_POST['e_date'];
    $summary = htmlspecialchars($_POST['summary']);
    $financialImp = $_POST['financialImp'];

    if (!empty($_FILES['file']['name'])) {
        $pathinfo = pathinfo($_FILES['file']['name']);
        $base = $pathinfo['filename'];
        $base = preg_replace('/[^a-zA-Z0-9_-]/', "_", $base);
        $base = mb_substr($base, 0, 200);
        $filename = $base . "." . $pathinfo['extension'];
        $destination = "ipr/$filename";
        $i = 1;

        while (file_exists($destination)) {
            $filename = $base . "-$i." . $pathinfo['extension'];
            $destination = "ipr/$filename";
            $i++;
        }
        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
            $iprBool = 1;
        }
    } else {
        $iprBool = 0;
        $filename = null;
    }
    $RPFRBSSendQuery = "UPDATE visit SET visitAddedDate = '$publish_date', startDate = '$s_date', endDate = '$e_date', summary = '$summary', financialImplications = '$financialImp', iprIssues = '$iprBool', iprFile = '$filename', supervisorApproved = 0, supervisorUsername = NULL, supervisorApprovedDate = NULL, supervisorCOmment = NULL, hrApproved = 0, hrUsername = NULL, hrApprovedDate = NULL, hrComment = NULL WHERE visitId = '$_POST[hiddenRPFRBS]'";
    mysqli_query($link, $RPFRBSSendQuery);
};

$currentAcademic = $_SESSION['username'];
//SQL statement to retrieve all the required columns from the visit and visitingAcademic tables in the database
$supervisorApproved = "SELECT v.visitId, v.visitorId, va.fName, va.lName, va.homeInstitution, va.department, va.email, va.phoneNumber, v.summary, v.visitAddedDate, v.status,  v.financialImplications, va.visitorType, va.visitorTypeExt,  v.startDate, v.endDate, v.supervisorApproved, v.supervisorUsername, v.supervisorApprovedDate, v.hrApproved, v.hrUsername, v.hrApprovedDate, v.hrComment, v.iprIssues, v.iprFile, va.title, va.street, va.city, va.county, va.postcode  FROM visit v, visitingAcademic va WHERE v.visitorId = va.visitorId AND v.hostAcademic LIKE '" . $currentAcademic . "%' AND v.supervisorApproved LIKE '3' AND v.hrApproved LIKE '2'  ORDER BY v.visitAddedDate DESC";
$supervisorApprovedresult = $link->query($supervisorApproved);
if ($supervisorApprovedresult->num_rows > 0) {
    echo $lang['resByHR'];
    echo "<div id='accordion'>";
    while ($row = $supervisorApprovedresult->fetch_assoc()) {
        $visitId = $row["visitId"];
        $visitorId = $row["visitorId"];
        $fName = htmlspecialchars($row["fName"]);
        $lName = htmlspecialchars($row["lName"]);
        $title = $row["title"];
        $homeInstitution = htmlspecialchars($row["homeInstitution"]);
        $department = htmlspecialchars($row["department"]);
        $street = htmlspecialchars($row["street"]);
        $city = htmlspecialchars($row["city"]);
        $county = htmlspecialchars($row["county"]);
        $postcode = htmlspecialchars($row["postcode"]);
        $email = htmlspecialchars($row["email"]);
        $phoneNumber = htmlspecialchars($row["phoneNumber"]);
        $visitAdded = $row["visitAddedDate"];
        $financialImp = htmlspecialchars($row["financialImplications"]);
        $visitorType = $row["visitorType"];
        $visitorTypeEXT = htmlspecialchars($row["visitorTypeExt"]);
        $visitStart = $row["startDate"];
        $visitEnd = $row["endDate"];
        $summary = htmlspecialchars($row["summary"]);
        $startDisplay = date("d/m/Y", strtotime($visitStart)); //format the date to be displayed in a clear and concise way
        $startDisplayDateDisp = date("Y-m-d", strtotime($visitStart)); //format the date to be used as input for the date pickers
        $endDisplay = date("d/m/Y", strtotime($visitEnd)); //format the date to be displayed in a clear and concise way
        $endDisplayDateDisp = date("Y-m-d", strtotime($visitEnd)); //format the date to be used as input for the date pickers
        $addedDisplay = date("d/m/Y - g:iA", strtotime($visitAdded)); //format the date to be displayed in a clear and concise way
        $supervisorApproved = $row["supervisorApprovedDate"];
        $supervisorUname = $row["supervisorUsername"];
        $supervisorApprovedDate = $row["supervisorApprovedDate"];
        $supervisorApprovedDateDisp = date("d/m/Y - g:iA", strtotime($supervisorApprovedDate)); //format the date to be displayed in a clear and concise way
        $hrApproved = $row["hrApprovedDate"];
        $hrUname = $row["hrUsername"];
        $hrApprovedDate = $row["hrApprovedDate"];
        $hrApprovedDateDisp = date("d/m/Y - g:iA", strtotime($hrApprovedDate)); //format the date to be displayed in a clear and concise way
        $hrComment = htmlspecialchars($row['hrComment']);
        $iprIssues = $row['iprIssues'];
        $iprFile = $row['iprFile'];
        ?>
        <form action=view_requests.php method=post enctype="multipart/form-data">
            <fieldset>
                <legend><?php echo $lang['Supervisor Decision Details'] ?></legend>
                <div class='row'>
                    <div class='col-sm-3'><b><?php echo $lang['Supervisor Username'] ?>:</b></div>
                    <div class='col-sm-3'><?php echo $supervisorUname ?></div>
                    <div class='col-sm-3'><b><?php echo $lang['Date Action Taken'] ?>:</b></div>
                    <div class='col-sm-3'><?php echo $supervisorApprovedDateDisp ?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php echo $lang['HR Decision Details'] ?></legend>
                <div class='row'>
                    <div class='col-sm-3'><b><?php echo $lang['HR Practitioner Username'] ?>:</b></div>
                    <div class='col-sm-3'><?php echo $hrUname ?></div>
                    <div class='col-sm-3'><b><?php echo $lang['Date Action Taken'] ?>:</b></div>
                    <div class='col-sm-3'><?php echo $hrApprovedDateDisp ?></div>
                </div>
                <div class='row'>
                    <div class='col-sm-3'><b><?php echo $lang['Comment'] ?>:</b></div>
                    <div class='col-sm-9'><?php echo $hrComment ?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php echo $lang['Personal Details'] ?></legend>
                <div class='row'>
                    <div class='col-sm'><b><?php echo $lang['Title'] ?>:</b></div>
                    <div class='col-sm'><?php echo $title ?></div>
                    <div class='col-sm'><b><?php echo $lang['First Name'] ?>:</b></div>
                    <div class='col-sm'><?php echo $fName ?></div>
                    <div class='col-sm'><b><?php echo $lang['Last Name'] ?>:</b></div>
                    <div class='col-sm'><?php echo $lName ?></div>
                </div>
                <div class='row'>
                    <div class='col-sm'><b><?php echo $lang['Email'] ?>:</b></div>
                    <div class='col-sm'><?php echo $email ?></div>
                    <div class='col-sm'><b><?php echo $lang['Phone Number'] ?>:</b></div>
                    <div class='col-sm'><?php echo $phoneNumber ?></div>
                    <div class='col-sm'><b><?php echo $lang['Visitor Type'] ?>:</b></div>
                    <div class='col-sm'><?php echo $visitorType . " " . $visitorTypeEXT ?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php echo $lang['Home Institution Details'] ?></legend>
                <div class='row'>
                    <div class='col-sm'><b><?php echo $lang['Home Institution Name'] ?>:</b></div>
                    <div class='col-sm'><?php echo $homeInstitution ?></div>
                    <div class='col-sm'><b><?php echo $lang['Department Name'] ?>:</b></div>
                    <div class='col-sm'><?php echo $department ?></div>
                </div>
                <div class='row'>
                    <div class='col-sm'><b><?php echo $lang['Street'] ?>:</b></div>
                    <div class='col-sm'><?php echo $street ?></div>
                    <div class='col-sm'><b><?php echo $lang['Town / City'] ?>:</b></div>
                    <div class='col-sm'><?php echo $city ?></div>
                    <div class='col-sm'><b><?php echo $lang['County'] ?>:</b></div>
                    <div class='col-sm'><?php echo $county ?></div>
                    <div class='col-sm'><b><?php echo $lang['Postcode'] ?>:</b></div>
                    <div class='col-sm'><?php echo $postcode ?></div>

                </div>
            </fieldset>

            <fieldset>
                <legend><?php echo $lang['Visitor Details'] ?></legend>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="s_date"><b><?php echo $lang['Visit Start Date'] ?>:</b> </label>
                        <!-- Appends the date from the database to the datefield -->
                        <input id="datefield" type="date" name="s_date" value="<?php echo $startDisplayDateDisp ?>" onchange="updateDateFields()" class="form-control" max=e_date required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="e_date"><b><?php echo $lang['Visit End Date'] ?>:</b> </label>
                        <!-- Appends the date from the database to the datefield -->
                        <input id="dateend" type="date" name="e_date" value="<?php echo $endDisplayDateDisp ?>" class="form-control" required>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <div class="form-group">
                    <label for="financialImp"><b><?php echo $lang['Financial Implications'] ?>:</b> </label>
                    <textarea class="form-control" id="financialImp" name="financialImp" rows="4" cols="40" placeholder="<?php echo $lang['Please summarise the related financial implications'] ?>" required> <?php echo $financialImp ?> </textarea>
                </div>
            </fieldset>

            <fieldset>
                <label for="ipr_issues"><b><?php echo $lang['IPR Issues'] ?>:</b> </label>

                <p><?php echo $lang['Are there IPR issues with the visit?'] ?> <b><?php echo $lang['NOTICE'] ?>:</b> <?php echo $lang['File must be uploaded again!'] ?> </p>
                <?php if ($iprIssues == 1) {
                    echo $lang['curFile'];
                } ?>

                <div class="form-check-inline">
                    <label class="form-check-label" for="radio1">
                        <input type="radio" class="form-check-input" id="radio1" name="ipr_issues" value="yes" onchange='CheckIPR(this.value);' <?php if ($iprIssues == 1) {
                                                                                                                                                    echo "checked";
                                                                                                                                                } ?>><?php echo $lang['Yes'] ?>
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label" for="radio2">
                        <input type="radio" class="form-check-input" id="radio1" name="ipr_issues" value="no" onchange='CheckIPR(this.value);' <?php if ($iprIssues != 1) {
                                                                                                                                                    echo "checked";
                                                                                                                                                } ?>><?php echo $lang['No'] ?>
                    </label>
                </div>




                <div class="custom-file" id="ipr_issues_ext" <?php ?>>
                    <label class="custom-file-label" for="inputGroupFile01"><?php echo $lang['Choose file'] ?></label>

                    <input type="file" class="custom-file-input" id="file" name="file">

                    <br>
                </div>
            </fieldset>

            <fieldset>
                <div class="form-group">
                    <label for="summary"><b><?php echo $lang['Summary of visit'] ?></b></label>
                    <textarea class="form-control" id="summary" name="summary" rows="4" cols="40" placeholder="<?php echo $lang['Please summarise the purpose of the visit'] ?>" required><?php echo $summary ?></textarea>
                </div>
            </fieldset>
            <input type=hidden name=hiddenRPFRBHR value=<?php echo $visitId ?>>
            <div class="container">
                <div class="row">
                    <div class="col-md"></div>
                    <!-- Button Resubmit request(s) Prompted for Resubmission by HR-->
                    <div class="col-md"><input type=submit name=RPFRBHRSend value="<?php echo $lang['Resubmit Visit Request'] ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $lang['Click to resubmit'] ?>" class='btn btn-secondary' style='width:100%; margin-bottom:5px'></div>
                    <div class="col-md"></div>
                </div>
            </div>
        </form>


        <script type="text/javascript">
            updateDateFields();
        </script>
        </form>

    <?php
}
echo "</div>";
} else { }

//SQL statement to retrieve all the required columns from the visit and visitingAcademic tables in the database
$supervisorApproved = "SELECT v.visitId, v.visitorId, va.fName, va.lName, va.homeInstitution, va.department, va.email, va.phoneNumber, v.summary, v.visitAddedDate, v.status,  v.financialImplications, va.visitorType, va.visitorTypeExt,  v.startDate, v.endDate, v.supervisorApproved, v.supervisorUsername, v.supervisorApprovedDate, v.supervisorComment, v.iprIssues, v.iprFile, va.title, va.street, va.city, va.county, va.postcode  FROM visit v, visitingAcademic va WHERE v.visitorId = va.visitorId AND v.hostAcademic LIKE '" . $currentAcademic . "%' AND v.supervisorApproved LIKE '2' ORDER BY v.visitAddedDate DESC";
$supervisorApprovedresult = $link->query($supervisorApproved);
if ($supervisorApprovedresult->num_rows > 0) {
    echo $lang['resBySup'];
    echo "<div id='accordion'>";
    while ($row = $supervisorApprovedresult->fetch_assoc()) {
        $visitId = $row["visitId"];
        $visitorId = $row["visitorId"];
        $fName = htmlspecialchars($row["fName"]);
        $lName = htmlspecialchars($row["lName"]);
        $title = $row["title"];
        $homeInstitution = htmlspecialchars($row["homeInstitution"]);
        $department = htmlspecialchars($row["department"]);
        $street = htmlspecialchars($row["street"]);
        $city = htmlspecialchars($row["city"]);
        $county = htmlspecialchars($row["county"]);
        $postcode = htmlspecialchars($row["postcode"]);
        $email = htmlspecialchars($row["email"]);
        $phoneNumber = htmlspecialchars($row["phoneNumber"]);
        $visitAdded = htmlspecialchars($row["visitAddedDate"]);
        $financialImp = htmlspecialchars($row["financialImplications"]);
        $visitorType = $row["visitorType"];
        $visitorTypeEXT = htmlspecialchars($row["visitorTypeExt"]);
        $visitStart = $row["startDate"];
        $visitEnd = $row["endDate"];
        $summary = $row["summary"];
        $startDisplay = date("d/m/Y", strtotime($visitStart)); //format the date to be displayed in a clear and concise way
        $startDisplayDateDisp = date("Y-m-d", strtotime($visitStart)); //format the date to be used as input for the date pickers
        $endDisplay = date("d/m/Y", strtotime($visitEnd)); //format the date to be displayed in a clear and concise way
        $endDisplayDateDisp = date("Y-m-d", strtotime($visitEnd)); //format the date to be used as input for the date pickers
        $addedDisplay = date("d/m/Y - g:iA", strtotime($visitAdded)); //format the date to be displayed in a clear and concise way
        $supervisorApproved = $row["supervisorApprovedDate"];
        $supervisorUname = $row["supervisorUsername"];
        $supervisorApprovedDate = $row["supervisorApprovedDate"];
        $supervisorApprovedDateDisp = date("d/m/Y - g:iA", strtotime($supervisorApprovedDate)); //format the date to be displayed in a clear and concise way
        $iprIssues = $row['iprIssues'];
        $iprFile = $row['iprFile'];
        $supervisorComment = htmlspecialchars($row['supervisorComment']);

        ?>
        <form action=view_requests.php method=post enctype="multipart/form-data">
            <fieldset>
                <legend><?php echo $lang['Supervisor Decision Details'] ?></legend>
                <div class='row'>
                    <div class='col-sm-3'><b><?php echo $lang['Supervisor Username'] ?>:</b></div>
                    <div class='col-sm-3'><?php echo $supervisorUname ?></div>
                    <div class='col-sm-3'><b><?php echo $lang['Date Action Taken'] ?>:</b></div>
                    <div class='col-sm-3'><?php echo $supervisorApprovedDateDisp ?></div>
                </div>
                <div class='row'>
                    <div class='col-sm-3'><b><?php echo $lang['Comment'] ?>:</b></div>
                    <div class='col-sm-9'><?php echo $supervisorComment ?></div>
                </div>
            </fieldset>


            <fieldset>
                <legend><?php echo $lang['Personal Details'] ?></legend>
                <div class='row'>
                    <div class='col-sm'><b><?php echo $lang['Title'] ?>:</b></div>
                    <div class='col-sm'><?php echo $title ?></div>
                    <div class='col-sm'><b><?php echo $lang['First Name'] ?>:</b></div>
                    <div class='col-sm'><?php echo $fName ?></div>
                    <div class='col-sm'><b><?php echo $lang['Last Name'] ?>:</b></div>
                    <div class='col-sm'><?php echo $lName ?></div>
                </div>
                <div class='row'>
                    <div class='col-sm'><b><?php echo $lang['Email'] ?>:</b></div>
                    <div class='col-sm'><?php echo $email ?></div>
                    <div class='col-sm'><b><?php echo $lang['Phone Number'] ?>:</b></div>
                    <div class='col-sm'><?php echo $phoneNumber ?></div>
                    <div class='col-sm'><b><?php echo $lang['Visitor Type'] ?>:</b></div>
                    <div class='col-sm'><?php echo $visitorType . " " . $visitorTypeEXT ?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?php echo $lang['Home Institution Details'] ?></legend>
                <div class='row'>
                    <div class='col-sm'><b><?php echo $lang['Home Institution Name'] ?>:</b></div>
                    <div class='col-sm'><?php echo $homeInstitution ?></div>
                    <div class='col-sm'><b><?php echo $lang['Department Name'] ?>:</b></div>
                    <div class='col-sm'><?php echo $department ?></div>
                </div>
                <div class='row'>
                    <div class='col-sm'><b><?php echo $lang['Street'] ?>:</b></div>
                    <div class='col-sm'><?php echo $street ?></div>
                    <div class='col-sm'><b><?php echo $lang['Town / City'] ?>:</b></div>
                    <div class='col-sm'><?php echo $city ?></div>
                    <div class='col-sm'><b><?php echo $lang['County'] ?>:</b></div>
                    <div class='col-sm'><?php echo $county ?></div>
                    <div class='col-sm'><b><?php echo $lang['Postcode'] ?>:</b></div>
                    <div class='col-sm'><?php echo $postcode ?></div>

                </div>
            </fieldset>

            <fieldset>
                <legend><?php echo $lang['Visitor Details'] ?></legend>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="s_date"><b><?php echo $lang['Visit Start Date'] ?>:</b> </label>
                        <!-- Appends the date from the database to the datefield -->
                        <input id="datefield" type="date" name="s_date" value="<?php echo $startDisplayDateDisp ?>" onchange="updateDateFields()" class="form-control" max=e_date required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="e_date"><b><?php echo $lang['Visit End Date'] ?>:</b> </label>
                        <!-- Appends the date from the database to the datefield -->
                        <input id="dateend" type="date" name="e_date" value="<?php echo $endDisplayDateDisp ?>" class="form-control" required>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <div class="form-group">
                    <label for="financialImp"><b><?php echo $lang['Financial Implications'] ?>:</b> </label>
                    <textarea class="form-control" id="financialImp" name="financialImp" rows="4" cols="40" placeholder="<?php echo $lang['Please summarise the related financial implications'] ?>" required> <?php echo $financialImp ?> </textarea>
                </div>
            </fieldset>

            <fieldset>
                <label for="ipr_issues"><b><?php echo $lang['IPR Issues'] ?>:</b> </label>

                <p><?php echo $lang['Are there IPR issues with the visit?'] ?> <b><?php echo $lang['NOTICE'] ?>:</b> <?php echo $lang['File must be uploaded again!'] ?> </p>
                <?php if ($iprIssues == 1) {
                    echo $lang['curFile'];
                }

                ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ipr_issues" id="inlineRadio1" value="yes" onchange='CheckIPR(this.value);' <?php
                                                                                                                                                    ?>>
                    <label class="form-check-label" for="inlineRadio1"><?php echo $lang['Yes'] ?>
</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ipr_issues" id="inlineRadio1" value="no" onchange='CheckIPR(this.value);' <?php
                                                                                                                                                    ?>>
                    <label class="form-check-label" for="inlineRadio1"><?php echo $lang['No'] ?>
</label>
                </div>

                <div class="custom-file" id="ipr_issues_ext" <? php ?>>
                    <label class="custom-file-label" for="inputGroupFile01"><?php echo $lang['Choose file'] ?></label>

                    <input type="file" class="custom-file-input" id="file" name="file">

                    <br>
                </div>
            </fieldset>

            <fieldset>
                <div class="form-group">
                    <label for="summary"><b><?php echo $lang['Summary of visit'] ?></b></label>
                    <textarea class="form-control" id="summary" name="summary" rows="4" cols="40" placeholder="<?php echo $lang['Please summarise the purpose of the visit'] ?>" required><?php echo $summary ?></textarea>
                </div>
            </fieldset>
            <input type=hidden name=hiddenRPFRBS value=<?php echo $visitId ?>>
            <div class="container">
                <div class="row">
                    <div class="col-md"></div>
                    <!-- Button Resubmit request(s) Prompted for Resubmission by Supervisor-->
                    <div class="col-md"><input type=submit name=RPFRBSSend value="<?php echo $lang['Resubmit Visit Request'] ?>" class='btn btn-secondary' data-toggle="tooltip" data-placement="top" title="<?php echo $lang['Resubmit the request'] ?>" style='width:100%; margin-bottom:5px'></div>
                    <div class="col-md"></div>
                </div>
            </div>
        </form>


        <script type="text/javascript">
            updateDateFields();
        </script>
        </form>

    <?php
}
echo "</div>";
} else { }

//SQL statement to retrieve all the required columns from the visit and visitingAcademic tables in the database
$awaitingAction = "SELECT v.visitId, v.visitorId, va.fName, va.lName, va.homeInstitution, va.department, va.email, va.phoneNumber, v.summary, v.visitAddedDate, v.status,  v.financialImplications, va.visitorType, va.visitorTypeExt,  v.startDate, v.endDate, v.iprIssues, v.iprFile  FROM visit v, visitingAcademic va WHERE v.visitorId = va.visitorId AND v.hostAcademic LIKE '" . $currentAcademic . "%' AND v.supervisorApproved LIKE '0' AND v.hrApproved LIKE '0'  ORDER BY v.visitAddedDate DESC";
$awaitingActionresult = $link->query($awaitingAction);
if ($awaitingActionresult->num_rows > 0) {
    echo $lang['reqAwaiting'];

    echo "<div id='accordion'>";
    while ($row = $awaitingActionresult->fetch_assoc()) {
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
        $visitAdded = $row["visitAddedDate"];
        $financialImp = htmlspecialchars($row["financialImplications"]);
        $visitorType = $row["visitorType"];
        $visitorTypeEXT = htmlspecialchars($row["visitorTypeExt"]);
        $visitStart = $row["startDate"];
        $visitEnd = $row["endDate"];
        $startDisplay = date("d/m/Y", strtotime($visitStart)); //format the date to be displayed in a clear and concise way
        $endDisplay = date("d/m/Y", strtotime($visitEnd)); //format the date to be displayed in a clear and concise way
        $addedDisplay = date("d/m/Y - g:iA", strtotime($visitAdded)); //format the date to be displayed in a clear and concise way
        $iprIssues = $row['iprIssues'];
        $iprFile = $row['iprFile'];
        ?>
        <form action=view_requests.php method=post>
<!-- Display Visits which require action and therefore pending -->
            <div class="card">
                <div class="card-header" id="<?php echo $headingId ?>" <button id="button1" class="btn btn-link collapsed" data-toggle="collapse" data-target=" <?php echo $collapseIdHash ?>" aria-expanded="false" aria-controls=" <?php echo $collapseId ?>">
                    <div class="row">
                        <div class='col-sm'><b><?php echo $lang['Name'] ?>: </b> <?php echo $fName . " " . $lName ?></div>
                        <div class='col-sm'><b><?php echo $lang['Home Institution'] ?>: </b> <?php echo $homeInt ?></div>
                        <div class='col-sm'><b><?php echo $lang['Department'] ?>: </b> <?php echo $department ?></div>
                        <div class='col-sm'><b><?php echo $lang['Email'] ?>: </b> <?php echo $email ?></div>
                        <div class='col-sm'><b><?php echo $lang['Phone Number'] ?>:</b> <?php echo $phone ?></div>
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
                        <?php if ($iprIssues == 1) {
                            echo $lang['IPR'];
                            echo "<p class='card-text'><a href='ipr/$iprFile' download>$iprFile</a>";
                        }
                        ?>
                        <input type=hidden name=hiddenVRAA value=<?php echo $visitId ?>>
                        <div class="container">
                            <div class="row">
                                <!-- Button to cancel visit -->
                                <div class="col-md"></div>
                                <div class="col-md"><input type=submit name=VRAACancel value="<?php echo $lang['Cancel Visit'] ?>" class='btn btn-warning' data-toggle="tooltip" data-placement="top" title="<?php echo $lang['CancelVisit'] ?>" style='width:100%; margin-bottom:5px'></div>
                                <div class="col-md"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <br>
    <?php
}
echo "</div>";
} else { }

//SQL statement to retrieve all the required columns from the visit and visitingAcademic tables in the database
$supervisorApproved = "SELECT v.visitId, v.visitorId, va.fName, va.lName, va.homeInstitution, va.department, va.email, va.phoneNumber, v.summary, v.visitAddedDate, v.status,  v.financialImplications, va.visitorType, va.visitorTypeExt,  v.startDate, v.endDate, v.supervisorApproved, v.supervisorUsername, v.supervisorApprovedDate, v.iprIssues, v.iprFile FROM visit v, visitingAcademic va WHERE v.visitorId = va.visitorId AND v.hostAcademic LIKE '" . $currentAcademic . "%' AND v.supervisorApproved LIKE '3' AND v.hrApproved LIKE '0'  ORDER BY v.visitAddedDate DESC";
$supervisorApprovedresult = $link->query($supervisorApproved);
if ($supervisorApprovedresult->num_rows > 0) {
    echo $lang['reqApprovedByS'];
    echo "<div id='accordion'>";
    while ($row = $supervisorApprovedresult->fetch_assoc()) {
        $visitId = $row["visitId"];
        $visitorId = $row["visitorId"];
        $headingId = "heading" . $visitId . $visitorId;
        $collapseId = "collapse" . $visitId . $visitorId;
        $collapseIdHash = "#collapse" . $visitId . $visitorId;
        $fName = $row["fName"];
        $lName = $row["lName"];
        $homeInt = $row["homeInstitution"];
        $department = $row["department"];
        $email = $row["email"];
        $phone = $row["phoneNumber"];
        $summary = $row["summary"];
        $visitAdded = $row["visitAddedDate"];
        $financialImp = $row["financialImplications"];
        $visitorType = $row["visitorType"];
        $visitorTypeEXT = $row["visitorTypeExt"];
        $visitStart = $row["startDate"];
        $visitEnd = $row["endDate"];
        $startDisplay = date("d/m/Y", strtotime($visitStart)); //format the date to be displayed in a clear and concise way
        $endDisplay = date("d/m/Y", strtotime($visitEnd)); //format the date to be displayed in a clear and concise way
        $addedDisplay = date("d/m/Y - g:iA", strtotime($visitAdded)); //format the date to be displayed in a clear and concise way
        $supervisorApproved = $row["supervisorApprovedDate"];
        $supervisorUname = $row["supervisorUsername"];
        $supervisorApprovedDate = $row["supervisorApprovedDate"];
        $supervisorApprovedDateDisp = date("d/m/Y - g:iA", strtotime($supervisorApprovedDate)); //format the date to be displayed in a clear and concise way
        $iprIssues = $row['iprIssues'];
        $iprFile = $row['iprFile'];
        ?>
        <form action=view_requests.php method=post>
<!-- Display Visits which have been approved by the Supervisor -->

            <div class="card">
                <div class="card-header" id="<?php echo $headingId ?>" <button class="btn btn-link collapsed" data-toggle="collapse" data-target=" <?php echo $collapseIdHash ?>" aria-expanded="false" aria-controls=" <?php echo $collapseId ?>">
                    <div class="row">
                        <div class='col-sm'><b><?php echo $lang['Name'] ?>: </b> <?php echo $fName . " " . $lName ?></div>
                        <div class='col-sm'><b><?php echo $lang['Home Institution'] ?>: </b> <?php echo $homeInt ?></div>
                        <div class='col-sm'><b><?php echo $lang['Department'] ?>: </b> <?php echo $department ?></div>
                        <div class='col-sm'><b><?php echo $lang['Email'] ?>: </b> <?php echo $email ?></div>
                        <div class='col-sm'><b><?php echo $lang['Phone Number'] ?>:</b> <?php echo $phone ?></div>
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
                        <?php if ($iprIssues == 1) {
                            echo $lang['IPR'];
                            echo "<p class='card-text'><a href='ipr/$iprFile' download>$iprFile</a>";
                        }
                        ?>
                        <input type=hidden name=hiddenVRABS value=<?php echo $visitId ?>>
                        <div class="container">
                            <div class="row">
                                <!-- Button to cancel visit -->
                                <div class="col-md"></div>
                                <div class="col-md"><input type=submit name=VRABSCancel value="<?php echo $lang['Cancel Visit'] ?>" class='btn btn-warning'data-toggle="tooltip" data-placement="top" title="<?php echo $lang['CancelVisit'] ?>" style='width:100%; margin-bottom:5px'></div>
                                <div class="col-md"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <br>
    <?php
}
echo "</div>";
} else { }

//SQL statement to retrieve all the required columns from the visit and visitingAcademic tables in the database
$supervisorApproved = "SELECT v.visitId, v.visitorId, va.fName, va.lName, va.homeInstitution, va.department, va.email, va.phoneNumber, v.summary, v.visitAddedDate, v.status,  v.financialImplications, va.visitorType, va.visitorTypeExt,  v.startDate, v.endDate, v.supervisorApproved, v.supervisorUsername, v.supervisorApprovedDate, v.supervisorComment, v.iprIssues, v.iprFile FROM visit v, visitingAcademic va WHERE v.visitorId = va.visitorId AND v.hostAcademic LIKE '" . $currentAcademic . "%' AND v.supervisorApproved LIKE '1' AND v.hrApproved LIKE '0'  ORDER BY v.visitAddedDate DESC";
$supervisorApprovedresult = $link->query($supervisorApproved);
if ($supervisorApprovedresult->num_rows > 0) {
    echo $lang['reqDeniedByS'];
    echo "<div id='accordion'>";
    while ($row = $supervisorApprovedresult->fetch_assoc()) {
        $visitId = $row["visitId"];
        $visitorId = $row["visitorId"];
        $headingId = "heading" . $visitId . $visitorId;
        $collapseId = "collapse" . $visitId . $visitorId;
        $collapseIdHash = "#collapse" . $visitId . $visitorId;
        $fName = $row["fName"];
        $lName = $row["lName"];
        $homeInt = $row["homeInstitution"];
        $department = $row["department"];
        $email = $row["email"];
        $phone = $row["phoneNumber"];
        $summary = $row["summary"];
        $visitAdded = $row["visitAddedDate"];
        $financialImp = $row["financialImplications"];
        $visitorType = $row["visitorType"];
        $visitorTypeEXT = $row["visitorTypeExt"];
        $visitStart = $row["startDate"];
        $visitEnd = $row["endDate"];
        $startDisplay = date("d/m/Y", strtotime($visitStart)); //format the date to be displayed in a clear and concise way
        $endDisplay = date("d/m/Y", strtotime($visitEnd)); //format the date to be displayed in a clear and concise way
        $addedDisplay = date("d/m/Y - g:iA", strtotime($visitAdded)); //format the date to be displayed in a clear and concise way
        $supervisorApproved = $row["supervisorApprovedDate"];
        $supervisorUname = $row["supervisorUsername"];
        $supervisorApprovedDate = $row["supervisorApprovedDate"];
        $supervisorApprovedDateDisp = date("d/m/Y - g:iA", strtotime($supervisorApprovedDate)); //format the date to be displayed in a clear and concise way
        $supervisorComment = $row["supervisorComment"];
        $iprIssues = $row['iprIssues'];
        $iprFile = $row['iprFile'];
        ?>
        <form action=view_requests.php method=post>
<!-- Display Visits which have been denied by the Supervisor -->
            <div class="card">
                <div class="card-header" id="<?php echo $headingId ?>" <button class="btn btn-link collapsed" data-toggle="collapse" data-target=" <?php echo $collapseIdHash ?>" aria-expanded="false" aria-controls=" <?php echo $collapseId ?>">
                    <div class="row">
                        <div class='col-sm'><b><?php echo $lang['Name'] ?>: </b> <?php echo $fName . " " . $lName ?></div>
                        <div class='col-sm'><b><?php echo $lang['Home Institution'] ?>: </b> <?php echo $homeInt ?></div>
                        <div class='col-sm'><b><?php echo $lang['Department'] ?>: </b> <?php echo $department ?></div>
                        <div class='col-sm'><b><?php echo $lang['Email'] ?>: </b> <?php echo $email ?></div>
                        <div class='col-sm'><b><?php echo $lang['Phone Number'] ?>:</b> <?php echo $phone ?></div>
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
                        <?php if ($iprIssues == 1) {
                            echo $lang['IPR'];
                            echo "<p class='card-text'><a href='ipr/$iprFile' download>$iprFile</a>";
                        }
                        ?>
                        <input type=hidden name=hiddenVRDBS value=<?php echo $visitId ?>>
                        <div class="container">
                            <div class="row">
                                <!-- Button to cancel visit -->
                                <div class="col-md"></div>
                                <div class="col-md"><input type=submit name=VRDBSCancel value="<?php echo $lang['Cancel Visit'] ?>" class='btn btn-warning' data-toggle="tooltip" data-placement="top" title="<?php echo $lang['CancelVisit'] ?>" style='width:100%; margin-bottom:5px'></div>
                                <div class="col-md"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <br>
    <?php

}
echo "</div>";
} else { }

//SQL statement to retrieve all the required columns from the visit and visitingAcademic tables in the database
$supervisorApproved = "SELECT v.visitId, v.visitorId, va.fName, va.lName, va.homeInstitution, va.department, va.email, va.phoneNumber, v.summary, v.visitAddedDate, v.status,  v.financialImplications, va.visitorType, va.visitorTypeExt,  v.startDate, v.endDate, v.supervisorApproved, v.supervisorUsername, v.supervisorApprovedDate, v.hrApproved, v.hrUsername, v.hrApprovedDate, v.iprIssues, v.iprFile  FROM visit v, visitingAcademic va WHERE v.visitorId = va.visitorId AND v.hostAcademic LIKE '" . $currentAcademic . "%' AND v.supervisorApproved LIKE '3' AND v.hrApproved LIKE '3'  ORDER BY v.visitAddedDate DESC";
$supervisorApprovedresult = $link->query($supervisorApproved);
if ($supervisorApprovedresult->num_rows > 0) {
    echo $lang['reqApprovedBySHR'];

    echo "<div id='accordion'>";
    while ($row = $supervisorApprovedresult->fetch_assoc()) {
        $visitId = $row["visitId"];
        $visitorId = $row["visitorId"];
        $headingId = "heading" . $visitId . $visitorId;
        $collapseId = "collapse" . $visitId . $visitorId;
        $collapseIdHash = "#collapse" . $visitId . $visitorId;
        $fName = $row["fName"];
        $lName = $row["lName"];
        $homeInt = $row["homeInstitution"];
        $department = $row["department"];
        $email = $row["email"];
        $phone = $row["phoneNumber"];
        $summary = $row["summary"];
        $visitAdded = $row["visitAddedDate"];
        $financialImp = $row["financialImplications"];
        $visitorType = $row["visitorType"];
        $visitorTypeEXT = $row["visitorTypeExt"];
        $visitStart = $row["startDate"];
        $visitEnd = $row["endDate"];
        $startDisplay = date("d/m/Y", strtotime($visitStart)); //format the date to be displayed in a clear and concise way
        $endDisplay = date("d/m/Y", strtotime($visitEnd)); //format the date to be displayed in a clear and concise way
        $addedDisplay = date("d/m/Y - g:iA", strtotime($visitAdded)); //format the date to be displayed in a clear and concise way
        $supervisorApproved = $row["supervisorApprovedDate"];
        $supervisorUname = $row["supervisorUsername"];
        $supervisorApprovedDate = $row["supervisorApprovedDate"];
        $supervisorApprovedDateDisp = date("d/m/Y - g:iA", strtotime($supervisorApprovedDate)); //format the date to be displayed in a clear and concise way
        $hrApproved = $row["hrApprovedDate"];
        $hrUname = $row["hrUsername"];
        $hrApprovedDate = $row["hrApprovedDate"];
        $hrApprovedDateDisp = date("d/m/Y - g:iA", strtotime($hrApprovedDate));
        $iprIssues = $row['iprIssues'];
        $iprFile = $row['iprFile'];
        ?>
        <form action=view_requests.php method=post>
<!-- Display Visits which have been approved by the Supervisor and HR -->
            <div class="card">
                <div class="card-header" id="<?php echo $headingId ?>" <button class="btn btn-link collapsed" data-toggle="collapse" data-target=" <?php echo $collapseIdHash ?>" aria-expanded="false" aria-controls=" <?php echo $collapseId ?>">
                    <div class="row">
                        <div class='col-sm'><b><?php echo $lang['Name'] ?>: </b> <?php echo $fName . " " . $lName ?></div>
                        <div class='col-sm'><b><?php echo $lang['Home Institution'] ?>: </b> <?php echo $homeInt ?></div>
                        <div class='col-sm'><b><?php echo $lang['Department'] ?>: </b> <?php echo $department ?></div>
                        <div class='col-sm'><b><?php echo $lang['Email'] ?>: </b> <?php echo $email ?></div>
                        <div class='col-sm'><b><?php echo $lang['Phone Number'] ?>:</b> <?php echo $phone ?></div>
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
                        <?php if ($iprIssues == 1) {
                            echo $lang['IPR'];
                            echo "<p class='card-text'><a href='ipr/$iprFile' download>$iprFile</a>";
                        }
                        ?>
                        <input type=hidden name=hiddenVRABSHR value=<?php echo $visitId ?>>
                            <div class="container">
                                <div class="row">
                                    <!-- Button to cancel visit -->
                                    <div class="col-md"></div>
                                    <div class="col-md"><input type=submit name=VRABSHRCancel value="<?php echo $lang['Cancel Visit'] ?>" class='btn btn-warning' data-toggle="tooltip" data-placement="top" title="<?php echo $lang['CancelVisit'] ?>" style='width:100%; margin-bottom:5px'></div>
                                    <div class="col-md"></div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </form>
        <br>
    <?php
}
echo "</div>";
} else { }

//SQL statement to retrieve all the required columns from the visit and visitingAcademic tables in the database
$supervisorApproved = "SELECT v.visitId, v.visitorId, va.fName, va.lName, va.homeInstitution, va.department, va.email, va.phoneNumber, v.summary, v.visitAddedDate, v.status,  v.financialImplications, va.visitorType, va.visitorTypeExt,  v.startDate, v.endDate, v.supervisorApproved, v.supervisorUsername, v.supervisorApprovedDate, v.hrApproved, v.hrUsername, v.hrApprovedDate, v.hrComment, v.iprIssues, v.iprFile  FROM visit v, visitingAcademic va WHERE v.visitorId = va.visitorId AND v.hostAcademic LIKE '" . $currentAcademic . "%' AND v.supervisorApproved LIKE '3' AND v.hrApproved LIKE '1'  ORDER BY v.visitAddedDate DESC";
$supervisorApprovedresult = $link->query($supervisorApproved);
if ($supervisorApprovedresult->num_rows > 0) {
    echo $lang['reqDenHR'];
    echo "<div id='accordion'>";
    while ($row = $supervisorApprovedresult->fetch_assoc()) {
        $visitId = $row["visitId"];
        $visitorId = $row["visitorId"];
        $headingId = "heading" . $visitId . $visitorId;
        $collapseId = "collapse" . $visitId . $visitorId;
        $collapseIdHash = "#collapse" . $visitId . $visitorId;
        $fName = $row["fName"];
        $lName = $row["lName"];
        $homeInt = $row["homeInstitution"];
        $department = $row["department"];
        $email = $row["email"];
        $phone = $row["phoneNumber"];
        $summary = $row["summary"];
        $visitAdded = $row["visitAddedDate"];
        $financialImp = $row["financialImplications"];
        $visitorType = $row["visitorType"];
        $visitorTypeEXT = $row["visitorTypeExt"];
        $visitStart = $row["startDate"];
        $visitEnd = $row["endDate"];
        $startDisplay = date("d/m/Y", strtotime($visitStart)); //format the date to be displayed in a clear and concise way
        $endDisplay = date("d/m/Y", strtotime($visitEnd)); //format the date to be displayed in a clear and concise way
        $addedDisplay = date("d/m/Y - g:iA", strtotime($visitAdded)); //format the date to be displayed in a clear and concise way
        $supervisorApproved = $row["supervisorApprovedDate"];
        $supervisorUname = $row["supervisorUsername"];
        $supervisorApprovedDate = $row["supervisorApprovedDate"];
        $supervisorApprovedDateDisp = date("d/m/Y - g:iA", strtotime($supervisorApprovedDate)); //format the date to be displayed in a clear and concise way
        $hrApproved = $row["hrApprovedDate"];
        $hrUname = $row["hrUsername"];
        $hrApprovedDate = $row["hrApprovedDate"];
        $hrApprovedDateDisp = date("d/m/Y - g:iA", strtotime($hrApprovedDate)); //format the date to be displayed in a clear and concise way
        $hrComment = $row['hrComment'];
        $iprIssues = $row['iprIssues'];
        $iprFile = $row['iprFile'];
        ?>
        <form action=view_requests.php method=post>
<!-- Display Visits which have been approved by the Supervisor and denied by HR -->

            <div class="card">
                <div class="card-header" id="<?php echo $headingId ?>" <button class="btn btn-link collapsed" data-toggle="collapse" data-target=" <?php echo $collapseIdHash ?>" aria-expanded="false" aria-controls=" <?php echo $collapseId ?>">
                    <div class="row">
                        <div class='col-sm'><b><?php echo $lang['Name'] ?>: </b> <?php echo $fName . " " . $lName ?></div>
                        <div class='col-sm'><b><?php echo $lang['Home Institution'] ?>: </b> <?php echo $homeInt ?></div>
                        <div class='col-sm'><b><?php echo $lang['Department'] ?>: </b> <?php echo $department ?></div>
                        <div class='col-sm'><b><?php echo $lang['Email'] ?>: </b> <?php echo $email ?></div>
                        <div class='col-sm'><b><?php echo $lang['Phone Number'] ?>:</b> <?php echo $phone ?></div>
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
                        <h5 class='card-title'><?php echo $lang['HR Comment'] ?></h5>
                        <p class='card-text'><?php echo $hrComment ?> </p>
                        <?php if ($iprIssues == 1) {
                            echo $lang['IPR'];
                            echo "<p class='card-text'><a href='ipr/$iprFile' download>$iprFile</a>";
                        }
                        ?>
                        <input type=hidden name=hiddenVRDBHR value=<?php echo $visitId ?>>
                        <div class="container">
                            <div class="row">
                                <!-- Button to cancel visit -->
                                <div class="col-md"></div>
                                <div class="col-md"><input type=submit name=VRABSCancel value="<?php echo $lang['Cancel Visit'] ?>" class='btn btn-warning' data-toggle="tooltip" data-placement="top" title="<?php echo $lang['CancelVisit'] ?>" style='width:100%; margin-bottom:5px'></div>
                                <div class="col-md"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
        <br>
    <?php
}
echo "</div>";
} else { }




$link->close();

?>
<?php require 'includes/footer.php'; ?>