<?php require 'includes/header.php';?>
<?php require 'includes/database.php';?>
<!--HTML HERE-->
<h2>Create a Visit</h2>
<?php require'includes/navbars/nav_picker.php';?>

<?php
if ($_SERVER["REQUEST_METHOD"] =="POST") {
    // visitId = autogenerated;
    $visitorId = $_POST['visitor'];
    date_default_timezone_set('Europe/London');
    $visitAddedDate = date('Y-m-d H:i:s');
    $hostAcademic = $_SESSION['username'];
    $s_date=$_POST['s_date'];
    $e_date=$_POST['e_date'];
    $summary=$_POST['summary'];
    $financialImp=$_POST['financialImp'];
    $inlineRadio1=$_POST['ipr_issues'];
    // iprFile = form;

    $conn = getDB();
    $sql = "INSERT INTO visit (visitorID, visitAddedDate, hostAcademic, startDate, endDate, summary, financialImplications, iprIssues) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        echo mysqli_error($conn);
    }
    mysqli_stmt_bind_param($stmt, "ssssssss", $visitorId, $visitAddedDate, $hostAcademic, $s_date, $e_date, $summary, $financialImp, $inlineRadio1);
    if (mysqli_stmt_execute($stmt)) {
        // TODO: Confirmation dialogue on success
        require 'includes/user_redirect.php';
    } else {
        echo mysqli_stmt_error($stmt);
    }
}

?>

<form method="post">
    <fieldset>
        <legend>Visitor</legend>
        <label for="Visitor">Visitor: </label>
        <?php
            $populatingVisitorDropDown = $link->query("SELECT visitorId, fName, lName from visitingAcademic");
        ?>
        <select name="visitor" id="visitor" class="form-control">
            <?php
            while ($rows = $populatingVisitorDropDown->fetch_assoc()) {
                $visitorId = $rows['visitorId'];
                $fName = $rows['fName'];
                $lName = $rows['lName'];
                $fullName = $fName. ' ' . $lName;
                echo "<option value='$visitorId'>$visitorId $fullName</option>";
            }
            ?>
        </select>
    </fieldset>
    <fieldset>
        <legend>Visit Dates</legend>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="s_date">Start Date: </label>
                <input type="date" name="s_date" class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label for="e_date">End Date: </label>
                <input type="date" name="e_date" class="form-control">
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Financial Implications</legend>
        <div class="form-group">
            <!-- <label for="financialImp">Financial Implications</label> -->
            <textarea class="form-control" id="financialImp" name="financialImp" rows="4" cols="40"
                placeholder="Please summarise the related financial implications"></textarea>
        </div>
    </fieldset>

    <fieldset>
        <legend>IPR Issues</legend>
        <p>Are there IPR issues with the visit?</p>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ipr_issues" id="inlineRadio1" value="yes"
                onchange='CheckIPR(this.value);'>
            <label class="form-check-label" for="inlineRadio1">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ipr_issues" id="inlineRadio1" value="no"
                onchange='CheckIPR(this.value);' checked>
            <label class="form-check-label" for="inlineRadio1">No</label>
        </div>

        <div class="custom-file" id="ipr_issues_ext" style='display:none;'>
            <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
        </div>

        <!-- <input type="text" id="ipr_issues_ext" name="ipr_issues_ext" class="form-control" style='display:none;'/> -->
        <!--TODO: Make attachment icon and message show if "yes" is selected above-->
    </fieldset>

    <fieldset>
        <legend>Additional Info</legend>
        <div class="form-group">
            <label for="summary">Summary of visit</label>
            <textarea class="form-control" id="summary" name="summary" rows="4" cols="40"
                placeholder="Please summarise the purpose of the visit"></textarea>
        </div>
    </fieldset>

    <button style="margin:10px 0px" type="submit" class="btn btn-primary btn-lg btn-block">Send</button>

    <?php require 'includes/footer.php';?>