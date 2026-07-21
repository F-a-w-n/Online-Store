<?php
// Original code by Fawn Barisic
// track order status

require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "Track Order - Shamazon";
$page_desc = "Track your order status.";
$help_page = 'how_to_track';
require_once __DIR__ . '/includes/header.php';

// redirect to login if needed
if (!is_logged_in()) {
    header("Location: /shamazon/login.php");
    exit();
}

// vet valid order id or redirect
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
    header("Location: /shamazon/order-history.php");
    exit();
}

// verify ownership
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

// if order not found show error
if (!$order) {
    echo "<p>Order not found.</p>";
    require_once __DIR__ . '/includes/footer.php';
    exit();
}
?>

<section class="track-order">
    <!--show existing order details by id-->
    <h1>Track Order #<?php echo $order['id']; ?></h1>
    <p><strong>Order Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></p>
    <p><strong>Current Status:</strong> <?php echo ucfirst($order['order_status']); ?></p>
    <p><strong>Shipping Address:</strong> <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
    <?php if ($order['tracking_number']): ?>
        <p><strong>Tracking Number:</strong> <?php echo htmlspecialchars($order['tracking_number']); ?></p>
    <?php else: ?>
        <p><em>No tracking number assigned yet.</em></p>
    <?php endif; ?>
    
    <p><a href="/shamazon/order-history.php">← Back to Order History</a></p>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>