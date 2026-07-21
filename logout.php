<?php
// Original code by Fawn Barisic
// log out user

// clears session
session_start();
session_destroy();

// redirect to home page
header("Location: /shamazon/index.php");
exit();
?>