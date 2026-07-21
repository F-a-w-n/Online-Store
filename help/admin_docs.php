<?php
// Original code by Fawn Barisic
// help page for admin panels

// page specific values
$page_title = "Admin Guide - Shamazon";
$page_desc = "Admin user documentation.";
$help_page = 'index';
require_once __DIR__ . '/../includes/header.php';

?>
<section class="help-page">
    <!--more in depth docs, split into sections for all admin tasks-->
    <h1>Admin Guide</h1>
    <p>This guide is for store administrators.</p>
    <h2>Managing Products</h2>
    <ul>
        <li>Go to <strong>Admin → Manage Products</strong>.</li>
        <li>You can <strong>Add</strong>, <strong>Edit</strong>, or <strong>Delete</strong> products and their options.</li>
    </ul>
    <h2>Managing Orders</h2>
    <ul>
        <li>Go to <strong>Admin → View Orders</strong>.</li>
        <li>Update order status (Pending → Processing → Shipped → Delivered).</li>
        <li>Add a tracking number for shipped orders.</li>
    </ul>
    <h2>Managing Users</h2>
    <ul>
        <li>Go to <strong>Admin → Manage Users</strong>.</li>
        <li>Enable or disable user accounts.</li>
    </ul>
    <h2>Switching Themes</h2>
    <ul>
        <li>Go to <strong>Admin → Switch Themes</strong>.</li>
        <li>Choose the default theme (Day, Night, Sepia) for all visitors.</li>
    </ul>
    <h2>Monitoring the Site</h2>
    <ul>
        <li>Go to <strong>Admin → Site Monitor</strong>.</li>
        <li>Check the status of database, sessions, files, and CSS themes.</li>
    </ul>
    <p><a href="/shamazon/help/index.php">← Back to Help Center</a></p>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>