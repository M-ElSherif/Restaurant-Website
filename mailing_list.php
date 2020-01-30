<!--
Mailing list page
Provides a new page with the mailing list that displays all signed up customers within a table
-->

<!-- Header section -->
<?php
include('./shared/header.php');
?>

<?php
require_once('./dao/customerDAO.php');	// Import the customer Data Access Object
require_once ('./WebsiteUser.php');

// Establish session call and check if current session websiteUser is authenticated to access the mailing list page
session_start();
session_regenerate_id(false);

if (isset($_SESSION['AdminID']) && isset($_SESSION['websiteUser'])) {	// if AdminID is set and websiteuser is set
	if(!$_SESSION['websiteUser']->isAuthenticated()) {
		header('Location:mailinglist.php');
	}
} else {
	header('Location:login.php');
}

$customerDAO = new customerDAO();   // new customer Data Access Object
$customers = $customerDAO->getCustomers();  // retreive all customers in database and store in an array

// The code that deletes a user in process_Customer.php directs back to this page
// with a parameter in the URL called 'deleted'. If deleted=true, display a confirmation message
if (isset($_GET['deleted'])) {
    if ($_GET['deleted'] == true) {
        echo '<h3 style = color:red> Customer Deleted </h3>';
    }
}

// The code that edits a user in process_Customer.php directs back to this page
// with a parameter in the URL called 'edited'. If edited=true, display a confirmation message
if (isset($_GET['edited'])) {
    if ($_GET['edited'] == true) {
        echo '<h3 style = color:greenyellow> Customer Updated </h3>';
    }
}
// If customers exists in the database, the following section generates a Mailing list table within the webpage
if ($customers) {   // if customers array exists
    echo '<table>';
    echo '<tr>';
    echo '<td> Session AdminID = ' . $_SESSION['AdminID'] . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> Last Login Date = ' . $_SESSION['websiteUser']->getDate() . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> <a href="logout.php">Logout</a> </td>';
    echo '</tr>';
    echo '</table>';
    echo '<table width="100%" height="100%" border="2">';
    echo '<tr><th>Customer ID</th><th>Customer Name</th> <th>Phone Number</th> <th>Email Address</th> <th>Referral</th></tr>';
    foreach ($customers as $customer) {
        $id = $customerDAO->getID($customer->getEmailAddress());    // get the customer id
        echo '<tr>';
        echo '<td style="text-align: center"><a href=\'edit_Customer.php?customerId=' . $id . '\'>' . $id . '</a></td>';
        echo '<td style="text-align: center">' . $customer->getCustomerName() . '</td>';
        echo '<td style="text-align: center">' . $customer->getPhoneNumber() . '</td>';
        echo '<td style="text-align: center">' . $customer->getEmailAddress() . '</td>';
        echo '<td style="text-align: center">' . $customer->getReferrer() . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<h3>Mailing list currently empty.</h3>';
}
?>

<!-- Footer section -->
<?php include './shared/footer.php' ?>