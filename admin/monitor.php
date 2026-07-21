<?php
// Original code by Fawn Barisic
// site monitoring
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../includes/db.php';

// page specific values
$help_page = 'admin_docs';
$page_title = "Site Monitor - Shamazon";
$checks = [];

// database connection
try {
    $pdo->query("SELECT 1");
    $checks['Database'] = ['status' => 'online', 'detail' => 'Connection successful.'];
} catch (PDOException $e) {
    $checks['Database'] = ['status' => 'offline', 'detail' => $e->getMessage()];
}

// database tables
$tables = ['users', 'products', 'orders', 'settings'];
foreach ($tables as $table) {
    try {
        $pdo->query("SELECT COUNT(*) FROM $table");
        $checks["Table: $table"] = ['status' => 'online', 'detail' => 'Table exists and accessible.'];
    } catch (PDOException $e) {
        $checks["Table: $table"] = ['status' => 'offline', 'detail' => 'Missing or corrupted.'];
    }
}

// sessions
$checks['Sessions'] = (session_status() === PHP_SESSION_ACTIVE) 
    ? ['status' => 'online', 'detail' => 'Sessions are active.'] 
    : ['status' => 'offline', 'detail' => 'Sessions not active.'];

// file system check /assets/
$folders = ['/assets/images/', '/assets/css/', '/includes/'];
foreach ($folders as $folder) {
    $path = $_SERVER['DOCUMENT_ROOT'] . '/shamazon' . $folder;
    if (is_dir($path)) {
        $checks["Folder: $folder"] = ['status' => 'online', 'detail' => 'Writable and accessible.'];
    } else {
        $checks["Folder: $folder"] = ['status' => 'offline', 'detail' => 'Folder missing.'];
    }
}

// theme files
$themes = ['theme-day.css', 'theme-night.css', 'theme-sepia.css'];
foreach ($themes as $css) {
    $path = $_SERVER['DOCUMENT_ROOT'] . '/shamazon/assets/css/' . $css;
    if (file_exists($path)) {
        $checks["CSS: $css"] = ['status' => 'online', 'detail' => 'Found.'];
    } else {
        $checks["CSS: $css"] = ['status' => 'offline', 'detail' => 'Missing.'];
    }
}

// overall status
$all_online = true;
foreach ($checks as $check) {
    if ($check['status'] === 'offline') {
        $all_online = false;
        break;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="admin-monitor">
    <h1>Site Monitoring Dashboard</h1>
    <div class="overall-status <?php echo $all_online ? 'online' : 'offline'; ?>">
        <?php echo $all_online ? 'All systems operational' : 'Some services are offline'; ?>
    </div>
    
    <table>
        <thead>
            <tr><th>Service</th><th>Status</th><th>Details</th></tr>
        </thead>
        <tbody>
            <!--lists all services and status values-->
            <?php foreach ($checks as $service => $data): ?>
                <tr>
                    <td><?php echo $service; ?></td>
                    <td>
                        <span class="badge badge-<?php echo $data['status'] === 'online' ? 'success' : 'danger'; ?>">
                            <?php echo ucfirst($data['status']); ?>
                        </span>
                    </td>
                    <td><?php echo $data['detail']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>