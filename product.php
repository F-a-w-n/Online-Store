<?php
// Original code by Fawn Barisic
// product detail page

require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "Product Details - Shamazon";
$page_desc = "View book details, add to cart, and leave ratings.";
$help_page = 'how_to_shop';
require_once __DIR__ . '/includes/header.php';

// get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    header("Location: /shamazon/index.php");
    exit();
}

// fetch product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

// product not found error
if (!$product) {
    echo "<p>Product not found.</p>";
    require_once __DIR__ . '/includes/footer.php';
    exit();
}

// fetch product options
$stmt = $pdo->prepare("SELECT * FROM product_options WHERE product_id = ?");
$stmt->execute([$product_id]);
$options = $stmt->fetchAll();

// handle add to cart POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $option_id = isset($_POST['option_id']) ? (int)$_POST['option_id'] : null;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($quantity < 1) $quantity = 1;
    
    add_to_cart($product_id, $option_id, $quantity);
    
    $_SESSION['cart_message'] = "Added " . htmlspecialchars($product['title']) . " to cart!";
    header("Location: /shamazon/cart.php");
    exit();
}

// handle rating submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rating'])) {
    // check if user is logged in
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = '/shamazon/product.php?id=' . $product_id;
        header("Location: /shamazon/login.php");
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $rating = (int)($_POST['rating'] ?? 0);
    $review = trim($_POST['review'] ?? '');
    
    if ($rating < 1 || $rating > 5) {
        $rating_error = 'Please select a rating from 1 to 5 stars.';
    } else {
        // check if user already rated this product
        $stmt = $pdo->prepare("SELECT id FROM ratings WHERE product_id = ? AND user_id = ?");
        $stmt->execute([$product_id, $user_id]);
        if ($stmt->fetch()) {
            // update existing rating
            $stmt = $pdo->prepare("UPDATE ratings SET rating = ?, review = ? WHERE product_id = ? AND user_id = ?");
            $stmt->execute([$rating, $review, $product_id, $user_id]);
            $rating_success = 'Your rating has been updated!';
        } else {
            // insert new rating
            $stmt = $pdo->prepare("INSERT INTO ratings (product_id, user_id, rating, review) VALUES (?, ?, ?, ?)");
            $stmt->execute([$product_id, $user_id, $rating, $review]);
            $rating_success = 'Thank you for your rating!';
        }
    }
}

// fetch ratings for this product
$stmt = $pdo->prepare("
    SELECT r.*, u.full_name, u.email 
    FROM ratings r
    JOIN users u ON r.user_id = u.id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$product_id]);
$ratings = $stmt->fetchAll();

// calculate average rating
$avg_rating = 0;
$total_ratings = count($ratings);
if ($total_ratings > 0) {
    $sum = array_sum(array_column($ratings, 'rating'));
    $avg_rating = round($sum / $total_ratings, 1);
}

// get user's existing rating (if logged in)
$user_rating = null;
if (is_logged_in()) {
    $stmt = $pdo->prepare("SELECT * FROM ratings WHERE product_id = ? AND user_id = ?");
    $stmt->execute([$product_id, $_SESSION['user_id']]);
    $user_rating = $stmt->fetch();
}
?>

<section class="product-detail">
    <!--grabs image from assets folder-->
    <div class="product-image">
        <img src="/shamazon/assets/images/products/<?php echo $product['image_url']; ?>" 
             alt="<?php echo htmlspecialchars($product['title']); ?>"
             onerror="this.src='/shamazon/assets/images/ui/placeholder.jpg'">
    </div>
    
    <!--lists book info-->
    <div class="product-info">
        <h1><?php echo htmlspecialchars($product['title']); ?></h1>
        <p class="author">by <?php echo htmlspecialchars($product['author']); ?></p>
        <p class="category"><?php echo htmlspecialchars($product['category']); ?></p>
        
        <!-- rating summary -->
        <div class="rating-summary">
            <span class="stars">
                <?php
                $full_stars = floor($avg_rating);
                $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $full_stars) {
                        echo '★';
                    } elseif ($i == $full_stars + 1 && $half_star) {
                        echo '⯪';
                    } else {
                        echo '☆';
                    }
                }
                ?>
            </span>
            <span class="rating-text">
                <?php echo number_format($avg_rating, 1); ?> / 5 
                (<?php echo $total_ratings; ?> reviews)
            </span>
        </div>
        
        <p class="description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        <p class="base-price">Price: $<?php echo number_format($product['base_price'], 2); ?></p>
        
        <!--confirms stock-->
        <?php if ($product['stock_quantity'] > 0): ?>
            <p class="in-stock">In Stock (<?php echo $product['stock_quantity']; ?> available)</p>
        <?php else: ?>
            <p class="out-of-stock">Out of Stock</p>
        <?php endif; ?>
        
        <!-- add to cart form -->
        <?php if ($product['stock_quantity'] > 0): ?>
            <form method="POST" class="add-to-cart-form">
                <?php if (count($options) > 0): ?>
                    <div class="form-group">
                        <label for="option_id">Choose Format:</label>
                        <select name="option_id" id="option_id" required>
                            <?php foreach ($options as $opt): ?>
                                <option value="<?php echo $opt['id']; ?>">
                                    <?php echo htmlspecialchars($opt['option_value']); ?> 
                                    (+$<?php echo number_format($opt['price_adjustment'], 2); ?>)
                                    <?php if ($opt['stock_quantity'] > 0): ?>
                                        (<?php echo $opt['stock_quantity']; ?> available)
                                    <?php else: ?>
                                        (Out of Stock)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="option_id" value="">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                </div>
                
                <button type="submit" name="add_to_cart" class="btn-primary">Add to Cart</button>
            </form>
        <?php endif; ?>
        
        <div class="product-actions">
            <a href="/shamazon/shop.php">← Back to Shop</a>
        </div>
    </div>
</section>

<!-- rating section -->
<section class="rating-section">
    <h2>Rate This Book</h2>
    
    <!-- confirm user logged in -->
    <?php if (is_logged_in()): ?>
        <?php if (isset($rating_success)): ?>
            <div class="success"><?php echo $rating_success; ?></div>
        <?php endif; ?>
        <?php if (isset($rating_error)): ?>
            <div class="error"><?php echo $rating_error; ?></div>
        <?php endif; ?>
        
        <!--form for logged in users to rate-->
        <form method="POST" class="rating-form">
            <div class="form-group">
                <label>Your Rating:</label>
                <div class="star-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" name="rating" value="<?php echo $i; ?>" 
                            id="star<?php echo $i; ?>"
                            <?php echo ($user_rating && $user_rating['rating'] == $i) ? 'checked' : ''; ?>>
                        <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> stars">★</label>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="review">Your Review:</label>
                <textarea name="review" id="review" rows="3" placeholder="Share your thoughts about this book..."><?php 
                    echo $user_rating ? htmlspecialchars($user_rating['review']) : ''; 
                ?></textarea>
            </div>
            
            <button type="submit" name="submit_rating" class="btn-primary">
                <?php echo $user_rating ? 'Update Rating' : 'Submit Rating'; ?>
            </button>
        </form>
    <?php else: ?>
        <p><a href="/shamazon/login.php">Log in</a> to rate this product.</p>
    <?php endif; ?>
    
    <!-- display existing ratings list -->
    <?php if ($total_ratings > 0): ?>
        <div class="ratings-list">
            <h3>Customer Reviews (<?php echo $total_ratings; ?>)</h3>
            <?php foreach ($ratings as $review): ?>
                <div class="rating-item">
                    <div class="rating-header">
                        <strong><?php echo htmlspecialchars($review['full_name']); ?></strong>
                        <span class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php echo $i <= $review['rating'] ? '★' : '☆'; ?>
                            <?php endfor; ?>
                        </span>
                        <span class="rating-date">
                            <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                        </span>
                    </div>
                    <?php if (!empty($review['review'])): ?>
                        <p class="rating-review"><?php echo nl2br(htmlspecialchars($review['review'])); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!--no reviews case-->
        <p class="no-ratings">No reviews yet. Be the first to rate this book!</p>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>