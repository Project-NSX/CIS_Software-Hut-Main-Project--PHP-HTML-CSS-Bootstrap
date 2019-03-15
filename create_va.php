<?php require 'includes/header.php';?>
<?php require 'includes/database.php';?>
<!--HTML HERE-->
<h2>Create a Visiting Academic</h2>
<?php require'includes/navbars/nav_picker.php';?>
<!--
    TODO: Make this page post to the database
    FIXME: This form inserts blankvalues into the datase
-->

<?php
// Initialising the title variable so nothing is displayed if the form hasn't been submitted
$title = '';
$f_name = '';
$l_name = '';
$street = '';
$town_city = '';
$county = '';
$postcode = '';
$email = '';
$phone_number = '';
$visitor_type = '';
$home_institution = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $street = $_POST['street'];
    $town_city = $_POST['town_city'];
    $county = $_POST['county'];
    $postcode = $_POST['postcode'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $visitor_type = $_POST['visitor_type'];
    $home_institution = $_POST['home_institution'];

    if (empty($errors)) {
        //include database connection information
        $conn = getDB();
        // Sql query using placeholders
        $sql = "INSERT INTO visitingAcademic (title, fName, lName, street, city, county, postcode, email, phoneNumber, visitorType, homeInstitution) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // Prepare the sql statement for execution using the connection info provided from
        // the database include
        $stmt = mysqli_prepare($conn, $sql);

        // Stmt will return false if there is an error in the mysql.
        // If it returns false, this error will be printed
        if ($stmt === false) {
            echo mysqli_error($conn);
        } else {
            if ($phone_number == '') {
                $phone_number = null;
            }
            if ($email == '') {
                $email = null;
            }

            // Bind to: $stmt, value types: "sss", From the sources $_POST['title'] etc
            mysqli_stmt_bind_param($stmt, "sssssssssss", $title, $f_name, $l_name, $street, $town_city, $county, $postcode, $email, $phone_number, $visitor_type, $home_institution);
            // If the execute function returns true..
            if (mysqli_stmt_execute($stmt)) {
                //echo "Inserted record with the ID: $id";
                require 'includes/user_redirect.php';
            }
            // Else, return the error that occoured
            else {
                echo mysqli_stmt_error($stmt);
            }
        }
    }
}

?>
<form method="post">

    <fieldset>
        <legend>Name</legend>
        <label for="title">Title: </label>
        <select name="title" id="title" class="form-control">
            <option value="mr">Mr</option>
            <option value="miss">Miss</option>
            <option value="mrs">Mrs</option>
            <option value="ms">Ms</option>
            <option value="dr">Dr</option>
            <option value="prof">Prof</option>
            <option value="other">Other</option>
        </select>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="f_name">First Name: </label>
                <input type="text" class="form-control" name="f_name" required>
            </div>
            <div class="form-group col-md-6">
                <label for="l_name">Last Name: </label>
                <input type="text" class="form-control" name="l_name" required>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Address</legend>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="street">Street: </label>
                <input type="text" class="form-control" name="street" required>
            </div>
            <div class="form-group col-md-6">
                <label for="town_city">Town / City: </label>
                <input type="text" class="form-control" name="town_city" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="county">County: </label>
                <input type="text" class="form-control" name="county" required>
            </div>
            <div class="form-group col-md-6">
                <label for="postcode">Postcode: </label>
                <input type="text" name="postcode"
                    pattern="[A-Za-z]{1,2}[0-9Rr][0-9A-Za-z]? [0-9][ABD-HJLNP-UW-Zabd-hjlnp-uw-z]{2}"
                    title="Please enter a valid UK postcode" class="form-control" required>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Contact Information</legend>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">Email: </label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="form-group col-md-6">
                <label for="phone_number">Phone Number: </label>
                <input type="tel" name="phone_number" class="form-control" minlength="9" maxlength="14">
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Academic Information</legend>
        <label for="visitor_typetype">Type of Academic: </label>
        <select name="visitor_type" id="visitor_type" class="form-control">
            <option value="undergrad">Undergraduate</option>
            <option value="phd">PhD student</option>
            <option value="vaPos">Visiting Academic (Position)</option>
            <option value="otherSpecify">Other (Specify)</option>
        </select>
        <div class="form-row">
            <label for="home_institution">Home Institution: </label>
            <input type="text" class="form-control" name="home_institution" required>
        </div>
    </fieldset>

    <button style="margin:10px 0px" type="submit" class="btn btn-primary btn-lg btn-block">Send</button>

</form>
<?php require 'includes/footer.php';?>