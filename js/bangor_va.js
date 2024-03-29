/**
 * Toggles the visibiltiy of a field where the user may
 * enter additional information about a visiting academic's
 * role depending on the selected visitor type.
 *
 * @param {string} val The type of visitor selected
 */
function CheckVisitorTypeDropDown(val) {
    var element = document.getElementById('visitor_type_ext');
    if (val == 'Academic' || val == 'Other') {
        element.style.display = 'block';
        document.getElementById("visitor_type_ext").required = true;
    } else {
        element.style.display = 'none';
        document.getElementById("visitor_type_ext").required = false;
    }

}

/**
 * Toggles the visibility of the IPR file dialog depending on the
 * given value.
 *
 * @param {string} val Whether there are IPR issues with the
 * current visit
 */
function CheckIPR(val) {
    var element = document.getElementById('ipr_issues_ext');
    if (val == 'yes') {
        element.style.display = 'block';
        document.getElementById("inputGroupFile01").required = true;
    } else {
        element.style.display = 'none';
        document.getElementById("inputGroupFile01   ").required = false;
    }

}

/**
 * Toggle the visibility of the password within
 * the login form's password field.
 */
function togglePasswordHidden() {
    var x = document.getElementById("passwordField");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

/**
 * Returns a string for the given date (or today's date
 * if a date is not given) to be used for updating the
 * min/max fields of date inputs.
 *
 * @param {Date} myDate the date to get a string from (or today's date)
 */
function getDateString(myDate) {
    var newDate = (myDate instanceof Date) ? myDate : new Date();
    return myDate.toJSON().split("T")[0];
}

/**
 * Returns a new Date object that is n years ahead of the
 * date given.
 *
 * @param {Date} myDate the date to use to get the date for next year
 * @param {number} n the number of years to skip ahead
 */
function getDateNYearsAhead(myDate, n) {
    // Creating new instance of date variable since we don't want to edit the original object
    var newDate = (myDate instanceof Date) ? new Date(myDate) : new Date();
    newDate.setFullYear(newDate.getFullYear() + n);
    return newDate;
}

/**
 * Updates the min/max attributes for the start and end date
 * fields in the "create a visitor" page so that the dates are
 * within a year of today's date.
 */
function updateDateFields() {
    // Get date fields
    var startField = document.getElementById("datefield");
    var endField = document.getElementById("dateend");

    // Update start date field's min/max attributes
    var today = new Date(); // Today's date
    var maxDate = getDateNYearsAhead(today, 2);
    startField.setAttribute("min", getDateString(today));
    startField.setAttribute("max", getDateString(maxDate));

    // Get start date field value and update the end date field's min/max attributes accordingly
    var startDateVal = startField.value;
    var startDate = (!startDateVal) ? today : new Date(startDateVal);

    endField.setAttribute("min", getDateString(startDate));
    endField.setAttribute("max", getDateString(maxDate));

    // Set default values for start and end dates
    if (!startField.value)
        startField.setAttribute("value", getDateString(today));

    if (!endField.value)
        endField.setAttribute("value", getDateString(today));
    else if (endField.value < startField.value)
        endField.setAttribute("value", startField.value);
}

/**
 * Updates the health & safety policy modal so that it will
 * update the correct visit when the user proceeds with the
 * induction.
 *
 * @param {number} visitID The visit ID to update
 */
function updateHealthSafetyModal(visitID) {
    $("#health-safety-modal > #health-safety-visit").setAttribute("value", visitID);
}