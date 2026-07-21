<?php
// Original code by Fawn Barisic
// order confirmation
require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "Order Confirmation - Shamazon";
$page_desc = "Thank you for your order!";
$help_page = 'how_to_track';
require_once __DIR__ . '/includes/header.php';

// checks order id and redirects to home if invalid
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
    header("Location: /shamazon/index.php");
    exit();
}

// fetch order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

// order not found error
if (!$order) {
    echo "<p>Order not found.</p>";
    require_once __DIR__ . '/includes/footer.php';
    exit();
}

// fetch order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.title, po.option_value 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    LEFT JOIN product_options po ON oi.option_id = po.id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>

<section class="order-confirmation">
    <h1>Thank You for Your Order!</h1>
    <!--order details-->
    <p>Your order has been placed successfully.</p>
    <p><strong>Order #:</strong> <?php echo $order['id']; ?></p>
    <p><strong>Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></p>
    <p><strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
    <p><strong>Shipping Address:</strong> <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
    <p><strong>Status:</strong> <?php echo ucfirst($order['order_status']); ?></p>
    
    <!--lists item details-->
    <h2>Order Items</h2>
    <table>
        <thead>
            <tr><th>Product</th><th>Format</th><th>Qty</th><th>Price</th></tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td><?php echo $item['option_value'] ?? 'Default'; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price_at_time'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <p><a href="/shamazon/order-history.php">View All Orders</a> | <a href="/shamazon/index.php">Continue Shopping</a></p>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>