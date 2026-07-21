<?php
// Original code by Fawn Barisic
// edit existing product
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../includes/db.php';

// page specific values
$page_title = "Edit Product - Shamazon";
$error = '';
$success = '';
require_once __DIR__ . '/../includes/header.php';

// get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    $_SESSION['admin_msg'] = "Invalid product ID.";
    header("Location: /shamazon/admin/products.php");
    exit();
}

// fetch product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    $_SESSION['admin_msg'] = "Product not found.";
    header("Location: /shamazon/admin/products.php");
    exit();
}

// fetch existing options
$stmt = $pdo->prepare("SELECT * FROM product_options WHERE product_id = ?");
$stmt->execute([$product_id]);
$existing_options = $stmt->fetchAll();

// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $base_price = (float)($_POST['base_price'] ?? 0);
    $stock_quantity = (int)($_POST['stock_quantity'] ?? 0);
    $image_url = trim($_POST['image_url'] ?? '');
    
    // validate
    if (empty($title) || empty($author) || empty($description) || empty($category) || $base_price <= 0) {
        $error = 'Please fill in all required fields.';
    } else {
        // update product
        $stmt = $pdo->prepare("UPDATE products SET 
            title = ?, 
            author = ?, 
            description = ?, 
            category = ?, 
            base_price = ?, 
            stock_quantity = ?, 
            image_url = ? 
            WHERE id = ?");
        $stmt->execute([$title, $author, $description, $category, $base_price, $stock_quantity, $image_url, $product_id]);
        
        // delete existing options
        $stmt = $pdo->prepare("DELETE FROM product_options WHERE product_id = ?");
        $stmt->execute([$product_id]);
        
        // reinsert options
        for ($i = 1; $i <= 3; $i++) {
            $opt_name = trim($_POST["option_name_$i"] ?? '');
            $opt_value = trim($_POST["option_value_$i"] ?? '');
            $opt_adj = (float)($_POST["price_adjustment_$i"] ?? 0);
            $opt_stock = (int)($_POST["option_stock_$i"] ?? 0);
            if (!empty($opt_name) && !empty($opt_value)) {
                $stmt = $pdo->prepare("INSERT INTO product_options (product_id, option_name, option_value, price_adjustment, stock_quantity) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$product_id, $opt_name, $opt_value, $opt_adj, $opt_stock]);
            }
        }
        
        $_SESSION['admin_msg'] = "Product updated successfully!";
        header("Location: /shamazon/admin/products.php");
        exit();
    }
}

?>

<section class="admin-form">
    <h1>Edit Product</h1>
    
    <!--error output if any occurs-->
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <!--form containing all product fields (default to existing values)-->
    <form method="POST">
        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Author *</label>
            <input type="text" name="author" value="<?php echo htmlspecialchars($product['author']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Description *</label>
            <textarea name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Category *</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Base Price ($) *</label>
            <input type="number" step="0.01" name="base_price" value="<?php echo $product['base_price']; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" name="stock_quantity" value="<?php echo $product['stock_quantity']; ?>">
        </div>
        
        <div class="form-group">
            <label>Image URL (filename)</label>
            <input type="text" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" placeholder="book.jpg">
            <small>Current: <?php echo htmlspecialchars($product['image_url']) ?: 'None'; ?></small>
        </div>
        
        <h3>Product Options (min 2)</h3>
        <?php 
        // ensure we have at least 3 option slots, using existing data if available
        for ($i = 1; $i <= 3; $i++): 
            $opt = isset($existing_options[$i-1]) ? $existing_options[$i-1] : null;
        ?>
            <div class="option-row">
                <input type="text" name="option_name_<?php echo $i; ?>" 
                       placeholder="e.g. Format" 
                       value="<?php echo $opt ? htmlspecialchars($opt['option_name']) : ''; ?>">
                <input type="text" name="option_value_<?php echo $i; ?>" 
                       placeholder="e.g. Hardcover" 
                       value="<?php echo $opt ? htmlspecialchars($opt['option_value']) : ''; ?>">
                <input type="number" step="0.01" name="price_adjustment_<?php echo $i; ?>" 
                       placeholder="Adjustment" 
                       value="<?php echo $opt ? $opt['price_adjustment'] : '0'; ?>">
                <input type="number" name="option_stock_<?php echo $i; ?>" 
                       placeholder="Stock" 
                       value="<?php echo $opt ? $opt['stock_quantity'] : '0'; ?>">
            </div>
        <?php endfor; ?>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Product</button>
            <a href="/shamazon/admin/products.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>