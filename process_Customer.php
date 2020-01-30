<!--
process_Customer page
This page handles both the editing of customer data, and the deletion of customer data from the mailing list
This page only provides the edit and delete functionality

<?php

// establishes connection to database and loads the customer Data Access Object class
require_once('./dao/customerDAO.php');
if (isset($_GET['action'])) {    // if URL parameter action is set, proceed to error checking

    // if the URL parameter indicates "delete" was selected by user
    if ($_GET['action'] == "delete") {
        if (isset($_GET['customerId']) && is_numeric($_GET['customerId'])) {
            $customerDAO = new customerDAO();
            $success = $customerDAO->deleteCustomer($_GET['customerId']);
            if ($success) { // if customer successfully deleted, return to mailing list page
                header('Location:mailing_list.php?deleted=true');
            } else {
                header('Location:mailing_list.php?deleted=false');
            }
        }
    }

    // If the URL parameter indicates "edit" was selected by user, then read in new inputs in text fields and
    // update the selected user data
    if ($_GET['action'] == "edit") {
        // first check if input field values are set
        if (isset($_POST['customerId']) &&
            isset($_POST['customerName']) &&
            isset($_POST['phoneNumber']) &&
            isset($_POST['emailAddress'])) {

            // if input field values are set, check for validation errors
            if (is_numeric($_POST['customerId']) &&
                $_POST['customerName'] != "" &&
                $_POST['phoneNumber'] != "" &&
                $_POST['emailAddress'] != "") {

                $customerDAO = new customerDAO();
                $success = $customerDAO->editCustomer($_POST["customerId"], $_POST["customerName"], $_POST["phoneNumber"], $_POST["emailAddress"],$_POST["referral"]);
                if ($success) { // if customer successfully edited, return to mailing list page
                    header('Location:mailing_list.php?edited=true');
                } else {
                    header('Location:mailing_list.php?edited=false');
                }
            }
        }
    }
}

?>