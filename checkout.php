<?php
// Original code by Fawn Barisic
// checkout page

require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "Checkout - Shamazon";
$page_desc = "Complete your order.";
$help_page = 'how_to_shop';
$error = '';
$success = false;
require_once __DIR__ . '/includes/header.php';

// ensure user is logged in
if (!is_logged_in()) {
    $_SESSION['redirect_after_login'] = '/shamazon/checkout.php';
    header("Location: /shamazon/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// get cart items
$cart_items = get_cart_items($pdo);
$cart_total = get_cart_total($pdo);

// redirect if cart empty
if (empty($cart_items)) {
    header("Location: /shamazon/cart.php");
    exit();
}

// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = trim($_POST['shipping_address'] ?? '');
    if (empty($shipping_address)) {
        $error = 'Please enter your shipping address.';
    } else {
        // begin transaction
        $pdo->beginTransaction();
        try {
            // insert order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, order_status) VALUES (?, ?, ?, 'pending')");
            $stmt->execute([$user_id, $cart_total, $shipping_address]);
            $order_id = $pdo->lastInsertId();
            
            // insert order items
            foreach ($cart_items as $item) {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, option_id, quantity, price_at_time) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $order_id,
                    $item['product']['id'],
                    $item['option'] ? $item['option']['id'] : null,
                    $item['quantity'],
                    $item['price']
                ]);
            }
            
            // clear the cart
            clear_cart();
            
            $pdo->commit();
            $success = true;
            $_SESSION['last_order_id'] = $order_id;
            header("Location: /shamazon/order-confirmation.php?id=" . $order_id);
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Order failed: ' . $e->getMessage();
        }
    }
}
?>

<section class="checkout-page">
    <h1>Checkout</h1>
    <!--error output-->
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <!--lists details for each item-->
    <div class="checkout-summary">
        <h2>Order Summary</h2>
        <table>
            <thead>
                <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product']['title']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr><td colspan="2"><strong>Total</strong></td><td><strong>$<?php echo number_format($cart_total, 2); ?></strong></td></tr>
            </tfoot>
        </table>
    </div>
    
    <!--form to handle shipping info and order placement-->
    <form method="POST" class="checkout-form">
        <div class="form-group">
            <label for="shipping_address">Shipping Address</label>
            <textarea name="shipping_address" id="shipping_address" rows="3" required><?php echo htmlspecialchars($_SESSION['shipping_address'] ?? ''); ?></textarea>
        </div>
        
        <button type="submit" class="btn-primary">Place Order</button>
    </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>