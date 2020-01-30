<!--
Contact page
Provides the sign up form for the newsletter, and adds the customer to the mailing list if their input data is valid
-->

<!-- Header Section -->
<?php include('shared/header.php'); ?>

<!-- import the employee Data Access Object class -->
<?php require_once('./dao/customerDAO.php'); ?>

<!-- Body Content Section -->
<div id="content" class="clearfix">
	<aside>
		<h2>Mailing Address</h2>
		<h3>1385 Woodroffe Ave<br>
			Ottawa, ON K4C1A4</h3>
		<h2>Phone Number</h2>
		<h3>(613)727-4723</h3>
		<h2>Fax Number</h2>
		<h3>(613)555-1212</h3>
		<h2>Email Address</h2>
		<h3>info@wpeatery.com</h3>
	</aside>
	<div class="main">
		<h1>Sign up for our newsletter</h1>
		<p>Please fill out the following form to be kept up to date with news, specials, and promotions from the WP
			eatery!</p>

		<!-- PHP code - Customer signup form validation and file upload handling -->
        <?php
        // The abstractDAO and customerDAO will throw exceptions if error occurs connecting to database. Web page insulated
        // in try-catch block so page doesn't load if error occurs and will inform user about error instead

        try {
        $customerDAO = new customerDAO();    // new customer data access objects
        $hasError = false;    // tracks errors with form fields
        $errorMessages = Array();    // Array for our error messages

        //Ensures all customer data is set and is valid when submitted. Code that adds customer to
        // database will run when form is submitted
        if (isset($_POST['customerName']) ||
            isset($_POST['phoneNumber']) ||
            isset($_POST['emailAddress']) ||
            isset($_POST['referral'])) {

            // Store the input field values in variables for ease
            $customerName = $_POST['customerName'];
            $phoneNumber = $_POST['phoneNumber'];
            $emailAddress = $_POST['emailAddress'];
//            $referral = $_POST['referral'];

            // If they are all set, the next step is to validate the values entered by the customer
            // Customer name cant be empty
            if (empty($customerName)) {
                $hasError = true;
                $errorMessages['customerNameError'] = '*Please enter a first name!';
            }
            // Customer phone must be numbers and cant be empty
            if (empty($phoneNumber) || !preg_match("/[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $phoneNumber)) {
                $hasError = true;
                $errorMessages['phoneNumberError'] = '*Please enter a numeric phone number in displayed format!';
            }
            // Email address must be correct format and cant be empty
            if (empty($emailAddress) ||
                !preg_match('/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i', $emailAddress)) {
                $hasError = true;
                $errorMessages['emailAddressError'] = '*Please enter a correct email address!';
            }

            // Email address must not be a duplicate of existing email address in database
            if ($customerDAO->checkDuplicateEmail($emailAddress)) {
                $hasError = true;
                $errorMessages['duplicateEmailError'] = '*This email is already in use!';
            }

            // Referral must be selected from available options
            if (empty($_POST['referral'])) {
                $hasError = true;
                $errorMessages['referralError'] = '*Please select a referral option!';
            }

            // if there are no errors triggered, save the customer data to the database
            if (!$hasError) {
                $customer = new Customer($customerName, $phoneNumber, $emailAddress, $_POST['referral']);
                $addSuccess = $customerDAO->addCustomer($customer);
                echo '<h3>' . $addSuccess . '</h3>';

                // File upload handling. User uploaded file is uploaded to directory 'files'
                // REFERENCE: https://www.php.net/manual/en/features.file-upload.post-method.php
                if (isset($_POST["btnSubmit"])) {
                    $path = 'files/';
                    $uploadFile = $path . basename($_FILES['fileUpload']['name']);    // upload file

                    if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $uploadFile)) {    // if file fails to upload, print error or success message
                        echo "<script>alert('File is valid and was uploaded successfully!');</script>";
                    } else {
                        echo "<script>alert('File upload failed!');</script>";
                    }
                }

            }
        }

        ?>

		<!------- Sign up form for the mailing list ------------->
		<form name="frmNewsletter" id="frmNewsletter" method="post" action="contact.php" enctype="multipart/form-data"> <!-- encode type added to allow for file uploads -->
			<!-- delegated form action to the button -->
			<table>
				<tr>
					<td>Customer Name:</td>
					<td><input type="text" name="customerName" id="customerName" size='40'
							   placeholder="FirstName LastName">
                        <?php
                        if (isset($errorMessages['customerNameError'])) {
                            echo '<span style=\'color:red\'>' . $errorMessages['customerNameError'] . '</span>';
                        }
                        ?></td>
				</tr>
				<tr>
					<td>Phone Number:</td>
					<td><input type="text" name="phoneNumber" id="phoneNumber" size='40' placeholder="XXX-XXX-XXXX">
                        <?php
                        if (isset($errorMessages['phoneNumberError'])) {
                            echo '<span style=\'color:red\'>' . $errorMessages['phoneNumberError'] . '</span>';
                        }
                        ?></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><input type="text" name="emailAddress" id="emailAddress" size='40'
							   placeholder="email@domain.com">
                        <?php
                        if (isset($errorMessages['emailAddressError'])) {
                            echo '<span style=\'color:red\'>' . $errorMessages['emailAddressError'] . '</span>';
                        } elseif (isset($errorMessages['duplicateEmailError'])) {
                            echo '<span style=\'color:red\'>' . $errorMessages['duplicateEmailError'] . '</span>';
                        }
                        ?></td>
				</tr>
				<tr>
					<td>How did you hear<br> about us?</td>
					<td>Newspaper<input type="radio" name="referral" id="referralNewspaper" value="newspaper">
						Radio<input type="radio" name='referral' id='referralRadio' value='radio'>
						TV<input type='radio' name='referral' id='referralTV' value='TV'>
						Other<input type='radio' name='referral' id='referralOther' value='other'>
                        <?php
                        if (isset($errorMessages['referralError'])) {
                            echo '<span style=\'color:red\'>' . $errorMessages['referralError'] . '</span>';
                        }
                        ?></td>
				</tr>
				<tr>
					<td>File Upload:</td> <!-- File Upload here -->
					<td><input type="file" name="fileUpload" id="fileUpload" value="Open File">&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td colspan='2'><input type='submit' name='btnSubmit' id='btnSubmit' value='Sign up!'>&nbsp;&nbsp;&nbsp;&nbsp;
						<input
								type='reset'
								name="btnReset"
								id="btnReset"
								value="Reset Form">
					</td>
				</tr>
			</table>
		</form>

	</div><!-- End Main -->
</div><!-- End Content -->

<!-- PHP 'catch' code for the try-catch -->
<?php
} catch (Exception $e) {
    echo '<h3>Error on page.</h3>';
    echo '<p>' . $e->getMessage() . '</p>';
}
?>

<!-- Footer Section -->
<?php include('shared/footer.php'); ?>