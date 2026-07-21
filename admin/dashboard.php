<?php
// Original code by Fawn Barisic
// admin dashboard
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../includes/db.php';

// sets page-specific values
$help_page = 'admin_docs';
$page_title = "Admin Dashboard - Shamazon";
$page_desc = "Manage your bookstore.";

// get stats
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pending_orders = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// include header
require_once __DIR__ . '/../includes/header.php';
?>

<section class="admin-dashboard">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h1>
    
    <!-- lists site details -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Products</h3>
            <p class="stat-number"><?php echo $total_products; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Orders</h3>
            <p class="stat-number"><?php echo $total_orders; ?></p>
        </div>
        <div class="stat-card">
            <h3>Pending Orders</h3>
            <p class="stat-number"><?php echo $pending_orders; ?></p>
        </div>
        <div class="stat-card">
            <h3>Users</h3>
            <p class="stat-number"><?php echo $total_users; ?></p>
        </div>
    </div>
    
    <!-- links to other admin pages -->
    <div class="admin-menu">
        <h2>Admin Tools</h2>
        <ul>
            <li><a href="/shamazon/admin/products.php">Manage Products</a></li>
            <li><a href="/shamazon/admin/orders.php">View Orders</a></li>
            <li><a href="/shamazon/admin/users.php">Manage Users</a></li>
            <li><a href="/shamazon/admin/templates.php">Switch Themes</a></li>
            <li><a href="/shamazon/admin/monitor.php">Site Monitor</a></li>
        </ul>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>