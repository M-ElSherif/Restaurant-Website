<?php
session_start();

// if session variable websiteUser is set and has a value, unset current session variables and destroy
// session. Then start a new session and regenerate the session id
if (isset($_SESSION['websiteUser'])) {
    session_unset();
    session_destroy();
    session_start();
    session_regenerate_id();
}

header('Location:login.php');
?>
