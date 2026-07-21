<?php
// Original code by Fawn Barisic
// about shamazon

// page specific values
$page_title = "About Shamazon - Windsor's Premier Online Bookstore";
$page_desc = "Learn about Shamazon, an advanced e-commerce bookstore with 20+ titles, dynamic themes, and a seamless shopping experience.";
$help_page = 'index';

require_once __DIR__ . '/includes/header.php';
?>

<section class="about-page">
    <!-- hero section -->
    <div class="about-hero">
        <h1>About Shamazon</h1>
        <p class="about-tagline">Your Gateway to Great Reads</p>
        <p>
            <strong>Shamazon</strong> is an advanced e-commerce bookstore that combines an extensive catalog of 20+ titles with a highly interactive, client-server architecture. Each book offers at least two customizable purchase options (e.g., Hardcover, Paperback, or E-book). Built with PHP and MySQL, the platform features three dynamic site themes, a responsive mobile-first design, two interactive HTML forms (search and price estimation), a robust admin panel for product and user management, and a comprehensive 5-page Help Wiki with context-sensitive guidance. From rating products to tracking order history, Shamazon delivers a seamless, modern reading community experience.
        </p>
    </div>

    <!-- features grid -->
    <div class="about-features">
        <div class="feature-card">
            <h3>20+ Curated Titles</h3>
            <p>From sci-fi epics to modern classics, every book includes 2+ format options (Hardcover, Paperback, E-book).</p>
        </div>
        <div class="feature-card">
            <h3>3 Dynamic Themes</h3>
            <p>Switch instantly between Daylight, Midnight, and Sepia themes via the admin or your user profile.</p>
        </div>
        <div class="feature-card">
            <h3>Smart Shopping Tools</h3>
            <p>Use our Advanced Search and real-time Price Estimator (2 dynamic forms) to find the perfect deal.</p>
        </div>
        <div class="feature-card">
            <h3>Admin Control</h3>
            <p>Manage products, view orders, and disable user accounts effortlessly from the secure admin panel.</p>
        </div>
        <div class="feature-card">
            <h3>Community Reviews</h3>
            <p>Rate and review books to help fellow readers discover their next favorite title.</p>
        </div>
        <div class="feature-card">
            <h3>Order Tracking</h3>
            <p>Track your orders from pending to delivered with real-time status updates.</p>
        </div>
    </div>

    <!-- interactive map -->
    <div class="about-map">
        <h3>Visit Our Virtual Hub</h3>
        <p>We're based in Windsor, Ontario, serving book lovers globally.</p>
        <div id="map" class="map-container">
            <!-- Interactive map using Leaflet.js -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <div id="leaflet-map" style="height: 300px; width: 100%; border-radius: 8px;"></div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var map = L.map('leaflet-map').setView([42.3149, -83.0364], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);
                    L.marker([42.3149, -83.0364]).addTo(map)
                        .bindPopup('<b>Shamazon HQ</b><br>Windsor, Ontario')
                        .openPopup();
                });
            </script>
        </div>
    </div>

    <!-- link to help -->
    <div class="help-prompt">
        <p>
            <strong>Need help navigating Shamazon?</strong> Check out our 
            <a href="/shamazon/help/index.php">5-page Help Wiki</a> 
            for step-by-step guides on ordering, tracking, and account management.
        </p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>