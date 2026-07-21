<?php
// Original code by Fawn Barisic
// universal page header

// Load core functions and session
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
start_session_if_needed();

// Get the current theme
$theme = get_current_theme();

// Determine page title/description (set before including header)
$page_title = isset($page_title) ? $page_title : "Shamazon - Your Online Bookstore";
$page_desc = isset($page_desc) ? $page_desc : "Discover 20+ books with dynamic themes, ratings, and seamless checkout.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!--SEO-->
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_desc; ?>">
    <meta name="keywords" content="bookstore, e-commerce, shamazon, PHP, MySQL, online books, sci-fi, fantasy">
    <meta name="author" content="Shamazon">
    
    <!--favicon-->
    <link rel="icon" href="/shamazon/assets/images/ui/favicon.ico" type="image/x-icon">

    <!--base styles + theme specific styles-->
    <link rel="stylesheet" href="/shamazon/assets/css/style.css">
    <link rel="stylesheet" href="/shamazon/assets/css/theme-<?php echo $theme; ?>.css">
    
    <!--external js-->
    <script src="/shamazon/assets/js/main.js" defer></script>
</head>
<body>
    <header>
        <div class="header-container">
            <!--website logo-->
            <div class="logo">
                <a href="/shamazon/index.php">
                    <img src="/shamazon/assets/images/ui/logo.png" alt="Shamazon Logo" height="50">
                </a>
            </div>
            
            <!-- main navigation menu -->
            <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">☰</button>
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="/shamazon/index.php">Home</a></li>
                    <li><a href="/shamazon/shop.php">Shop</a></li>
                    <li><a href="/shamazon/cart.php">Cart</a></li>
                    
                    <!-- show additional features if logged in, admin, or if not-->
                    <?php if (is_logged_in()): ?>
                        <li><a href="/shamazon/order-history.php">My Orders</a></li>
                        <li><a href="/shamazon/profile.php">Profile</a></li>
                        <?php if (is_admin()): ?>
                            <li><a href="/shamazon/admin/dashboard.php">Admin</a></li>
                        <?php endif; ?>
                        <li><a href="/shamazon/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/shamazon/login.php">Login</a></li>
                        <li><a href="/shamazon/register.php">Register</a></li>
                    <?php endif; ?>
                    
                    <li><a href="/shamazon/about.php">About</a></li>
                </ul>
                <!-- mobile hamburger menu -->
            </nav>
            <div class="context-help">
            <?php if (isset($help_page) && $help_page): ?>
                <a href="/shamazon/help/<?php echo $help_page; ?>.php">Need help? Click here for guidance.</a>
            <?php else: ?>
                <a href="/shamazon/help/index.php">Need help? Click here for guidance.</a>
            <?php endif; ?>
            </div>
        </div>
    </header>
    
    <main>
        <!-- page content below -->