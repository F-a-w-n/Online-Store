<?php
// Original code by Fawn Barisic
// custom helper functions for Shamazon

// start session if it hasn't been started yet

function start_session_if_needed() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// check if user logged in

function is_logged_in() {
    start_session_if_needed();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// check if user is admin

function is_admin() {
    start_session_if_needed();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// redirect to a specific page

function redirect($url) {
    header("Location: " . $url);
    exit();
}

// sanitize user input from XSS

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// get product options from the database

function get_product_options($pdo, $product_id) {
    $stmt = $pdo->prepare("SELECT * FROM product_options WHERE product_id = ?");
    $stmt->execute([$product_id]);
    return $stmt->fetchAll();
}

// calculate final price: base price + option adjustment

function calculate_price($base_price, $adjustment) {
    return number_format($base_price + $adjustment, 2);
}

// add a product to cart

function add_to_cart($product_id, $option_id = null, $quantity = 1) {
    start_session_if_needed();
    
    // initialize cart array if needed
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // create a unique key for this product+option combination
    $key = $product_id . '_' . ($option_id ?? '0');
    
    if (isset($_SESSION['cart'][$key])) {
        // Increment quantity
        $_SESSION['cart'][$key]['quantity'] += $quantity;
    } else {
        // add new item
        $_SESSION['cart'][$key] = [
            'product_id' => $product_id,
            'option_id' => $option_id,
            'quantity' => $quantity
        ];
    }
}

// get all cart items from the database

function get_cart_items($pdo) {
    start_session_if_needed();
    
    if (empty($_SESSION['cart'])) {
        return [];
    }
    
    $items = [];
    foreach ($_SESSION['cart'] as $key => $cart_item) {
        $product_id = $cart_item['product_id'];
        $option_id = $cart_item['option_id'];
        $quantity = $cart_item['quantity'];
        
        // fetch product details
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        if (!$product) continue;
        
        // fetch option details
        $option = null;
        $price = $product['base_price'];
        if ($option_id) {
            $stmt = $pdo->prepare("SELECT * FROM product_options WHERE id = ?");
            $stmt->execute([$option_id]);
            $option = $stmt->fetch();
            if ($option) {
                $price += $option['price_adjustment'];
            }
        }
        
        $items[] = [
            'key' => $key,
            'product' => $product,
            'option' => $option,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $price * $quantity
        ];
    }
    
    return $items;
}

// calculate cart total

function get_cart_total($pdo) {
    $items = get_cart_items($pdo);
    $total = 0;
    foreach ($items as $item) {
        $total += $item['subtotal'];
    }
    return $total;
}

// update cart quantity for an item

function update_cart_quantity($key, $quantity) {
    start_session_if_needed();
    if (isset($_SESSION['cart'][$key])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$key]);
        } else {
            $_SESSION['cart'][$key]['quantity'] = $quantity;
        }
        return true;
    }
    return false;
}

// remove an item from cart

function remove_from_cart($key) {
    start_session_if_needed();
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]);
        return true;
    }
    return false;
}

// clear the entire cart

function clear_cart() {
    start_session_if_needed();
    unset($_SESSION['cart']);
}

// get a site setting from the database

function get_setting($pdo, $key, $default = null) {
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    } catch (PDOException $e) {
        return $default;
    }
}

// update a site setting

function update_setting($pdo, $key, $value) {
    try {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$key, $value, $value]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// get current theme

function get_current_theme() {
    start_session_if_needed();
    $valid_themes = ['day', 'night', 'sepia'];
    
    if (isset($_SESSION['theme']) && in_array($_SESSION['theme'], $valid_themes)) {
        return $_SESSION['theme'];
    }
    
    // try to get from DB if $pdo exists in global scope
    if (isset($GLOBALS['pdo']) && $GLOBALS['pdo'] instanceof PDO) {
        $pdo = $GLOBALS['pdo'];
        $default = get_setting($pdo, 'default_theme', 'day');
        return in_array($default, $valid_themes) ? $default : 'day';
    }
    
    return 'day';
}

// get order status badge HTML

function get_status_badge($status) {
    $colors = [
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger'
    ];
    $color = $colors[$status] ?? 'secondary';
    return '<span class="badge badge-' . $color . '">' . ucfirst($status) . '</span>';
}

?>