<?php

// Header section
include('./shared/header.php');

// Establish connection to database and import customer Data Access Object class
require_once('./dao/customerDAO.php');

$customerDAO = new customerDAO();    // initialize new customer Data Access Object
$customers = $customerDAO->getCustomers();    // get customers in the database and store in an array

// if URL parameter customer Id is set, store it in variable
if (isset($_GET['customerId'])) {
    $id = $_GET['customerId'];    // stores the customer id from URL parameter into a variable
}

// check that the customerId URL parameter is set in order to retrieve its value
// if URL parameter not set, return the user back to the mailing list page
if (!isset($_GET['customerId']) || !is_numeric($_GET['customerId'])) {
    header("Location: contact.php");
    exit;
} else {
    $customerDAO = new customerDAO();
    $customer = $customerDAO->getCustomer($_GET['customerId']);

    if ($customer) { // if customer object is assigned from the URL parameter, display customer data values in form fields
        ?>
		<form method="post" action"">
		<table>
			<tr>
				<td>Customer ID:</td>
				<td><input type="hidden" name="customerId" id="customerId" value="<?php echo $id; ?>"><?php echo $id; ?>
				</td>
			</tr>
			<tr>
				<td>Customer Name:</td>
				<td><input type="text" name="customerName" id="customerName"
						   value="<?php echo $customer->getCustomerName() ?>"></td>
			</tr>
			<tr>
				<td>Customer Phone Number:</td>
				<td><input type="text" name="phoneNumber" id="phoneNumber"
						   value="<?php echo $customer->getPhoneNumber() ?>"></td>
			</tr>
			<tr>
				<td>Customer Email:</td>
				<td><input type="text" name="emailAddress" id="emailAddress"
						   value="<?php echo $customer->getEmailAddress() ?>"></td>
			</tr>
			<tr>
				<td>Referral:</td>
				<td>Newspaper<input type="radio" name="referral" id="referralNewspaper"
									value="newspaper" <?php if ($customer->getReferrer() == 'newspaper') echo 'checked'; ?> >
					Radio<input type="radio" name='referral' id='referralRadio'
								value='radio'<?php if ($customer->getReferrer() == 'radio') echo 'checked'; ?> >
					TV<input type='radio' name='referral' id='referralTV'
							 value='TV' <?php if ($customer->getReferrer() == 'TV') echo 'checked'; ?> >
					Other<input type='radio' name='referral' id='referralOther'
								value='other'<?php if ($customer->getReferrer() == 'other') echo 'checked'; ?> >
				</td>
			</tr>
			<tr>
				<td colspan='2'><input type='submit' name='btnSubmit' id='btnSubmit' value='Update'
									   formaction="process_Customer.php?action=edit&customerId=<?php echo $id; ?>">&nbsp;&nbsp;&nbsp;&nbsp;
					<input type='submit' name='btnSubmit' id='btnSubmit' value='Delete'
						   formaction="process_Customer.php?action=delete&customerId=<?php echo $id; ?>"></td>
			</tr>
		</table>
		</form>

		<!-- URL parameter error handling -->
        <?php
    } else {    // if customer variable does not get a value from the URL, return to contact page
        header("Location: contact.php");
        exit;
    }
}
?>

	<!-- Footer section -->
<?php
// Footer section
include('./shared/footer.php')
?>