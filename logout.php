<?php

// start the session
session_start();

// unset all session variables
$_SESSION = [];

// destroy the session
session_destroy();

// user should be redirected to the login form on logout
header('location: hoofdpagina.php');
exit;

?>