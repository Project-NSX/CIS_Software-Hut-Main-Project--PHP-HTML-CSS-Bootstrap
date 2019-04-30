<!-- Variable used to highlight the appropriate button on the navbar -->
<?php $page = 'CV';
require 'includes/header.php';
require 'includes/deny_hr_role.php'; // Redirects users with the "Human Resources" role to prevent access to this page
require 'includes/deny_va_role.php'; // Redirect visiting academics to prevent access to the page.
?>
<?php require 'includes/database.php'; ?>
<?php
//import phpMailer to send emails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

?>

<h2><?php echo $lang['Create a Visit'] ?></h2>
<?php require 'includes/navbars/nav_picker.php'; ?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //assign form fields to variables when form is submitted
    // visitId = autogenerated;
    $visitorId = $_POST['visitor'];
    date_default_timezone_set('Europe/London');
    $visitAddedDate = date('Y-m-d H:i:s');
    $hostAcademic = $_SESSION['username'];
    $s_date = $_POST['s_date'];
    $e_date = $_POST['e_date'];
    $summary = htmlspecialchars($_POST['summary']);
    $financialImp = htmlspecialchars($_POST['financialImp']);
    $inlineRadio1 = htmlspecialchars($_POST['ipr_issues']);
    $suppervisorVal = 3;
    //initialise phpMailer to send emails
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'support@nwsd.online';
    $mail->Password = 'twNqxeX4okGE';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('support@nwsd.online', 'Visitng Academic Form');
    $mail->Subject = "Visit At start date :{$s_date} End date : {$e_date}";
    $mail->Body = "A visit request has been made by the user: {$hostAcademic}. Please sign into the visiting academic form to respond to this.";
    $message = file_get_contents('Email.html');
    $message = str_replace('%startdate%', $s_date, $message);
    $message = str_replace('%enddate%', $e_date, $message);
    $message = str_replace('%summary%', $summary, $message);
    $message = str_replace('%HostAcademic%', $hostAcademic, $message);
    $message = str_replace('%visitorId%', $visitorId, $message);
    $mail->AddEmbeddedImage('img/bangor_logo.png', 'logo');
    $mail->MsgHTML($message);

    $conn = getDB();

    //gets the users input and adds the directory to the beginning before the file name
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
    } else {

        $iprBool = 0;
        $filename = null;
    }

    //Since the College Manager's request doesn't need approval by a supervisor there is a different SQL statement which appends values which then sends the visit straight to HR
    if ($_SESSION["role"] === "College Manager") {
        //SQL statement which will make the visit request bypass the supervisor stage for College Managers
        $sql = "INSERT INTO visit (visitorID, visitAddedDate, hostAcademic, startDate, endDate, summary, financialImplications, iprIssues, supervisorApproved, supervisorUsername, supervisorApprovedDate, iprFile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        //SQL statement which will make the request firs to go the supervisor for approval
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
    if (mysqli_stmt_execute($stmt)) {
        //Gets HR email if the request user logged in is a College Manager because it bypasses the supervisor stage
        if ($_SESSION["role"] === "College Manager") {
            $sql = "SELECT email FROM user where role = 'Human Resources'";
            $result = $link->query($sql);
            while ($row = $result->fetch_assoc()) {
                $email = $row["email"];
                $mail->addAddress("$email");
            }
        }

        // to get email for cm when hos makes request
        if ($_SESSION["role"] === "Head Of School") {
            $hosid = $_SESSION["college_id"];
            $sql = "SELECT email FROM user where college_id = '$hosid' AND role = 'College Manager'";
            $result = $link->query($sql);
            while ($row = $result->fetch_assoc()) {
                $email = $row["email"];
                $mail->addAddress("$email");
            }
        }

        // to get email for hos when academic makes request
        if ($_SESSION["role"] === "Academic") {
            $aid = $_SESSION["school_id"];
            $sql = "SELECT email FROM user where school_id = '$aid' AND role = 'Head Of School'";
            $result = $link->query($sql);
            while ($row = $result->fetch_assoc()) {
                $email = $row["email"];
                $mail->addAddress("$email");
            }
        }

        $mail->send(); //send email


        require 'includes/user_redirect.php'; //redirects afterwards
    } else {
        echo mysqli_stmt_error($stmt);
    }
}

?>

<form method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?php echo $lang['Visitor'] ?></legend>
        <label for="Visitor"><?php echo $lang['Visitor'] ?>: </label>
        <?php
        $populatingVisitorDropDown = $link->query("SELECT visitorId, fName, lName from visitingAcademic WHERE hostAcademic='{$_SESSION['username']}'");
        ?>
        <select name="visitor" id="visitor" class="form-control">
            <?php
            while ($rows = $populatingVisitorDropDown->fetch_assoc()) {
                $visitorId = $rows['visitorId'];
                $fName = htmlspecialchars($rows['fName']);
                $lName = htmlspecialchars($rows['lName']);
                $fullName = $fName . ' ' . $lName;
                echo "<option value='$visitorId'>$visitorId $fullName</option>";
            }
            ?>
        </select>
    </fieldset>
    <fieldset>
        <legend><?php echo $lang['Visit Dates'] ?></legend>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="s_date"><?php echo $lang['Start Date'] ?>: </label>
                <input id="datefield" type="date" name="s_date" onchange="updateDateFields()" class="form-control" max=e_date required>
            </div>
            <div class="form-group col-md-6">
                <label for="e_date"><?php echo $lang['End Date'] ?>: </label>
                <input id="dateend" type="date" name="e_date" class=" form-control" required>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend><?php echo $lang['Financial Implications'] ?></legend>
        <div class="form-group">
            <textarea class="form-control" id="financialImp" name="financialImp" rows="4" cols="40" placeholder="<?php echo $lang['Please summarise the related financial implications'] ?>" required></textarea>
        </div>
    </fieldset>

    <fieldset>
        <legend><?php echo $lang['IPR Issues'] ?></legend>
        <p><?php echo $lang['Are there IPR issues with the visit?'] ?></p>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ipr_issues" id="inlineRadio1" value="yes" onchange='CheckIPR(this.value);'>
            <label class="form-check-label" for="inlineRadio1"><?php echo $lang['Yes'] ?></label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ipr_issues" id="inlineRadio1" value="no" onchange='CheckIPR(this.value);' checked>
            <label class="form-check-label" for="inlineRadio1"><?php echo $lang['No'] ?></label>
        </div>

        <div class="custom-file" id="ipr_issues_ext" style='display:none;'>
            <label class="custom-file-label" for="inputGroupFile01"><?php echo $lang['Choose file'] ?></label>
            <input type="file" class="custom-file-input" id="file" name="file">
        </div>
    </fieldset>

    <fieldset>
        <legend><?php echo $lang['Additional Info'] ?></legend>
        <div class="form-group">
            <label for="summary"><?php echo $lang['Summary of visit'] ?></label>
            <textarea class="form-control" id="summary" name="summary" rows="4" cols="40" placeholder="<?php echo $lang['Please summarise the purpose of the visit'] ?>" required></textarea>
        </div>
    </fieldset>

    <button id="button1" style="margin:10px 0px" type="submit" class="btn btn-primary btn-lg btn-block"><?php echo $lang['Send'] ?></button>

    <script type="text/javascript">
        updateDateFields();
    </script>

    <?php require 'includes/footer.php'; ?>