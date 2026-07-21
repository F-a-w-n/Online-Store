<?php
// Original code by Fawn Barisic
// shopping cart page

require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "Your Cart - Shamazon";
$page_desc = "Review items in your shopping cart.";
$help_page = 'how_to_shop';
require_once __DIR__ . '/includes/header.php';

// process cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update':
            // get the key from the POST data
            $key = $_POST['cart_key'] ?? '';
            $quantity = (int)($_POST['quantity'] ?? 0);
            if (!empty($key) && $quantity > 0) {
                update_cart_quantity($key, $quantity);
            }
            break;
            
        case 'remove':
            $key = $_POST['cart_key'] ?? '';
            if (!empty($key)) {
                remove_from_cart($key);
            }
            break;
            
        case 'clear':
            clear_cart();
            break;
    }
    
    // redirect to avoid form resubmission
    header("Location: /shamazon/cart.php");
    exit();
}

// get cart items with full details
$cart_items = get_cart_items($pdo);
$cart_total = get_cart_total($pdo);
?>

<section class="cart-page">
    <h1>Your Shopping Cart</h1>
    
    <!--result of cart action-->
    <?php if (isset($_SESSION['cart_message'])): ?>
        <div class="success"><?php echo $_SESSION['cart_message']; unset($_SESSION['cart_message']); ?></div>
    <?php endif; ?>
    
    <!--handles empty/filled cart-->
    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty. <a href="/shamazon/index.php">Start shopping</a>.</p>
    <?php else: ?>
        <!--puts cart items in a form to edit the order-->
        <form method="POST" id="cart-form">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Format</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <a href="/shamazon/product.php?id=<?php echo $item['product']['id']; ?>">
                                    <?php echo htmlspecialchars($item['product']['title']); ?>
                                </a>
                            </td>
                            <td><?php echo $item['option'] ? htmlspecialchars($item['option']['option_value']) : 'Default'; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <!-- each item has its own quantity input with unique name -->
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99" class="qty-input">
                                <input type="hidden" name="cart_key" value="<?php echo htmlspecialchars($item['key']); ?>">
                            </td>
                            <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                            <td>
                                <!-- update button for THIS row -->
                                <button type="submit" name="action" value="update" class="btn-small">Update</button>
                                <!-- remove button for THIS row -->
                                <button type="submit" name="action" value="remove" class="btn-small btn-danger">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: bold;">Total:</td>
                        <td colspan="2" style="font-weight: bold; font-size: 1.4rem;">$<?php echo number_format($cart_total, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
            
            <!--clear/checkout buttons-->
            <div class="cart-actions">
                <button type="submit" name="action" value="clear" class="btn-secondary" onclick="return confirm('Clear all items?');">Clear Cart</button>
                <a href="/shamazon/checkout.php" class="btn-primary">Proceed to Checkout</a>
            </div>
        </form>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>