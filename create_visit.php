<?php $page = 'CV';
require 'includes/header.php'; ?>
<?php require 'includes/database.php'; ?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

?>

<!--HTML HERE-->
<h2>Create a Visit</h2>
<?php require 'includes/navbars/nav_picker.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // visitId = autogenerated;
    $visitorId = $_POST['visitor'];
    date_default_timezone_set('Europe/London');
    $visitAddedDate = date('Y-m-d H:i:s');
    $hostAcademic = $_SESSION['username'];
    $s_date = $_POST['s_date'];
    $e_date = $_POST['e_date'];
    $summary = $_POST['summary'];
    $financialImp = $_POST['financialImp'];
    $inlineRadio1 = $_POST['ipr_issues'];
    $suppervisorVal = 3;
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'support@nwsd.online';
    $mail->Password = 'twNqxeX4okGE';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('support@nwsd.online', 'Visitng Academic Form');
    $mail->Subject = 'New visit request that requires your attention';
    $mail->Body = "A visit request has been made by the user: {$hostAcademic}. Please sign into the visiting academic form to respond to this.";
    $conn = getDB();

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
        echo 'alert("File uploaded successfully")';
        $iprBool = 1;
    } else {

        $iprBool = 0;
    }

    if ($_SESSION["role"] === "College Manager") {
        $sql = "INSERT INTO visit (visitorID, visitAddedDate, hostAcademic, startDate, endDate, summary, financialImplications, iprIssues, supervisorApproved, supervisorUsername, supervisorApprovedDate, iprFile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "INSERT INTO visit (visitorID, visitAddedDate, hostAcademic, startDate, endDate, summary, financialImplications, iprIssues, iprFile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        echo mysqli_error($conn);
    }
    if ($_SESSION["role"] === "College Manager") {
        mysqli_stmt_bind_param($stmt, "sssssssissss", $visitorId, $visitAddedDate, $hostAcademic, $s_date, $e_date, $summary, $financialImp, $iprBool, $suppervisorVal, $hostAcademic, $visitAddedDate, $filename);
    } else {
        mysqli_stmt_bind_param($stmt, "sssssssis", $visitorId, $visitAddedDate, $hostAcademic, $s_date, $e_date, $summary, $financialImp, $iprBool, $filename);
    }
    // If Statement executes properly.
    if (mysqli_stmt_execute($stmt))
    {

        if ($_SESSION["role"] === "College Manager") {
            $sql = "SELECT email FROM user where role = 'Human Resources'";
            $result = $link->query($sql);
            while ($row = $result->fetch_assoc())
            {
                $email = $row["email"];
                $mail->addAddress("$email");
            }

            $mail->addAddress("{$email}");

        }

        // to get email for cm when hos makes request
        if ($_SESSION["role"] === "Head Of School") {
            $hosid = $_SESSION["college_id"];
            $sql = "SELECT email FROM user where college_id = '$hosid' AND role = 'College Manager'";
            $result = $link->query($sql);
            while ($row = $result->fetch_assoc())
            {
                $email = $row["email"];
                $mail->addAddress("$email");
            }

            $mail->addAddress("{$email}");
        }

        // to get email for hos when academic makes request
        if ($_SESSION["role"] === "Academic") {
            $aid = $_SESSION["school_id"];
            $sql = "SELECT email FROM user where school_id = '$aid' AND role = 'Head Of School'";
            $result = $link->query($sql);
            while ($row = $result->fetch_assoc())
            {
                $email = $row["email"];
                $mail->addAddress("$email");
            }

            $mail->addAddress("{$email}");
        }

        $mail->send();


        require 'includes/user_redirect.php';
    } else {
        echo mysqli_stmt_error($stmt);
    }
}

?>

<form method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>Visitor</legend>
        <label for="Visitor">Visitor: </label>
        <?php
        $populatingVisitorDropDown = $link->query("SELECT visitorId, fName, lName from visitingAcademic WHERE hostAcademic='{$_SESSION['username']}'");
        ?>
        <select name="visitor" id="visitor" class="form-control">
            <?php
            while ($rows = $populatingVisitorDropDown->fetch_assoc()) {
                $visitorId = $rows['visitorId'];
                $fName = $rows['fName'];
                $lName = $rows['lName'];
                $fullName = $fName . ' ' . $lName;
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
                <input id="datefield" type="date" name="s_date" onchange="updateDateFields()" class="form-control" max=e_date required>
            </div>
            <div class="form-group col-md-6">
                <label for="e_date">End Date: </label>
                <input id="dateend" type="date" name="e_date" class=" form-control" required>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Financial Implications</legend>
        <div class="form-group">
            <textarea class="form-control" id="financialImp" name="financialImp" rows="4" cols="40" placeholder="Please summarise the related financial implications" required></textarea>
        </div>
    </fieldset>

    <fieldset>
        <legend>IPR Issues</legend>
        <p>Are there IPR issues with the visit?</p>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ipr_issues" id="inlineRadio1" value="yes" onchange='CheckIPR(this.value);'>
            <label class="form-check-label" for="inlineRadio1">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ipr_issues" id="inlineRadio1" value="no" onchange='CheckIPR(this.value);' checked>
            <label class="form-check-label" for="inlineRadio1">No</label>
        </div>

        <div class="custom-file" id="ipr_issues_ext" style='display:none;'>
            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
            <input type="file" class="custom-file-input" id="file" name="file">
            <!-- <input type="file" class="custom-file-input" id="inputGroupFile01" name="file" aria-describedby="inputGroupFileAddon01"> -->
        </div>

        <!-- <input type="text" id="ipr_issues_ext" name="ipr_issues_ext" class="form-control" style='display:none;'/> -->
        <!--TODO: Make attachment icon and message show if "yes" is selected above-->
    </fieldset>

    <fieldset>
        <legend>Additional Info</legend>
        <div class="form-group">
            <label for="summary">Summary of visit</label>
            <textarea class="form-control" id="summary" name="summary" rows="4" cols="40" placeholder="Please summarise the purpose of the visit" required></textarea>
        </div>
    </fieldset>

    <button id="button1" style="margin:10px 0px" type="submit" class="btn btn-primary btn-lg btn-block">Send</button>

    <script type="text/javascript">
        updateDateFields();
    </script>

    <?php require 'includes/footer.php'; ?>