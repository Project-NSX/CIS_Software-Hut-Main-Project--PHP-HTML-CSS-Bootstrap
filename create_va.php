<?php $page ='CVa'; require 'includes/header.php';?>
<?php require 'includes/database.php';?>
<!--HTML HERE-->
<h2>Create a Visiting Academic</h2>
<?php require'includes/navbars/nav_picker.php';?>
<?php
// This says "if the user tries to post to the database, assign these $variables from the names of the html5 elements
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
    $visitor_type_ext = $_POST['visitor_type_ext'];
    $home_institution = $_POST['home_institution'];
    $host_academic = $_SESSION['username'];
    $department = $_POST['department'];



    //include database connection information
    $conn = getDB();
    // Sql query using placeholders
    // The placeholders are ?'s that are replcaed with the actual values in the form when the form is submitted.
    $sql = "INSERT INTO visitingAcademic (title, fName, lName, street, city, county, postcode, email, phoneNumber, visitorType, visitorTypeExt, homeInstitution, hostAcademic, department) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the sql statement for execution using the connection info provided from
    $stmt = mysqli_prepare($conn, $sql);

    // Stmt will return false if there is an error in the mysql.
    // If it returns false, this error will be printed
    if ($stmt === false) {
        echo mysqli_error($conn);
    // If there are no errors, then it'll check to see if some values are empty and if they are, it'll replace the empty strings with null
    } else {
        if ($phone_number == '') {
            $phone_number = null;
        }
        if ($email == '') {
            $email = null;
        }
        // Bind the variables defined above to the MySQL statement.
        // s - means string. For every variable entered there needs to be a ? above and letter that shows the datatype below.
        // Bind to: $stmt, value types: "sss", From the sources $_POST['title'] etc
        mysqli_stmt_bind_param($stmt, "ssssssssssssss", $title, $f_name, $l_name, $street, $town_city, $county, $postcode, $email, $phone_number, $visitor_type, $visitor_type_ext, $home_institution, $host_academic, $department);
        // If the $stmt is able to execute:
        if (mysqli_stmt_execute($stmt)) {
            // TODO: Confirmation dialogue on success

            // Redirect the user to their home page
            require 'includes/user_redirect.php';
        }
        // Else, return the error that occoured
        else {
            echo mysqli_stmt_error($stmt);
        }
    }
}

?>
<form method="post">
    <fieldset>
        <legend>Academic Information</legend>
        <label for="visitor_typetype">Type of Academic: </label>
        <select name="visitor_type" class="form-control" onchange='CheckVisitorTypeDropDown(this.value);' style="margin:0px 0px 10px 0px" required>
            <option value="Undergraduate">Undergraduate</option>
            <option value="PhD Student">PhD Student</option>
            <!--TODO: If "visitng Academic (position)"" or "Other (Specify)"" is selected, add the "required" option to visitor_type_ext-->
            <option value="Academic">Visiting Academic (Position)</option>
            <option value="Other">Other (Specify)</option>
        </select>
        <input type="text" id="visitor_type_ext" placeholder="Please specify the type of academic." name="visitor_type_ext" class="form-control"  style='display:none;' />
        <div class="form-row">
        <div class="form-group col-md-6">
            <label for="home_institution">Home Institution: </label>
            <input type="text" class="form-control" name="home_institution" required>
        </div>
        <div class="form-group col-md-6">
            <label for="department">Department: </label>
            <input type="text" class="form-control" name="department" required>
        </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>Name</legend>
        <label for="title">Title: </label>
        <select name="title" id="title" class="form-control" style="margin:0px 0px 10px 0px" required>
            <option value="Mr">Mr</option>
            <option value="Mrs">Mrs</option>
            <option value="Miss">Miss</option>
            <option value="Ms">Ms</option>
            <option value="Dr">Dr</option>
            <option value="Professor">Professor</option>
            <option value="Rev">Rev</option>
            <option value="Sir">Sir</option>
            <option value="Lady">Lady</option>
            <option value="Lord">Lord</option>
            <option value="Captain">Captain</option>
            <option value="Major">Major</option>
            <option value="Dame">Dame</option>
            <option value="Colonel">Colonel</option>
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
                    pattern="([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9][A-Za-z]?))))\s?[0-9][A-Za-z]{2})"
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
                <!--TODO: Restrict this to numbers only-->
                <input type="tel" name="phone_number" class="form-control" minlength="9" maxlength="14">
            </div>

        </div>
    </fieldset>
    <button id="button1" style="margin:10px 0px" type="submit" class="btn btn-primary btn-lg btn-block">Send</button>
</form>
<?php require 'includes/footer.php';?>