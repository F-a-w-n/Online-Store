<?php
// Original code by Fawn Barisic
// manage orders

require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

// page specific values
$page_title = "Manage Orders - Shamazon";
$help_page = 'admin_docs';

// handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['order_status'];
    $tracking = trim($_POST['tracking_number'] ?? '');
    
    $stmt = $pdo->prepare("UPDATE orders SET order_status = ?, tracking_number = ? WHERE id = ?");
    $stmt->execute([$status, $tracking, $order_id]);
    $_SESSION['admin_msg'] = "Order #$order_id updated.";
    header("Location: /shamazon/admin/orders.php");
    exit();
}

// list of all orders
$orders = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC")->fetchAll();

if (isset($_SESSION['admin_msg'])) {
    echo '<div class="success">' . $_SESSION['admin_msg'] . '</div>';
    unset($_SESSION['admin_msg']);
}
?>

<section class="admin-orders">
    <h1>All Orders</h1>
    
    <!--lists all orders and their details-->
    <?php foreach ($orders as $order): ?>
        <div class="order-card">
            <h3>Order #<?php echo $order['id']; ?></h3>
            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['order_date'])); ?></p>
            <p><strong>User ID:</strong> <?php echo $order['user_id']; ?></p>
            <p><strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
            <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
            
            <!--form to update an order status, tracking, etc.-->
            <form method="POST">
                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                <div class="form-group">
                    <label>Status</label>
                    <select name="order_status">
                        <option value="pending" <?php echo $order['order_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="processing" <?php echo $order['order_status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                        <option value="shipped" <?php echo $order['order_status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                        <option value="delivered" <?php echo $order['order_status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                        <option value="cancelled" <?php echo $order['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tracking Number</label>
                    <input type="text" name="tracking_number" value="<?php echo htmlspecialchars($order['tracking_number'] ?? ''); ?>">
                </div>
                <button type="submit" name="update_order" class="btn-small">Update Order</button>
            </form>
        </div>
        <hr>
    <?php endforeach; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>