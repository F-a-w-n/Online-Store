<?php
// Original code by Fawn Barisic
// switch site templates
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../includes/db.php';

// page specific values
$help_page = 'admin_docs';
$page_title = "Site Themes - Shamazon";
$error = '';
$success = '';
require_once __DIR__ . '/../includes/header.php';

// set new theme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theme = $_POST['default_theme'] ?? 'day';
    if (in_array($theme, ['day', 'night', 'sepia'])) {
        update_setting($pdo, 'default_theme', $theme);
        $success = "Theme updated to " . ucfirst($theme) . "!";
    } else {
        $error = "Invalid theme selected.";
    }
}

// get current theme and make it nice
$current_theme = get_setting($pdo, 'default_theme', 'day');
$themes = [
    'day' => ['label' => 'Daylight', 'desc' => 'Bright, clean, and modern.'],
    'night' => ['label' => 'Midnight', 'desc' => 'Dark mode for late-night browsing.'],
    'sepia' => ['label' => 'Vintage', 'desc' => 'Warm, classic paper feel.']
];

?>

<section class="admin-themes">
    <h1>Site-Wide Theme Switcher</h1>
    <p>Choose the default theme for all visitors. Users can still override this in their session.</p>
    <!--result-->
    <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
    
    <!--form to set new theme-->
    <form method="POST">
        <div class="theme-options">
            <?php foreach ($themes as $key => $theme): ?>
                <label class="theme-option <?php echo $key === $current_theme ? 'selected' : ''; ?>">
                    <input type="radio" name="default_theme" value="<?php echo $key; ?>" <?php echo $key === $current_theme ? 'checked' : ''; ?>>
                    <div class="theme-preview" style="background: <?php echo $key === 'day' ? '#fff' : ($key === 'night' ? '#1a1a2e' : '#f4ecd8'); ?>; color: <?php echo $key === 'day' ? '#212529' : ($key === 'night' ? '#e0e0e0' : '#5b4637'); ?>; padding: 20px; border-radius: 8px; border: 2px solid <?php echo $key === $current_theme ? '#007bff' : '#ccc'; ?>;">
                        <h3><?php echo $theme['label']; ?></h3>
                        <p><?php echo $theme['desc']; ?></p>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn-primary">Apply Theme</button>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>