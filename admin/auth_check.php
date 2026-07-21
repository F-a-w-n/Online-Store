<?php
// Original code by Fawn Barisic
// ensure only admins can access this area
require_once __DIR__ . '/../includes/functions.php';

start_session_if_needed();

// redirect to login if not logged in
if (!is_logged_in()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: /shamazon/login.php");
    exit();
}

// redirect to homepage if not admin
if (!is_admin()) {
    header("Location: /shamazon/index.php");
    exit();
}
?>