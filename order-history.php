<?php
// Original code by Fawn Barisic
// order history for user

require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "My Orders - Shamazon";
$page_desc = "View your past orders.";
$help_page = 'how_to_track';
require_once __DIR__ . '/includes/header.php';

// redirect to home if not logged in
if (!is_logged_in()) {
    header("Location: /shamazon/login.php");
    exit();
}

// fetch user id
$user_id = $_SESSION['user_id'];

// fetch all orders for this user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<section class="order-history">
    <h1>My Orders</h1>
    
    <!--no orders message-->
    <?php if (empty($orders)): ?>
        <p>You haven't placed any orders yet. <a href="/shamazon/index.php">Start shopping</a>.</p>
    <?php else: ?>
        <!--table of all order details-->
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo ucfirst($order['order_status']); ?></td>
                        <td><a href="/shamazon/track-order.php?id=<?php echo $order['id']; ?>">Track</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>