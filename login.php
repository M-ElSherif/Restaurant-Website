<?php
require_once('websiteUser.php');
session_start(); // to access the $_SESSION superglobal

if (isset($_SESSION['AdminID'])) {
    if ($_SESSION['websiteUser']->isAuthenticated()) {  // if the user is already authenticated, take him to mailing list directly
        session_write_close();  // write data in the $_SESSION global variable and writes it to the disk, freeing $_SESSOON to other scripts
        header('Location:mailing_list.php');
    }
}

$missingFields = false;
if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] == "" || $_POST['password'] == "") {
        $missingFields = true;	// there is a missing field in form
    } else {
        // All fields are set and have a value
        $websiteUser = new WebsiteUser();   // create a new website user object
        if (!$websiteUser->hasDbError()) {  // if WebsiteUser object has no error connecting to the database
            $username = $_POST['username'];
            $password = $_POST['password'];
            $websiteUser->authenticate($username, $password);
            if ($websiteUser->isAuthenticated()) {	// if websiteUser is authenticated
                $_SESSION['AdminID'] = $websiteUser->getID();
                $_SESSION['websiteUser'] = $websiteUser;    // if user authenticated, store the user in a variable in $_SESSION array
                header('Location:mailing_list.php');    // take user to mailing_list.php
            }
        }
    }
}

?>

	<!-- Header section of the page -->
<?php include('./shared/header.php'); ?>

	<!-- MESSAGES Error message stating if fields are missing or username and password are not authenticated -->
<?php
// Error message for missing username and password
if ($missingFields) {
    echo '<h3 style="color:red;">Please enter both a username and a password</h3>';
}

// Authentication of username and password failed
if (isset($websiteUser)) {	// if $_SESSION['websiteUser'] not set
    if (!$websiteUser->isAuthenticated()) {
        echo '<h3 style="color:red;">Login failed. Please try again</h3>';
    }
}
?>

<form name="login" id="login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table>
			<tr>
				<td>Username:</td>
				<td><input type="text" name="username" id="username"></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" id="password"></td>
			</tr>
			<tr>
				<td><input type="submit" name="submit" id="submit" value="Log in"></td>
				<td><input type="reset" name="reset" id="reset" value="Reset"></td>
			</tr>
		</table>
</form>

	<!-- Footer section of the page -->
<?php include('./shared/footer.php'); ?>