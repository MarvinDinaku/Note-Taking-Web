<?php

//End all sessions and destroy them and redirect user to login page (Index.php)
session_start();
$_SESSION['userId'] = null;
$_SESSION = array();
session_destroy();
unset($_SESSION['userId']);
unset($_SESSION['message']);
header("Location:../../users/frontend/login_form.php");

?>