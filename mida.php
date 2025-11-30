<?php
/**
 * Plugin Name: Mida
 * Plugin URI: https://xudiyev.com
 * Description: MIDA oyunu üçün lazım olan hər şey
 * Version: 1.4.1
 * Author: difteriya
 * Author URI: https://xudiyev.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mida
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('MIDA_VERSION', '1.4.1');
define('MIDA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MIDA_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include Plugin Update Checker
require MIDA_PLUGIN_DIR . 'lib/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Set up automatic updates from GitHub
global $myUpdateChecker;
$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/difteriya/MDAWPPlugin/',
    __FILE__,
    'mida'
);

// Optional: Set the branch that contains the stable release
$myUpdateChecker->setBranch('main');

// Optional: If your GitHub repo is private, specify a GitHub API token
// $myUpdateChecker->setAuthentication('your-token-here');

/**
 * The code that runs during plugin activation.
 */
function activate_mida() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    // Submissions table
    $table_name = $wpdb->prefix . 'mida_submissions';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        selection_time_ms int(11) NOT NULL,
        selection_time_display varchar(50) NOT NULL,
        layihe varchar(100) DEFAULT NULL,
        odenish_usulu varchar(100) DEFAULT NULL,
        mertebe varchar(50) DEFAULT NULL,
        otaq_sayi varchar(50) DEFAULT NULL,
        has_warning tinyint(1) DEFAULT 0,
        submitted_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id),
        KEY user_id (user_id),
        KEY selection_time_ms (selection_time_ms),
        KEY has_warning (has_warning)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    // Warnings table
    $warnings_table = $wpdb->prefix . 'mida_warnings';
    $wpdb->query("DROP TABLE IF EXISTS $warnings_table");
    
    $sql_warnings = "CREATE TABLE $warnings_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        submission_id mediumint(9) NOT NULL,
        user_id bigint(20) NOT NULL,
        warning_type varchar(50) NOT NULL,
        expected_value varchar(100) NOT NULL,
        actual_value varchar(100) NOT NULL,
        created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id),
        KEY submission_id (submission_id),
        KEY user_id (user_id)
    ) $charset_collate;";
    
    dbDelta($sql_warnings);
    
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'activate_mida');

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_mida() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'deactivate_mida');

/**
 * Initialize the plugin.
 */
function mida_init() {
    load_plugin_textdomain('mida', false, dirname(plugin_basename(__FILE__)) . '/languages');
    add_shortcode('mida_house_form', 'mida_house_form_shortcode');
    add_shortcode('mida_rankings', 'mida_rankings_shortcode');
    add_shortcode('mida_debug_db', 'mida_debug_db_shortcode');
}
add_action('plugins_loaded', 'mida_init');

/**
 * Add admin menu
 */
function mida_admin_menu() {
    add_menu_page(
        'Mida Settings',
        'Mida',
        'manage_options',
        'mida-settings',
        'mida_settings_page',
        'dashicons-list-view',
        30
    );
    
    add_submenu_page(
        'mida-settings',
        'User Restrictions',
        'User Restrictions',
        'manage_options',
        'mida-settings',
        'mida_settings_page'
    );
    
    add_submenu_page(
        'mida-settings',
        'Projects (Layihələr)',
        'Projects',
        'manage_options',
        'mida-projects',
        'mida_projects_page'
    );
    
    add_submenu_page(
        'mida-settings',
        'Warnings Log',
        'Warnings Log',
        'manage_options',
        'mida-warnings',
        'mida_warnings_page'
    );
    
    add_submenu_page(
        'mida-settings',
        'Timing Logs',
        'Timing Logs',
        'manage_options',
        'mida-timing-logs',
        'mida_timing_logs_page'
    );
    
    add_submenu_page(
        'mida-settings',
        'Plugin Updates',
        'Updates',
        'manage_options',
        'mida-updates',
        'mida_updates_page'
    );
}
add_action('admin_menu', 'mida_admin_menu');

/**
 * Admin settings page - User Restrictions
 */
function mida_settings_page() {
    global $wpdb;
    
    // Handle form submission
    if (isset($_POST['mida_save_restrictions']) && check_admin_referer('mida_restrictions_nonce')) {
        $user_restrictions = isset($_POST['user_restrictions']) ? $_POST['user_restrictions'] : array();
        update_option('mida_user_restrictions', $user_restrictions);
        echo '<div class="notice notice-success"><p>Restrictions saved successfully!</p></div>';
    }
    
    // Get all users
    $users = get_users(array('orderby' => 'display_name'));
    $restrictions = get_option('mida_user_restrictions', array());
    $projects = get_option('mida_projects', array());
    
    ?>
    <div class="wrap">
        <h1>Mida User Restrictions</h1>
        <p>Set mandatory selection options for each user. If a user selects different options, their time will not be counted in rankings.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('mida_restrictions_nonce'); ?>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 180px;">User</th>
                        <th style="width: 120px;">Doğum tarixi</th>
                        <th>Layihə</th>
                        <th>Ödəniş üsulu</th>
                        <th>Mərtəbə seçimi</th>
                        <th>Otaq sayı</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): 
                        $user_id = $user->ID;
                        $user_data = isset($restrictions[$user_id]) ? $restrictions[$user_id] : array();
                    ?>
                    <tr>
                        <td><strong><?php echo esc_html($user->display_name); ?></strong><br>
                            <small><?php echo esc_html($user->user_email); ?></small>
                        </td>
                        <td>
                            <input type="date" name="user_restrictions[<?php echo $user_id; ?>][birthdate]" 
                                   value="<?php echo esc_attr(isset($user_data['birthdate']) ? $user_data['birthdate'] : ''); ?>" 
                                   style="width: 100%;" required>
                            <small>For captcha</small>
                        </td>
                        <td>
                            <select name="user_restrictions[<?php echo $user_id; ?>][layihe]" style="width: 100%;">
                                <option value="">Any</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?php echo esc_attr($project['name']); ?>" 
                                            <?php selected(isset($user_data['layihe']) ? $user_data['layihe'] : '', $project['name']); ?>>
                                        <?php echo esc_html($project['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="user_restrictions[<?php echo $user_id; ?>][odenish_usulu]" style="width: 100%;" required>
                                <option value="">Select Payment Method</option>
                                <option value="Nağd" <?php selected(isset($user_data['odenish_usulu']) ? $user_data['odenish_usulu'] : '', 'Nağd'); ?>>Nağd</option>
                                <option value="İpoteka" <?php selected(isset($user_data['odenish_usulu']) ? $user_data['odenish_usulu'] : '', 'İpoteka'); ?>>İpoteka</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="user_restrictions[<?php echo $user_id; ?>][mertebe]" 
                                   value="<?php echo esc_attr(isset($user_data['mertebe']) ? $user_data['mertebe'] : ''); ?>" 
                                   placeholder="e.g., 1-5" style="width: 100%;">
                            <small>Leave empty for any, or specify: 1-5 or 1,2,3</small>
                        </td>
                        <td>
                            <input type="text" name="user_restrictions[<?php echo $user_id; ?>][otaq_sayi]" 
                                   value="<?php echo esc_attr(isset($user_data['otaq_sayi']) ? $user_data['otaq_sayi'] : ''); ?>" 
                                   placeholder="e.g., 2,3" style="width: 100%;">
                            <small>Leave empty for any, or specify: 1,2,3,4,5</small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <p class="submit">
                <input type="submit" name="mida_save_restrictions" class="button button-primary" value="Save Restrictions">
            </p>
        </form>
    </div>
    <?php
}

/**
 * Admin projects page - Manage Layihələr
 */
function mida_projects_page() {
    // Handle form submission - Add new project
    if (isset($_POST['mida_add_project']) && check_admin_referer('mida_projects_nonce')) {
        $project_name = sanitize_text_field($_POST['project_name']);
        if (!empty($project_name)) {
            $projects = get_option('mida_projects', array());
            $projects[] = array(
                'name' => $project_name,
                'enabled' => true
            );
            update_option('mida_projects', $projects);
            echo '<div class="notice notice-success"><p>Project added successfully!</p></div>';
        }
    }
    
    // Handle form submission - Update projects
    if (isset($_POST['mida_update_projects']) && check_admin_referer('mida_projects_nonce')) {
        $projects = isset($_POST['projects']) ? $_POST['projects'] : array();
        $updated_projects = array();
        
        foreach ($projects as $index => $project) {
            if (!empty($project['name'])) {
                $updated_projects[] = array(
                    'name' => sanitize_text_field($project['name']),
                    'enabled' => isset($project['enabled']) && $project['enabled'] === '1'
                );
            }
        }
        
        update_option('mida_projects', $updated_projects);
        echo '<div class="notice notice-success"><p>Projects updated successfully!</p></div>';
    }
    
    // Handle delete
    if (isset($_GET['delete']) && check_admin_referer('mida_delete_project_' . $_GET['delete'])) {
        $projects = get_option('mida_projects', array());
        $delete_index = intval($_GET['delete']);
        if (isset($projects[$delete_index])) {
            unset($projects[$delete_index]);
            $projects = array_values($projects); // Reindex array
            update_option('mida_projects', $projects);
            echo '<div class="notice notice-success"><p>Project deleted successfully!</p></div>';
        }
    }
    
    $projects = get_option('mida_projects', array());
    
    ?>
    <div class="wrap">
        <h1>Manage Projects (Layihələr)</h1>
        <p>Add and manage projects that will appear in the first step of the form.</p>
        
        <!-- Add New Project -->
        <div style="background: #fff; padding: 20px; margin-bottom: 20px; border: 1px solid #ccc;">
            <h2>Add New Project</h2>
            <form method="post" action="" style="display: flex; gap: 10px; align-items: center;">
                <?php wp_nonce_field('mida_projects_nonce'); ?>
                <input type="text" name="project_name" placeholder="Project name (e.g., Yeni tikili)" 
                       style="width: 300px; padding: 8px;" required>
                <input type="submit" name="mida_add_project" class="button button-primary" value="Add Project">
            </form>
        </div>
        
        <!-- Existing Projects -->
        <form method="post" action="">
            <?php wp_nonce_field('mida_projects_nonce'); ?>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 60px;">Order</th>
                        <th>Project Name</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($projects)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px; color: #999;">
                                No projects added yet. Add your first project above.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($projects as $index => $project): ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $index + 1; ?></td>
                            <td>
                                <input type="text" name="projects[<?php echo $index; ?>][name]" 
                                       value="<?php echo esc_attr($project['name']); ?>" 
                                       style="width: 100%;">
                            </td>
                            <td>
                                <label style="display: flex; align-items: center; gap: 5px;">
                                    <input type="hidden" name="projects[<?php echo $index; ?>][enabled]" value="0">
                                    <input type="checkbox" name="projects[<?php echo $index; ?>][enabled]" value="1" 
                                           <?php checked($project['enabled'], true); ?>>
                                    <span><?php echo $project['enabled'] ? '✅ Enabled' : '❌ Disabled'; ?></span>
                                </label>
                            </td>
                            <td>
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=mida-projects&delete=' . $index), 'mida_delete_project_' . $index); ?>" 
                                   class="button button-small button-link-delete"
                                   onclick="return confirm('Are you sure you want to delete this project?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <?php if (!empty($projects)): ?>
            <p class="submit">
                <input type="submit" name="mida_update_projects" class="button button-primary" value="Save Changes">
            </p>
            <?php endif; ?>
        </form>
        
        <div style="margin-top: 20px; padding: 15px; background: #f0f6fc; border-left: 4px solid #0073aa;">
            <h3>ℹ️ How it works:</h3>
            <ul>
                <li><strong>Enabled projects</strong> will appear as active options in Step 1</li>
                <li><strong>Disabled projects</strong> will appear as disabled (grayed out) options</li>
                <li>Projects appear in the order listed above</li>
                <li>You can edit project names directly in the table</li>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Admin warnings page
 */
function mida_warnings_page() {
    global $wpdb;
    $warnings_table = $wpdb->prefix . 'mida_warnings';
    
    // Get selected user filter
    $selected_user = isset($_GET['filter_user']) ? intval($_GET['filter_user']) : 0;
    
    // Get user statistics
    $user_stats = $wpdb->get_results(
        "SELECT w.user_id, u.display_name, u.user_email,
        COUNT(*) as total_warnings,
        COUNT(CASE WHEN w.warning_type = 'layihe' THEN 1 END) as layihe_warnings,
        COUNT(CASE WHEN w.warning_type = 'odenish_usulu' THEN 1 END) as odenish_warnings,
        COUNT(CASE WHEN w.warning_type = 'mertebe' THEN 1 END) as mertebe_warnings,
        COUNT(CASE WHEN w.warning_type = 'otaq_sayi' THEN 1 END) as otaq_warnings
        FROM {$warnings_table} w
        LEFT JOIN {$wpdb->users} u ON w.user_id = u.ID
        GROUP BY w.user_id
        ORDER BY total_warnings DESC",
        ARRAY_A
    );
    
    // Build WHERE clause for filtering
    $where_clause = '';
    if ($selected_user > 0) {
        $where_clause = $wpdb->prepare("WHERE w.user_id = %d", $selected_user);
    }
    
    // Get warnings (filtered or all)
    $warnings = $wpdb->get_results(
        "SELECT w.*, u.display_name, u.user_email, s.submitted_at, s.selection_time_display
        FROM {$warnings_table} w
        LEFT JOIN {$wpdb->users} u ON w.user_id = u.ID
        LEFT JOIN {$wpdb->prefix}mida_submissions s ON w.submission_id = s.id
        {$where_clause}
        ORDER BY w.created_at DESC
        LIMIT 100",
        ARRAY_A
    );
    
    ?>
    <div class="wrap">
        <h1>🚨 Mida Warnings Log</h1>
        <p>List of all warnings when users selected options different from their restrictions.</p>
        
        <!-- Statistics Section -->
        <?php if (!empty($user_stats)): ?>
        <div class="card" style="max-width: 100%; margin-bottom: 20px;">
            <h2>📊 Statistics by User</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 250px;">User</th>
                        <th style="width: 100px; text-align: center;">Total Warnings</th>
                        <th style="width: 120px; text-align: center;">Layihə</th>
                        <th style="width: 120px; text-align: center;">Ödəniş üsulu</th>
                        <th style="width: 120px; text-align: center;">Mərtəbə</th>
                        <th style="width: 120px; text-align: center;">Otaq sayı</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user_stats as $stat): ?>
                    <tr>
                        <td>
                            <strong><?php echo esc_html($stat['display_name']); ?></strong><br>
                            <small><?php echo esc_html($stat['user_email']); ?></small>
                        </td>
                        <td style="text-align: center;">
                            <strong style="font-size: 16px; color: #d63638;"><?php echo esc_html($stat['total_warnings']); ?></strong>
                        </td>
                        <td style="text-align: center;"><?php echo esc_html($stat['layihe_warnings']); ?></td>
                        <td style="text-align: center;"><?php echo esc_html($stat['odenish_warnings']); ?></td>
                        <td style="text-align: center;"><?php echo esc_html($stat['mertebe_warnings']); ?></td>
                        <td style="text-align: center;"><?php echo esc_html($stat['otaq_warnings']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        
        <!-- Filter Section -->
        <div class="card" style="max-width: 100%; margin-bottom: 20px;">
            <h2>🔍 Filter Warnings</h2>
            <form method="get" action="">
                <input type="hidden" name="page" value="mida-warnings">
                <select name="filter_user" id="filter_user">
                    <option value="0">All Users</option>
                    <?php foreach ($user_stats as $stat): ?>
                        <option value="<?php echo esc_attr($stat['user_id']); ?>" <?php selected($selected_user, $stat['user_id']); ?>>
                            <?php echo esc_html($stat['display_name']); ?> (<?php echo esc_html($stat['total_warnings']); ?> warnings)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="button button-primary">Apply Filter</button>
                <?php if ($selected_user > 0): ?>
                    <a href="?page=mida-warnings" class="button">Clear Filter</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Warnings Table -->
        <div class="card" style="max-width: 100%;">
            <h2>📋 Warning Details <?php if ($selected_user > 0): ?><span style="color: #2271b1;">(Filtered)</span><?php endif; ?></h2>
            <?php if (empty($warnings)): ?>
                <p>No warnings found.</p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 150px;">Date</th>
                            <th style="width: 200px;">User</th>
                            <th style="width: 150px;">Warning Type</th>
                            <th style="width: 150px;">Expected</th>
                            <th style="width: 150px;">Actual</th>
                            <th style="width: 120px;">Selection Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($warnings as $warning): ?>
                        <tr>
                            <td><?php echo esc_html(date('d.m.Y H:i', strtotime($warning['created_at']))); ?></td>
                            <td>
                                <strong><?php echo esc_html($warning['display_name']); ?></strong><br>
                                <small><?php echo esc_html($warning['user_email']); ?></small>
                            </td>
                            <td><span style="background: #fef8e7; padding: 3px 8px; border-radius: 3px; font-weight: 600;"><?php echo esc_html($warning['warning_type']); ?></span></td>
                            <td><code style="background: #d4edda; color: #155724; padding: 3px 6px;"><?php echo esc_html($warning['expected_value']); ?></code></td>
                            <td><code style="background: #f8d7da; color: #721c24; padding: 3px 6px;"><?php echo esc_html($warning['actual_value']); ?></code></td>
                            <td><?php echo esc_html($warning['selection_time_display']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p style="margin-top: 15px; color: #666;">
                    <em>Showing <?php echo count($warnings); ?> warning(s)<?php if ($selected_user > 0): ?> for selected user<?php endif; ?></em>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Timing Logs page - Performance monitoring
 */
function mida_timing_logs_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mida_submissions';
    
    // Get selected user filter
    $selected_user = isset($_GET['filter_user']) ? intval($_GET['filter_user']) : 0;
    
    // Get user statistics
    $user_stats = $wpdb->get_results(
        "SELECT 
            user_id,
            u.display_name,
            u.user_email,
            COUNT(*) as total_attempts,
            MIN(selection_time_ms) as best_time_ms,
            MAX(selection_time_ms) as slowest_time_ms,
            AVG(selection_time_ms) as avg_time_ms,
            MIN(selection_time_display) as best_time_display,
            MAX(selection_time_display) as slowest_time_display,
            SUM(CASE WHEN has_warning = 1 THEN 1 ELSE 0 END) as warnings_count,
            SUM(CASE WHEN has_warning = 0 THEN 1 ELSE 0 END) as valid_attempts
        FROM {$table_name} s
        LEFT JOIN {$wpdb->users} u ON s.user_id = u.ID
        GROUP BY user_id
        ORDER BY best_time_ms ASC",
        ARRAY_A
    );
    
    // Build WHERE clause for filtering
    $where_clause = 'WHERE 1=1';
    if ($selected_user > 0) {
        $where_clause .= $wpdb->prepare(" AND user_id = %d", $selected_user);
    }
    
    // Get detailed attempts (filtered or all)
    $attempts = $wpdb->get_results(
        "SELECT s.*, u.display_name, u.user_email
        FROM {$table_name} s
        LEFT JOIN {$wpdb->users} u ON s.user_id = u.ID
        {$where_clause}
        ORDER BY s.submitted_at DESC
        LIMIT 200",
        ARRAY_A
    );
    
    // Calculate performance categories
    $performance_distribution = array();
    if ($selected_user > 0 && !empty($attempts)) {
        $fast_count = 0;
        $medium_count = 0;
        $slow_count = 0;
        
        $user_best = min(array_column($attempts, 'selection_time_ms'));
        
        foreach ($attempts as $attempt) {
            $time_ms = $attempt['selection_time_ms'];
            $diff_percent = (($time_ms - $user_best) / $user_best) * 100;
            
            if ($diff_percent <= 10) {
                $fast_count++;
            } elseif ($diff_percent <= 30) {
                $medium_count++;
            } else {
                $slow_count++;
            }
        }
        
        $performance_distribution = array(
            'fast' => $fast_count,
            'medium' => $medium_count,
            'slow' => $slow_count
        );
    }
    
    ?>
    <div class="wrap">
        <h1>⏱️ Timing Logs & Performance Monitor</h1>
        <p>Detailed performance analysis for all users and their selection times.</p>
        
        <!-- Overall Statistics -->
        <?php if (!empty($user_stats)): ?>
        <div class="card" style="max-width: 100%; margin-bottom: 20px;">
            <h2>📊 User Performance Summary</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 200px;">User</th>
                        <th style="width: 80px; text-align: center;">Total Attempts</th>
                        <th style="width: 80px; text-align: center;">Valid</th>
                        <th style="width: 80px; text-align: center;">Warnings</th>
                        <th style="width: 120px; text-align: center;">🏆 Best Time</th>
                        <th style="width: 120px; text-align: center;">🐢 Slowest</th>
                        <th style="width: 120px; text-align: center;">📈 Average</th>
                        <th style="width: 100px; text-align: center;">Performance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user_stats as $stat): 
                        $avg_display = gmdate("i:s", floor($stat['avg_time_ms'] / 1000)) . '.' . str_pad(floor($stat['avg_time_ms'] % 1000), 3, '0', STR_PAD_LEFT);
                        $improvement = $stat['slowest_time_ms'] > 0 ? (($stat['slowest_time_ms'] - $stat['best_time_ms']) / $stat['slowest_time_ms'] * 100) : 0;
                    ?>
                    <tr>
                        <td>
                            <strong><?php echo esc_html($stat['display_name']); ?></strong><br>
                            <small><?php echo esc_html($stat['user_email']); ?></small>
                        </td>
                        <td style="text-align: center;"><strong><?php echo esc_html($stat['total_attempts']); ?></strong></td>
                        <td style="text-align: center;"><span style="color: #28a745;"><?php echo esc_html($stat['valid_attempts']); ?></span></td>
                        <td style="text-align: center;"><span style="color: #dc3545;"><?php echo esc_html($stat['warnings_count']); ?></span></td>
                        <td style="text-align: center;">
                            <code style="background: #d4edda; color: #155724; padding: 3px 8px; font-weight: bold;">
                                <?php echo esc_html($stat['best_time_display']); ?>
                            </code>
                        </td>
                        <td style="text-align: center;">
                            <code style="background: #f8d7da; color: #721c24; padding: 3px 8px;">
                                <?php echo esc_html($stat['slowest_time_display']); ?>
                            </code>
                        </td>
                        <td style="text-align: center;">
                            <code style="background: #e7f3ff; color: #004085; padding: 3px 8px;">
                                <?php echo esc_html($avg_display); ?>
                            </code>
                        </td>
                        <td style="text-align: center;">
                            <span style="color: <?php echo $improvement >= 30 ? '#28a745' : ($improvement >= 15 ? '#ffc107' : '#6c757d'); ?>">
                                <?php echo round($improvement, 1); ?>% ↓
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p style="margin-top: 10px; color: #666; font-size: 12px;">
                <strong>Performance:</strong> Shows improvement from slowest to best time. 
                <span style="color: #28a745;">Green (≥30%)</span> = Great improvement, 
                <span style="color: #ffc107;">Yellow (15-29%)</span> = Good, 
                <span style="color: #6c757d;">Gray (&lt;15%)</span> = Consistent
            </p>
        </div>
        <?php endif; ?>
        
        <!-- Filter Section -->
        <div class="card" style="max-width: 100%; margin-bottom: 20px;">
            <h2>🔍 Filter by User</h2>
            <form method="get" action="">
                <input type="hidden" name="page" value="mida-timing-logs">
                <select name="filter_user" id="filter_user" style="min-width: 300px;">
                    <option value="0">All Users</option>
                    <?php foreach ($user_stats as $stat): ?>
                        <option value="<?php echo esc_attr($stat['user_id']); ?>" <?php selected($selected_user, $stat['user_id']); ?>>
                            <?php echo esc_html($stat['display_name']); ?> (<?php echo esc_html($stat['total_attempts']); ?> attempts - Best: <?php echo esc_html($stat['best_time_display']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="button button-primary">Apply Filter</button>
                <?php if ($selected_user > 0): ?>
                    <a href="?page=mida-timing-logs" class="button">Clear Filter</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Performance Distribution (when user filtered) -->
        <?php if ($selected_user > 0 && !empty($performance_distribution)): ?>
        <div class="card" style="max-width: 100%; margin-bottom: 20px;">
            <h2>📈 Performance Distribution</h2>
            <div style="display: flex; gap: 20px; align-items: center;">
                <div style="flex: 1; text-align: center; padding: 15px; background: #d4edda; border-radius: 5px;">
                    <div style="font-size: 32px; font-weight: bold; color: #155724;">
                        <?php echo esc_html($performance_distribution['fast']); ?>
                    </div>
                    <div style="color: #155724; font-weight: 600;">Fast Attempts</div>
                    <small style="color: #666;">Within 10% of best</small>
                </div>
                <div style="flex: 1; text-align: center; padding: 15px; background: #fff3cd; border-radius: 5px;">
                    <div style="font-size: 32px; font-weight: bold; color: #856404;">
                        <?php echo esc_html($performance_distribution['medium']); ?>
                    </div>
                    <div style="color: #856404; font-weight: 600;">Medium Attempts</div>
                    <small style="color: #666;">10-30% slower</small>
                </div>
                <div style="flex: 1; text-align: center; padding: 15px; background: #f8d7da; border-radius: 5px;">
                    <div style="font-size: 32px; font-weight: bold; color: #721c24;">
                        <?php echo esc_html($performance_distribution['slow']); ?>
                    </div>
                    <div style="color: #721c24; font-weight: 600;">Slow Attempts</div>
                    <small style="color: #666;">More than 30% slower</small>
                </div>
            </div>
            <p style="margin-top: 15px; color: #666; text-align: center;">
                <strong>Analysis:</strong> Shows where the user struggles most. High "Slow Attempts" indicates inconsistency.
            </p>
        </div>
        <?php endif; ?>
        
        <!-- Detailed Attempts Log -->
        <div class="card" style="max-width: 100%;">
            <h2>📋 Detailed Attempts Log <?php if ($selected_user > 0): ?><span style="color: #2271b1;">(Filtered)</span><?php endif; ?></h2>
            <?php if (empty($attempts)): ?>
                <p>No attempts found.</p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 150px;">Date & Time</th>
                            <th style="width: 180px;">User</th>
                            <th style="width: 120px; text-align: center;">Time</th>
                            <th style="width: 150px;">Layihə</th>
                            <th style="width: 130px;">Ödəniş</th>
                            <th style="width: 100px;">Mərtəbə</th>
                            <th style="width: 100px;">Otaq sayı</th>
                            <th style="width: 80px; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $prev_user_id = null;
                        foreach ($attempts as $attempt): 
                            $is_warning = $attempt['has_warning'] == 1;
                            $row_style = $is_warning ? 'background-color: #fff3cd;' : '';
                            
                            // Calculate performance indicator for filtered user
                            $performance_indicator = '';
                            if ($selected_user > 0 && !empty($user_stats)) {
                                $user_best = $user_stats[0]['best_time_ms'];
                                $diff_percent = (($attempt['selection_time_ms'] - $user_best) / $user_best) * 100;
                                
                                if ($diff_percent <= 10) {
                                    $performance_indicator = '<span style="color: #28a745; font-weight: bold;">🚀 Fast</span>';
                                } elseif ($diff_percent <= 30) {
                                    $performance_indicator = '<span style="color: #ffc107; font-weight: bold;">⚡ Medium</span>';
                                } else {
                                    $performance_indicator = '<span style="color: #dc3545; font-weight: bold;">🐢 Slow</span>';
                                }
                            }
                        ?>
                        <tr style="<?php echo $row_style; ?>">
                            <td><?php echo esc_html(date('d.m.Y H:i:s', strtotime($attempt['submitted_at']))); ?></td>
                            <td>
                                <strong><?php echo esc_html($attempt['display_name']); ?></strong><br>
                                <small><?php echo esc_html($attempt['user_email']); ?></small>
                            </td>
                            <td style="text-align: center;">
                                <code style="background: #e7f3ff; padding: 3px 8px; font-weight: bold; font-size: 13px;">
                                    <?php echo esc_html($attempt['selection_time_display']); ?>
                                </code>
                                <?php if ($performance_indicator): ?>
                                    <br><?php echo $performance_indicator; ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($attempt['layihe'] ?: '-'); ?></td>
                            <td><?php echo esc_html($attempt['odenish_usulu'] ?: '-'); ?></td>
                            <td><?php echo esc_html($attempt['mertebe'] ?: '-'); ?></td>
                            <td><?php echo esc_html($attempt['otaq_sayi'] ?: '-'); ?></td>
                            <td style="text-align: center;">
                                <?php if ($is_warning): ?>
                                    <span style="background: #dc3545; color: white; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: bold;">⚠️ WARNING</span>
                                <?php else: ?>
                                    <span style="background: #28a745; color: white; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: bold;">✅ VALID</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p style="margin-top: 15px; color: #666;">
                    <em>Showing <?php echo count($attempts); ?> attempt(s)<?php if ($selected_user > 0): ?> for selected user<?php endif; ?> (limited to 200 most recent)</em>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Plugin Updates page
 */
function mida_updates_page() {
    global $myUpdateChecker;
    
    // Handle manual update check
    if (isset($_POST['check_for_updates']) && check_admin_referer('mida_check_updates')) {
        // Force the update checker to run
        $myUpdateChecker->checkForUpdates();
        $update = $myUpdateChecker->getUpdate();
        
        if ($update !== null) {
            echo '<div class="notice notice-success"><p>✅ New version available: ' . esc_html($update->version) . '</p></div>';
        } else {
            echo '<div class="notice notice-info"><p>✅ You are running the latest version!</p></div>';
        }
    }
    
    // Get current plugin info
    $pluginFile = plugin_basename(MIDA_PLUGIN_DIR . 'mida.php');
    $pluginData = get_plugin_data(MIDA_PLUGIN_DIR . 'mida.php');
    
    // Get update info
    $update = $myUpdateChecker->getUpdate();
    
    ?>
    <div class="wrap">
        <h1>🔄 Plugin Updates</h1>
        
        <div class="card" style="max-width: 800px;">
            <h2>Current Version</h2>
            <table class="form-table">
                <tr>
                    <th>Plugin Name:</th>
                    <td><strong><?php echo esc_html($pluginData['Name']); ?></strong></td>
                </tr>
                <tr>
                    <th>Current Version:</th>
                    <td><code><?php echo esc_html(MIDA_VERSION); ?></code></td>
                </tr>
                <tr>
                    <th>Author:</th>
                    <td><?php echo esc_html($pluginData['Author']); ?></td>
                </tr>
                <tr>
                    <th>GitHub Repository:</th>
                    <td><a href="https://github.com/difteriya/MDAWPPlugin" target="_blank">difteriya/MDAWPPlugin</a></td>
                </tr>
            </table>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>Update Status</h2>
            
            <?php if ($update !== null): ?>
                <div class="notice notice-warning inline">
                    <p><strong>🎉 New version available: <?php echo esc_html($update->version); ?></strong></p>
                    <p>You can update to the latest version from the <a href="<?php echo admin_url('plugins.php'); ?>">Plugins page</a>.</p>
                </div>
            <?php else: ?>
                <div class="notice notice-success inline">
                    <p><strong>✅ You are running the latest version!</strong></p>
                </div>
            <?php endif; ?>
            
            <form method="post" style="margin-top: 20px;">
                <?php wp_nonce_field('mida_check_updates'); ?>
                <button type="submit" name="check_for_updates" class="button button-primary">
                    🔍 Check for Updates Now
                </button>
                <p class="description">Manually check for updates from GitHub instead of waiting for automatic checks.</p>
            </form>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>ℹ️ Update Information</h2>
            <ul>
                <li><strong>Automatic checks:</strong> WordPress checks for updates every 12 hours</li>
                <li><strong>Manual check:</strong> Click the button above to check immediately</li>
                <li><strong>Update source:</strong> Updates are pulled directly from GitHub</li>
                <li><strong>One-click update:</strong> Update from the Plugins page when available</li>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Multi-step house picking form shortcode - EXACT HTML FROM TARGET
 */
function mida_house_form_shortcode($atts) {
    // Enqueue styles in correct order
    wp_enqueue_style('mida-variables', MIDA_PLUGIN_URL . 'assets/css/variables.css', array(), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-fonts', MIDA_PLUGIN_URL . 'assets/css/fonts.css', array('mida-variables'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-bootstrap-bundle', MIDA_PLUGIN_URL . 'assets/css/bootstrap-bundle.css', array('mida-fonts'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-index', MIDA_PLUGIN_URL . 'assets/css/index.css', array('mida-bootstrap-bundle'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-helper', MIDA_PLUGIN_URL . 'assets/css/helper.css', array('mida-index'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-buttons', MIDA_PLUGIN_URL . 'assets/css/buttons.css', array('mida-helper'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-inputs', MIDA_PLUGIN_URL . 'assets/css/inputs.css', array('mida-buttons'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-breadcrumb', MIDA_PLUGIN_URL . 'assets/css/breadcrumb.css', array('mida-inputs'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-alert', MIDA_PLUGIN_URL . 'assets/css/alert.css', array('mida-breadcrumb'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-callout', MIDA_PLUGIN_URL . 'assets/css/callout.css', array('mida-alert'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-loader', MIDA_PLUGIN_URL . 'assets/css/loader.css', array('mida-callout'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-axtaris', MIDA_PLUGIN_URL . 'assets/css/axtaris.css', array('mida-loader'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-responsive', MIDA_PLUGIN_URL . 'assets/css/responsive.css', array('mida-axtaris'), MIDA_VERSION, 'all');
    wp_enqueue_style('mida-override', MIDA_PLUGIN_URL . 'assets/css/override.css', array('mida-responsive'), MIDA_VERSION, 'all');
    
    // Enqueue scripts
    wp_enqueue_script('mida-target-functions', MIDA_PLUGIN_URL . 'assets/js/target-functions.js', array('jquery'), MIDA_VERSION, true);
    wp_enqueue_script('mida-script', MIDA_PLUGIN_URL . 'assets/js/script.js', array('jquery', 'mida-target-functions'), MIDA_VERSION, true);
    
    // Get user restrictions including birthdate
    $user_restrictions = array();
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $all_restrictions = get_option('mida_user_restrictions', array());
        if (isset($all_restrictions[$user_id])) {
            $user_restrictions = $all_restrictions[$user_id];
        }
    }
    
    wp_localize_script('mida-script', 'midaAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mida_house_form_submit'),
        'save_selection_nonce' => wp_create_nonce('mida_save_selection')
    ));
    
    wp_localize_script('mida-script', 'midaUserRestrictions', $user_restrictions);
    
    ob_start();
    
    // Get user's last 3 selection times if logged in
    $last_times = array();
    if (is_user_logged_in()) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mida_submissions';
        $user_id = get_current_user_id();
        
        $last_times = $wpdb->get_results($wpdb->prepare(
            "SELECT selection_time_display, submitted_at 
            FROM {$table_name} 
            WHERE user_id = %d 
            ORDER BY submitted_at DESC 
            LIMIT 3",
            $user_id
        ), ARRAY_A);
    }
    
    // Get projects from settings
    $projects = get_option('mida_projects', array());
    if (empty($projects)) {
        // Default projects if none configured
        $projects = array(
            array('name' => 'Yasamal Yaşayış Kompleksi', 'enabled' => false),
            array('name' => 'Hövsan Yaşayış Kompleksi', 'enabled' => true),
            array('name' => 'Sumqayıt şəhərində güzəştli mənzillər', 'enabled' => false),
            array('name' => 'Gəncə Yaşayış Kompleksi', 'enabled' => false),
        );
    }
    
    // Get current user's restrictions
    $user_restrictions = array();
    if (is_user_logged_in()) {
        $all_restrictions = get_option('mida_user_restrictions', array());
        $current_user_id = get_current_user_id();
        $user_restrictions = isset($all_restrictions[$current_user_id]) ? $all_restrictions[$current_user_id] : array();
    }
    
    ?>
    <!-- EXACT HTML FROM MIDA TARGET WEBSITE -->
    <div class="cont body-content">
    <div id="options" class="w-100">
        <div id="page-loading-spinner" class="overflow-hidden d-flex justify-content-center align-items-center loader-container" style="z-index: 1000000000; display: none !important;">
            <div class="loader"></div>
        </div>
        
        <div class="editor-content">
            <!-- Start Screen -->
            <div id="start-screen" class="w-100">
                <div class="card mt-5 w-100 border-0 p-5 text-center">
                    <h2 class="mb-4">Mənzil Seçim Sistemi</h2>
                    <p class="mb-4">Mənzil seçim prosesini başlatmaq üçün aşağıdakı düyməyə klikləyin.</p>
                    
                    <?php if (!empty($last_times)): ?>
                    <!-- Last 3 Selection Times -->
                    <div class="mb-4" style="max-width: 400px; margin: 0 auto;">
                        <h5 class="mb-3" style="color: #199862; font-weight: 600;">Son 3 Nəticəniz</h5>
                        <div style="background: #f8f9fa; border-radius: 8px; padding: 15px;">
                            <?php foreach ($last_times as $index => $time): ?>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; <?php echo $index < 2 ? 'border-bottom: 1px solid #dee2e6;' : ''; ?>">
                                <span style="color: #6c757d; font-size: 14px;">
                                    <?php echo date('d.m.Y H:i', strtotime($time['submitted_at'])); ?>&nbsp
                                -->&nbsp&nbsp&nbsp
                                </span>
                                <span style="font-family: monospace; font-size: 18px; font-weight: 700; color: #464646ff;">
                                    <?php echo esc_html($time['selection_time_display']); ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-center">
                        <button type="button" id="start-btn" class="btn btn-success px-5 py-2">Başla</button>
                    </div>
                </div>
            </div>
            
            <!-- Main Form Container (Initially Hidden) -->
            <div id="main-form-container" style="display: none;">
            
            <!-- Timer Display -->
            <div id="selection-timer" style="position: fixed; top: 20px; left: 20px; background: #fff; padding: 10px 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000; display: none;">
                <div style="font-size: 14px; font-weight: 600; color: #333; margin-bottom: 5px;">Seçim müddəti</div>
                <div id="timer-display" style="font-size: 24px; font-weight: 700; color: #199862; font-family: monospace;">00:00:000</div>
            </div>
            
            <!-- Breadcrumb Navigation - EXACT FROM TARGET -->
            <div class="breadcrumb mt-4 mb-5 px-3">
                <div class="bc-item pending">
                    <div class="d-flex w-100 align-items-center test">
                        <div class="bc-circle">
                            <span>1</span>
                            <small class="mt-2 breadcrumb-title">Seçimlər</small>
                        </div>
                        <div class="bc-line2 w-100"></div>
                    </div>
                </div>
                
                <div class="bc-item" style="display: none;">
                    <div class="d-flex w-100 align-items-center test">
                        <div class="bc-circle">
                            <span>0</span>
                            <small class="mt-2 breadcrumb-title">Axtarış</small>
                        </div>
                        <div class="bc-line2 w-100"></div>
                    </div>
                </div>
                
                <div class="bc-item">
                    <div class="d-flex w-100 align-items-center test">
                        <div class="bc-circle">
                            <span>2</span>
                            <small class="mt-2 breadcrumb-title">Mənzil</small>
                        </div>
                        <div class="bc-line2 w-100"></div>
                    </div>
                </div>
                
                <div class="bc-item flex-grow-0 min-w-0">
                    <div class="d-flex align-items-center">
                        <div class="bc-circle">
                            <span>3</span>
                            <small class="mt-2 breadcrumb-title">Ərizə</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form - EXACT FROM TARGET -->
            <form action="" id="mida-house-form" method="post" class="w-100" style="margin-top: 4.5rem;">
                <?php wp_nonce_field('mida_house_form_submit', 'mida_house_form_nonce'); ?>
                
                <!-- Step 1: Seçimlər -->
                <div id="step-secimler" class="w-100">
                    <div class="card mt-5 w-100 position-relative ">
                        <div class="card-tab-title card-tab-title text-secondary">
                            <span>Mənzil sifarişi</span>
                        </div>
                        
                        <div class="card-heading">
                            <h3 class="card-title pb-4 border-b">Seçimlər</h3>
                        </div>
                        
                        <div>
                            <input type="hidden" name="PaymentType">
                        <input type="hidden" name="Method">
                        <input type="hidden" name="ProjectId">
                        <input type="hidden" name="BirthDateCode" data-nosubmitonenter="true" value="12345678">
                        <br>
                        
                        <div class="card-body p-0">
                            <!-- Project Selection -->
                            <div class="card-row pt-0 border-0">
                                <div class="col-6 d-flex justify-content-between">
                                    <h6>Layihə</h6>
                                    <select autocomplete="off" class="" style="max-width: fit-content;">
                                        <option disabled="disabled" selected="selected" hidden="hidden" value="">Layihəni seçin</option>
                                        <?php foreach ($projects as $index => $project): ?>
                                            <option value="<?php echo esc_attr($project['name']); ?>" <?php echo !$project['enabled'] ? 'disabled="disabled"' : ''; ?>>
                                                <?php echo esc_html($project['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Payment Method -->
                            <div class="card-row flex-wrap">
                                <div class="col-6">
                                    <h6 class="mb-3">Ödəniş üsulu</h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <div class="d-flex flex-wrap gap-5">
                                            <div class="d-flex gap-3">
                                                <input type="radio" name="payment-method" id="cash-payment-choice" autocomplete="off" class="form-check-input radio-input" value="Nağd" disabled>
                                                <label for="cash-payment-choice">Öz vəsaiti hesabına</label>
                                            </div>
                                            
                                            <div class="d-flex gap-3 position-relative">
                                                <input type="radio" name="payment-method" id="loan-payment-choice" autocomplete="off" class="form-check-input radio-input" value="İpoteka" disabled>
                                                <label for="loan-payment-choice">İpoteka krediti hesabına</label>
                                                <div id="loan-payment-type-not-allowed" onmouseover="toggleTooltip('loan-payment-type-not-allowed', true)" onmouseout="toggleTooltip('loan-payment-type-not-allowed', false)" class="custom-tooltip-icon">
                                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="svg-icon-size-2">
                                                        <path d="M24 12C24 8.8174 22.7357 5.76515 20.4853 3.51472C18.2348 1.26428 15.1826 0 12 0C8.8174 0 5.76515 1.26428 3.51472 3.51472C1.26428 5.76515 0 8.8174 0 12C0 15.1826 1.26428 18.2348 3.51472 20.4853C5.76515 22.7357 8.8174 24 12 24C15.1826 24 18.2348 22.7357 20.4853 20.4853C22.7357 18.2348 24 15.1826 24 12ZM11.262 10.365C11.2933 10.1921 11.3843 10.0356 11.5192 9.92298C11.6541 9.81033 11.8243 9.74862 12 9.74862C12.1757 9.74862 12.3459 9.81033 12.4808 9.92298C12.6156 10.0356 12.7067 10.1921 12.738 10.365L12.75 10.5V17.253L12.738 17.388C12.7067 17.5609 12.6156 17.7174 12.4808 17.83C12.3459 17.9427 12.1757 18.0044 12 18.0044C11.8243 18.0044 11.6541 17.9427 11.5192 17.83C11.3843 17.7174 11.2933 17.5609 11.262 17.388L11.25 17.253V10.5L11.262 10.365ZM10.875 7.125C10.875 6.82663 10.9935 6.54048 11.2045 6.3295C11.4155 6.11852 11.7016 6 12 6C12.2984 6 12.5845 6.11852 12.7955 6.3295C13.0065 6.54048 13.125 6.82663 13.125 7.125C13.125 7.42337 13.0065 7.70951 12.7955 7.92049C12.5845 8.13147 12.2984 8.25 12 8.25C11.7016 8.25 11.4155 8.13147 11.2045 7.92049C10.9935 7.70951 10.875 7.42337 10.875 7.125Z" fill="var(--info)"></path>
                                                    </svg>
                                                </div>
                                                <div id="loan-payment-type-not-allowed-tooltip" class="d-none custom-tooltip">
                                                    <div role="alert" class="alert alert-info flex-column my-0">
                                                        <div class="d-flex gap-3 m-0">
                                                            <div style="line-height: 0;">
                                                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="svg-icon-size-2">
                                                                    <path d="M24 12C24 8.8174 22.7357 5.76515 20.4853 3.51472C18.2348 1.26428 15.1826 0 12 0C8.8174 0 5.76515 1.26428 3.51472 3.51472C1.26428 5.76515 0 8.8174 0 12C0 15.1826 1.26428 18.2348 3.51472 20.4853C5.76515 22.7357 8.8174 24 12 24C15.1826 24 18.2348 22.7357 20.4853 20.4853C22.7357 18.2348 24 15.1826 24 12ZM11.262 10.365C11.2933 10.1921 11.3843 10.0356 11.5192 9.92298C11.6541 9.81033 11.8243 9.74862 12 9.74862C12.1757 9.74862 12.3459 9.81033 12.4808 9.92298C12.6156 10.0356 12.7067 10.1921 12.738 10.365L12.75 10.5V17.253L12.738 17.388C12.7067 17.5609 12.6156 17.7174 12.4808 17.83C12.3459 17.9427 12.1757 18.0044 12 18.0044C11.8243 18.0044 11.6541 17.9427 11.5192 17.83C11.3843 17.7174 11.2933 17.5609 11.262 17.388L11.25 17.253V10.5L11.262 10.365ZM10.875 7.125C10.875 6.82663 10.9935 6.54048 11.2045 6.3295C11.4155 6.11852 11.7016 6 12 6C12.2984 6 12.5845 6.11852 12.7955 6.3295C13.0065 6.54048 13.125 6.82663 13.125 7.125C13.125 7.42337 13.0065 7.70951 12.7955 7.92049C12.5845 8.13147 12.2984 8.25 12 8.25C11.7016 8.25 11.4155 8.13147 11.2045 7.92049C10.9935 7.70951 10.875 7.42337 10.875 7.125Z" fill="var(--info)"></path>
                                                                </svg>
                                                            </div>
                                                            <p class="w-100">Azərbaycan Respublikası İpoteka və Kredit Zəmanət Fondunun güzəştli ipoteka krediti (3 ildən 30 ilədək müddətində, 4% illik).</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Flat Selection Method -->
                            <div class="card-row">
                                <div class="col-6">
                                    <h6 class="mb-3">Mənzil seçimi üsulu</h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <div class="d-flex gap-5">
                                            <div class="d-flex gap-3">
                                                <input v:checked="explorer == 'Map'" type="radio" name="flat-selection" id="explorer-map" autocomplete="off" class="form-check-input radio-input" disabled>
                                                <label for="explorer-map">Xəritə üzərində</label>
                                            </div>
                                            <div class="d-flex gap-3">
                                                <input v:checked="explorer == 'Search'" type="radio" name="flat-selection" id="explorer-search" autocomplete="off" class="form-check-input radio-input" disabled>
                                                <label for="explorer-search">Parametrlər üzrə</label>
                                            </div>
                                            <div class="d-flex gap-3">
                                                <input v:checked="explorer == 'Address'" type="radio" name="flat-selection" id="explorer-address" autocomplete="off" class="form-check-input radio-input" disabled>
                                                <label for="explorer-address">Ünvan üzrə</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Birthdate Captcha -->
                        <div class="card-row mt-4">
                            <div class="col-12">
                                <div id="birthdate-captcha" style="background: #f8f9fa; padding: 30px; border-radius: 8px; width: 100%; border: 1px solid #dee2e6;">
                                    <div style="width: 50%;">
                                        <!-- Content will be dynamically generated by JavaScript -->
                                    </div>
                                    <input type="hidden" id="captcha-verified" value="0">
                                    <input type="hidden" id="captcha-answer" value="">
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <p data-valmsg-for="PaymentType" data-valmsg-replace="true" class="field-validation-valid text-danger my-1"></p>
                            <p data-valmsg-for="SelectionMethod" data-valmsg-replace="true" class="field-validation-valid text-danger my-1"></p>
                            <p data-valmsg-for="BirthDateCode" data-valmsg-replace="true" class="field-validation-valid text-danger text-center"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between text-center align-self-start p-0 text-center mt-4 ms-auto w-100">
                    <a href="#" onclick="return false;" class="m-0 btn btn-outline-secondary btn-transparent btn-prev gap-3">
                        <svg viewBox="0 0 7 13" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 0.4375rem; height: 0.8125rem;">
                            <path d="M6.5 12.3333L0.666667 6.49992L6.5 0.666586" stroke="#C1C1C1" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        Əvvəlki
                    </a>
                    <div class="cursor">
                        <button type="button" id="btn-next-step1" class="mb-0 btn btn-success gap-3 mida-btn-next" disabled>
                            Növbəti&nbsp;&nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#e8eaed" class="svg-icon-size-1">
                                <path d="m321-80-71-71 329-329-329-329 71-71 400 400L321-80Z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                    </div>
                </div>
                <!-- End Step 1 -->
                
                <!-- Step 2: Axtarış (Search by Parameters) - Hidden by default -->
                <div id="step-axtaris" class="w-100" style="display: none;">
                    <div class="row w-100 gap-3 flex-nowrap mt-5 ">
                    <!-- Left Sidebar - Filters -->
                    <div class="col-3">
                        <div id="side-filter-spacer" style="height: 0px; width: 100%;"></div>
                        <form action="" id="side-filter" method="post" style="position: sticky; height: max-content; top: 10px;">
                            <div class="p-0">
                                <input type="hidden" name="Storeys" value="">
                                <input type="hidden" name="MinLivingFloor" value="">
                                <input type="hidden" name="MaxLivingFloor" value="">
                                <input type="hidden" name="Rooms" value="">
                                <input type="hidden" name="BirthDateCode" data-nosubmitonenter="true" value="12345678">
                            </div>
                            
                            <p class="text-center card border-0 text-black fw-600 mb-2 py-2 px-4">Binəqədi Yaşayış Kompleksi</p>
                            
                            <!-- Birthdate Captcha for Step 2 -->
                            <div id="birthdate-captcha-step2" style="background: #f8f9fa; padding: 20px; border-radius: 8px; width: 100%; border: 1px solid #dee2e6; margin-bottom: 15px;">
                                <div style="width: 100%;">
                                    <!-- Content will be dynamically generated by JavaScript -->
                                </div>
                                <input type="hidden" id="captcha-verified-step2" value="0">
                                <input type="hidden" id="captcha-answer-step2" value="">
                            </div>
                            
                            <div class="card border-0">
                                <div class="card-heading">
                                    <h3 class="card-title" style="font-size: 1.125rem;">Parametrlər üzrə mənzil seçimi</h3>
                                </div>
                                <div class="card-body p-0">
                                    <!-- Bina tipi -->
                                    <div class="card-row p-0">
                                        <div class="mb-3 w-100">
                                            <h4 class="fs-14 my-3">Bina tipi</h4>
                                            <ul class="list-group">
                                                <li class="list-group-item success" data-building-type="9">9 mərtəbəli</li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <!-- Mərtəbə seçimi -->
                                    <div class="card-row p-0">
                                        <div class="mb-3 w-100">
                                            <h4 class="fs-14 my-3">Mərtəbə seçimi</h4>
                                            <div class="d-flex gap-3">
                                                <select class="flex-grow-1 m-0" name="min_floor" disabled>
                                                    <option value=""></option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                </select>
                                                <select class="flex-grow-1 m-0" name="max_floor" disabled>
                                                    <option value=""></option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Otaq sayı -->
                                    <div class="card-row p-0">
                                        <div class="mb-3 w-100">
                                            <h4 class="fs-14 my-3">Otaq sayı</h4>
                                            <ul class="list-group">
                                                <li class="list-group-item success cursor disabled" data-rooms="1">1 otaqlı</li>
                                                <li class="list-group-item success cursor disabled" data-rooms="2">2 otaqlı</li>
                                                <li class="list-group-item success cursor disabled" data-rooms="3">3 otaqlı</li>
                                                <li class="list-group-item success cursor disabled" data-rooms="4">4 otaqlı</li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <!-- Buttons -->
                                    <div class="card-row gap-3 pb-0 w-100">
                                        <a class="w-100 text-center fw-500 btn cursor" style="color: var(--black); border-bottom: 0.08rem solid var(--black);" id="reset-filters">Sıfırla</a>
                                        <div class="cursor w-100">
                                            <button type="button" id="search-apartments" class="btn btn-success w-100" disabled>Axtar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4 align-self-start">
                                <a href="#" class="mb-0 btn btn-outline-secondary btn-transparent gap-3 btn-prev" id="back-to-options">
                                    <svg viewBox="0 0 7 13" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 0.4375rem; height: 0.8125rem;">
                                        <path d="M6.5 12.3333L0.666667 6.49992L6.5 0.666586" stroke="#C1C1C1" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    Geri
                                </a>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Right Side - Search Results -->
                    <div id="table-wrapper" class="col-9 card border-0 p-2 pt-0">
                        <div id="apartments-list-container">
                            <!-- Header Row (Sticky) -->
                            <div class="row m-0 py-3 bg-white position-sticky top-0">
                                <div class="fs-14 fw-600 text-center p-0" style="width:12.5%; flex: 0 0 auto;">Bina</div>
                                <div class="fs-14 fw-600 text-center p-0" style="width:12.5%; flex: 0 0 auto;">Giriş</div>
                                <div class="fs-14 fw-600 text-center p-0" style="width:12.5%; flex: 0 0 auto;">Mərtəbə</div>
                                <div class="fs-14 fw-600 text-center p-0" style="width:12.5%; flex: 0 0 auto;">Mənzil</div>
                                <div class="col-2 fs-14 fw-600 text-center p-0">Otaq sayı</div>
                                <div class="col-2 fs-14 fw-600 text-center p-0">Sahə, m<sup>2</sup></div>
                                <div class="col-2 fs-14 fw-600 text-center p-0">Qiymət, AZN</div>
                            </div>
                            
                            <!-- Apartments List (Will be populated by JavaScript) -->
                            <div id="apartments-list">
                                <!-- Empty State -->
                                <div class="d-flex h-100 justify-content-center align-items-center py-5">
                                    <div class="dashed-border mg-glass-cont success d-flex flex-column align-items-center justify-content-center gap-3" style="width: 33%;">
                                        <svg viewBox="0 0 163 109" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 10.1875rem; height: 6.8125rem;">
                                            <path d="M153.389 23.9466C153.389 27.4389 151.978 30.5996 149.698 32.8881C147.418 35.1767 144.267 36.5923 140.787 36.5923H23.2696C16.3102 36.5923 10.6689 30.9312 10.6689 23.9473C10.6689 20.455 12.0796 17.2943 14.3602 15.0057C16.6399 12.7172 19.7903 11.3015 23.2696 11.3015H140.787C147.747 11.3015 153.389 16.9627 153.389 23.9466Z" fill="#EAEEF1" fill-opacity="0.4"></path>
                                            <path d="M23.2695 11.8015H140.787C147.469 11.8015 152.889 17.2376 152.889 23.947C152.889 27.092 151.698 29.9559 149.744 32.113L149.344 32.5349C147.154 34.7333 144.129 36.0925 140.787 36.0925H23.2695C16.588 36.0925 11.1689 30.6564 11.1689 23.947C11.169 20.802 12.3597 17.9382 14.3135 15.781L14.7139 15.3591L14.7148 15.3582C16.8363 13.2288 19.7406 11.8873 22.957 11.8054L23.2695 11.8015Z" stroke="#EAEEF1" stroke-opacity="0.6"></path>
                                            <path d="M37.4757 11.3015H36.8496V36.5923H37.4757V11.3015Z" fill="#F0F4F7"></path>
                                            <path d="M127.208 11.3015H126.582V36.5923H127.208V11.3015Z" fill="#F0F4F7"></path>
                                            <path d="M28.8932 27.8449L26.4815 25.4247C26.9868 24.7749 27.2891 23.9585 27.2891 23.0722C27.2891 20.9599 25.5761 19.2408 23.4712 19.2408C21.3663 19.2408 19.6533 20.9599 19.6533 23.0722C19.6533 25.1845 21.3663 26.9036 23.4712 26.9036C24.3544 26.9036 25.1679 26.6002 25.8155 26.0931L28.2271 28.5133C28.319 28.6055 28.4398 28.6516 28.5605 28.6516C28.6813 28.6516 28.8021 28.6055 28.8939 28.5133C29.0769 28.3289 29.0769 28.03 28.8932 27.8449ZM20.5958 23.0729C20.5958 21.4818 21.8857 20.1873 23.4712 20.1873C25.0568 20.1873 26.3467 21.4818 26.3467 23.0729C26.3467 24.6641 25.0568 25.9585 23.4712 25.9585C21.8857 25.9585 20.5958 24.6641 20.5958 23.0729Z" fill="#6C767C"></path>
                                            <path d="M139.007 25.6582C140.026 25.6582 140.853 24.8284 140.853 23.8053V21.0937C140.853 20.0706 140.026 19.2408 139.007 19.2408C137.987 19.2408 137.16 20.0706 137.16 21.0937V23.8046C137.16 24.8284 137.986 25.6582 139.007 25.6582Z" fill="#6C767C"></path>
                                    <path d="M142.007 23.9154C142.007 23.7272 141.855 23.5756 141.668 23.5756C141.481 23.5756 141.33 23.728 141.33 23.9154C141.33 25.2009 140.288 26.2463 139.007 26.2463C137.726 26.2463 136.684 25.2009 136.684 23.9154C136.684 23.7272 136.532 23.5756 136.345 23.5756C136.158 23.5756 136.007 23.728 136.007 23.9154C136.007 25.4604 137.173 26.7378 138.668 26.9073V27.972H137.891C137.704 27.972 137.552 28.1244 137.552 28.3118C137.552 28.4992 137.704 28.6516 137.891 28.6516H140.123C140.31 28.6516 140.461 28.4992 140.461 28.3118C140.461 28.1244 140.309 27.972 140.123 27.972H139.345V26.9073C140.841 26.7378 142.007 25.4611 142.007 23.9154Z" fill="#6C767C"></path>
                                    <path d="M106.564 64.5666L100.752 70.3992L105.446 75.1098L111.258 69.2772L106.564 64.5666Z" fill="#5A6266"></path>
                                    <path d="M106.723 65.4282L101.612 70.557C100.99 71.1809 99.9818 71.1809 99.3602 70.557L96.7529 67.9406L104.115 60.5522L106.723 63.1687C107.344 63.7925 107.344 64.8044 106.723 65.4282Z" fill="#C7CDD1"></path>
                                    <path d="M81.2871 71.7891C96.0127 71.7891 107.95 59.8095 107.95 45.032C107.95 30.2545 96.0127 18.275 81.2871 18.275C66.5615 18.275 54.624 30.2545 54.624 45.032C54.624 59.8095 66.5615 71.7891 81.2871 71.7891Z" fill="#5D88C6"></path>
                                    <path d="M98.1595 61.9635C88.8411 71.314 73.734 71.314 64.4156 61.9635C55.0972 52.6122 55.0972 37.4518 64.4156 28.1006C73.734 18.7493 88.8411 18.7493 98.1595 28.1006C107.477 37.4518 107.477 52.6122 98.1595 61.9635Z" fill="#199862" class="mg-glass"></path>
                                    <path d="M101.184 64.9985C98.4982 67.6937 95.3664 69.7808 91.8753 71.2017C88.5034 72.5735 84.9411 73.2694 81.2877 73.2694C77.6335 73.2694 74.0712 72.5735 70.7001 71.2017C67.2089 69.7808 64.0763 67.6937 61.3913 64.9985C58.7055 62.3032 56.6258 59.1604 55.2099 55.6569C53.8429 52.2731 53.1494 48.6983 53.1494 45.032C53.1494 41.365 53.8429 37.7901 55.2099 34.4071C56.6258 30.9036 58.7055 27.76 61.3913 25.0655C64.0771 22.371 67.2089 20.2832 70.7001 18.8623C74.0719 17.4906 77.6342 16.7946 81.2877 16.7946C84.9418 16.7946 88.5041 17.4906 91.8753 18.8623C95.3664 20.2832 98.499 22.3703 101.184 25.0655C103.87 27.7608 105.95 30.9036 107.365 34.4071C108.732 37.7909 109.426 41.3657 109.426 45.032C109.426 48.699 108.732 52.2739 107.365 55.6569C105.95 59.1604 103.87 62.304 101.184 64.9985ZM63.4762 27.1585C58.7188 31.9327 56.099 38.2801 56.099 45.032C56.099 51.7839 58.7188 58.1313 63.4762 62.9055C68.2336 67.6796 74.5587 70.3087 81.2869 70.3087C88.0151 70.3087 94.3403 67.6796 99.0976 62.9055C103.855 58.1313 106.475 51.7839 106.475 45.032C106.475 38.2801 103.855 31.9327 99.0976 27.1585C94.3403 22.3844 88.0151 19.7553 81.2869 19.7553C74.5595 19.7546 68.2336 22.3844 63.4762 27.1585Z" fill="#E4EAEF"></path>
                                    <path d="M65.3377 56.2175C65.6652 56.8072 65.454 57.5507 64.8672 57.8786C64.2797 58.2072 63.5388 57.9953 63.212 57.4064C62.8845 56.8168 63.095 56.074 63.6825 55.7454C64.2686 55.4175 65.0095 55.6287 65.3377 56.2175Z" fill="#95FCCE" class="mg-glass-glare"></path>
                                    <path d="M71.1466 33.9119C64.3754 40.8765 63.4463 41.6996 63.1122 52.1891C63.0959 52.694 62.8536 53.1602 62.3646 53.2843C61.883 53.407 61.3814 53.1579 61.1932 52.6962C58.1718 45.3027 59.5262 36.257 64.6481 30.1215C65.2593 29.3884 65.9891 28.656 66.9301 28.4999C68.0422 28.3147 69.0994 28.9817 70.03 29.6196C71.0999 30.352 72.7981 32.2137 71.1466 33.9119Z" fill="#95FCCE" class="mg-glass-glare"></path>
                                    <path d="M132.244 96.1683C130.211 98.2085 126.915 98.2085 124.882 96.1683L104.041 75.2538C103.464 74.6754 103.464 73.7385 104.041 73.1601L109.317 67.8655C109.893 67.287 110.827 67.287 111.403 67.8655L132.244 88.78C134.277 90.8202 134.277 94.1281 132.244 96.1683Z" fill="#313638"></path>
                                </svg>
                                <p class="fw-500 text-center">Parametrlər üzrə axtarış edin.</p>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <!-- End Step 2 -->
            </form>
            
            </div>
            <!-- End Main Form Container -->
        </div>
    </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Enqueue scripts and styles.
 */
function mida_enqueue_scripts() {
    // Enqueue Bootstrap bundle (Bootstrap CSS + Icons) from MIDA
    wp_enqueue_style('mida-bootstrap-bundle', MIDA_PLUGIN_URL . 'assets/css/bootstrap-bundle.css', array(), MIDA_VERSION, 'all');
    
    // Enqueue target website styles - pixel perfect CSS with high priority
    wp_enqueue_style('mida-target-style', MIDA_PLUGIN_URL . 'assets/css/target-styles.css', array('mida-bootstrap-bundle'), MIDA_VERSION, 'all');
    
    // Enqueue target website JavaScript functions
    wp_enqueue_script('mida-target-functions', MIDA_PLUGIN_URL . 'assets/js/target-functions.js', array('jquery'), MIDA_VERSION, true);
    
    // Enqueue custom form handling script
    wp_enqueue_script('mida-script', MIDA_PLUGIN_URL . 'assets/js/script.js', array('jquery', 'mida-target-functions'), MIDA_VERSION, true);
    
    wp_localize_script('mida-script', 'midaAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mida_house_form_submit')
    ));
}
add_action('wp_enqueue_scripts', 'mida_enqueue_scripts');

/**
 * Handle form submission via AJAX.
 */
function mida_handle_form_submission() {
    check_ajax_referer('mida_house_form_submit', 'nonce');
    
    $form_data = isset($_POST['form_data']) ? $_POST['form_data'] : array();
    
    $sanitized_data = array();
    foreach ($form_data as $key => $value) {
        $sanitized_data[sanitize_key($key)] = sanitize_text_field($value);
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'mida_submissions';
    
    $wpdb->insert(
        $table_name,
        array(
            'form_data' => json_encode($sanitized_data),
            'submitted_at' => current_time('mysql'),
            'user_ip' => $_SERVER['REMOTE_ADDR']
        ),
        array('%s', '%s', '%s')
    );
    
    wp_send_json_success(array('message' => 'Form submitted successfully'));
}
add_action('wp_ajax_mida_submit_form', 'mida_handle_form_submission');
add_action('wp_ajax_nopriv_mida_submit_form', 'mida_handle_form_submission');

/**
 * Handle apartment selection with timer data via AJAX.
 */
function mida_save_apartment_selection() {
    check_ajax_referer('mida_save_selection', 'nonce');
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'You must be logged in to select an apartment'));
        return;
    }
    
    $selection_time_ms = isset($_POST['selection_time_ms']) ? intval($_POST['selection_time_ms']) : 0;
    $selection_time_display = isset($_POST['selection_time_display']) ? sanitize_text_field($_POST['selection_time_display']) : '';
    
    // Get form selections
    $layihe = isset($_POST['layihe']) ? sanitize_text_field($_POST['layihe']) : '';
    $odenish_usulu = isset($_POST['odenish_usulu']) ? sanitize_text_field($_POST['odenish_usulu']) : '';
    $mertebe = isset($_POST['mertebe']) ? sanitize_text_field($_POST['mertebe']) : '';
    $otaq_sayi = isset($_POST['otaq_sayi']) ? sanitize_text_field($_POST['otaq_sayi']) : '';
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'mida_submissions';
    $warnings_table = $wpdb->prefix . 'mida_warnings';
    
    $user_id = get_current_user_id();
    
    // Check user restrictions
    $restrictions = get_option('mida_user_restrictions', array());
    $user_restrictions = isset($restrictions[$user_id]) ? $restrictions[$user_id] : array();
    
    $has_warning = 0;
    $warnings = array();
    
    // Check each restriction
    if (!empty($user_restrictions['layihe']) && $user_restrictions['layihe'] !== $layihe) {
        $has_warning = 1;
        $warnings[] = array(
            'type' => 'Layihə',
            'expected' => $user_restrictions['layihe'],
            'actual' => $layihe
        );
    }
    
    if (!empty($user_restrictions['odenish_usulu']) && $user_restrictions['odenish_usulu'] !== $odenish_usulu) {
        $has_warning = 1;
        $warnings[] = array(
            'type' => 'Ödəniş üsulu',
            'expected' => $user_restrictions['odenish_usulu'],
            'actual' => $odenish_usulu
        );
    }
    
    if (!empty($user_restrictions['mertebe'])) {
        $allowed_floors = mida_parse_floor_range($user_restrictions['mertebe']);
        if (!in_array($mertebe, $allowed_floors)) {
            $has_warning = 1;
            $warnings[] = array(
                'type' => 'Mərtəbə',
                'expected' => $user_restrictions['mertebe'],
                'actual' => $mertebe
            );
        }
    }
    
    if (!empty($user_restrictions['otaq_sayi'])) {
        $allowed_rooms = array_map('trim', explode(',', $user_restrictions['otaq_sayi']));
        if (!in_array($otaq_sayi, $allowed_rooms)) {
            $has_warning = 1;
            $warnings[] = array(
                'type' => 'Otaq sayı',
                'expected' => $user_restrictions['otaq_sayi'],
                'actual' => $otaq_sayi
            );
        }
    }
    
    // Insert submission
    $result = $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'selection_time_ms' => $selection_time_ms,
            'selection_time_display' => $selection_time_display,
            'layihe' => $layihe,
            'odenish_usulu' => $odenish_usulu,
            'mertebe' => $mertebe,
            'otaq_sayi' => $otaq_sayi,
            'has_warning' => $has_warning,
            'submitted_at' => current_time('mysql')
        ),
        array('%d', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%s')
    );
    
    if ($result) {
        $submission_id = $wpdb->insert_id;
        
        // Log warnings
        if (!empty($warnings)) {
            foreach ($warnings as $warning) {
                $wpdb->insert(
                    $warnings_table,
                    array(
                        'submission_id' => $submission_id,
                        'user_id' => $user_id,
                        'warning_type' => $warning['type'],
                        'expected_value' => $warning['expected'],
                        'actual_value' => $warning['actual'],
                        'created_at' => current_time('mysql')
                    ),
                    array('%d', '%d', '%s', '%s', '%s', '%s')
                );
            }
        }
        
        wp_send_json_success(array(
            'message' => 'Selection saved successfully',
            'submission_id' => $submission_id,
            'has_warning' => $has_warning,
            'warnings' => $warnings
        ));
    } else {
        wp_send_json_error(array('message' => 'Failed to save selection'));
    }
}
add_action('wp_ajax_mida_save_selection', 'mida_save_apartment_selection');
add_action('wp_ajax_nopriv_mida_save_selection', 'mida_save_apartment_selection');

/**
 * Parse floor range string (e.g., "1-5" or "1,2,3")
 */
function mida_parse_floor_range($range) {
    $floors = array();
    
    if (strpos($range, '-') !== false) {
        // Range format: "1-5"
        list($start, $end) = explode('-', $range);
        $floors = range(intval($start), intval($end));
    } else {
        // Comma-separated: "1,2,3"
        $floors = array_map('trim', explode(',', $range));
    }
    
    return array_map('strval', $floors);
}

/**
 * Get top 10 fastest selection times globally
 */
function mida_get_global_top_rankings() {
    check_ajax_referer('mida_save_selection', 'nonce');
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'mida_submissions';
    
    $results = $wpdb->get_results(
        "SELECT s.id, s.user_id, s.selection_time_ms, s.selection_time_display, s.submitted_at, u.display_name
        FROM {$table_name} s
        LEFT JOIN {$wpdb->users} u ON s.user_id = u.ID
        ORDER BY s.selection_time_ms ASC
        LIMIT 10",
        ARRAY_A
    );
    
    wp_send_json_success(array('rankings' => $results));
}
add_action('wp_ajax_mida_get_global_rankings', 'mida_get_global_top_rankings');
add_action('wp_ajax_nopriv_mida_get_global_rankings', 'mida_get_global_top_rankings');

/**
 * Get top 10 fastest selection times for current user
 */
function mida_get_user_top_rankings() {
    check_ajax_referer('mida_save_selection', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'You must be logged in'));
        return;
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'mida_submissions';
    $user_id = get_current_user_id();
    
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT id, user_id, selection_time_ms, selection_time_display, submitted_at
        FROM {$table_name}
        WHERE user_id = %d
        ORDER BY selection_time_ms ASC
        LIMIT 10",
        $user_id
    ), ARRAY_A);
    
    wp_send_json_success(array('rankings' => $results));
}
add_action('wp_ajax_mida_get_user_rankings', 'mida_get_user_top_rankings');

/**
 * Rankings display shortcode
 */
function mida_rankings_shortcode($atts) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mida_submissions';
    
    // Get global top 10 (exclude entries with warnings)
    $global_rankings = $wpdb->get_results(
        "SELECT s.id, s.user_id, s.selection_time_ms, s.selection_time_display, s.submitted_at, u.display_name
        FROM {$table_name} s
        LEFT JOIN {$wpdb->users} u ON s.user_id = u.ID
        WHERE s.has_warning = 0
        ORDER BY s.selection_time_ms ASC
        LIMIT 10",
        ARRAY_A
    );
    
    // Get user's personal top 10 if logged in (exclude entries with warnings)
    $user_rankings = array();
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $user_rankings = $wpdb->get_results($wpdb->prepare(
            "SELECT id, user_id, selection_time_ms, selection_time_display, submitted_at
            FROM {$table_name}
            WHERE user_id = %d AND has_warning = 0
            ORDER BY selection_time_ms ASC
            LIMIT 10",
            $user_id
        ), ARRAY_A);
    }
    
    ob_start();
    ?>
    <style>
        .mida-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }
        .mida-tab {
            flex: 1;
            padding: 15px 30px;
            background: transparent;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            color: #666;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }
        .mida-tab:hover {
            color: #199862;
            background: #f8f9fa;
        }
        .mida-tab.active {
            color: #199862;
            border-bottom-color: #199862;
            background: #fff;
        }
        .mida-tab-content {
            display: none;
            min-height: 500px;
            animation: fadeIn 0.3s ease-in;
        }
        .mida-tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .mida-rankings-container table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            table-layout: fixed;
        }
        .mida-rankings-container th:first-child,
        .mida-rankings-container td:first-child {
            width: 100px;
        }
        .mida-rankings-container th:last-child,
        .mida-rankings-container td:last-child {
            width: 180px;
        }
    </style>
    
    <div class="mida-rankings-container" style="max-width: 1200px; margin: 0 auto;">
        <h2 style="text-align: center; margin-bottom: 30px;">🏆 Reytinq Cədvəli</h2>
        
        <!-- Tabs Navigation -->
        <div class="mida-tabs">
            <button class="mida-tab active" onclick="switchTab('global')">🌍 Ümumi Top 10</button>
            <?php if (is_user_logged_in()): ?>
                <button class="mida-tab" onclick="switchTab('personal')">⭐ Şəxsi Top 10</button>
            <?php endif; ?>
        </div>
        
        <!-- Global Top 10 Tab -->
        <div id="global-tab" class="mida-tab-content active">
            <table style="width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background: #199862; color: white;">
                        <th style="padding: 15px; text-align: center; width: 100px;">Sıra</th>
                        <th style="padding: 15px; text-align: left;">İstifadəçi</th>
                        <th style="padding: 15px; text-align: center;">Vaxt</th>
                        <th style="padding: 15px; text-align: center;">Tarix</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (!empty($global_rankings)) {
                        $rank = 1;
                        foreach ($global_rankings as $record) {
                            $medal = '';
                            if ($rank == 1) $medal = '🥇';
                            elseif ($rank == 2) $medal = '🥈';
                            elseif ($rank == 3) $medal = '🥉';
                            
                            $bg_color = ($rank % 2 == 0) ? '#f9f9f9' : '#ffffff';
                            ?>
                            <tr style="background: <?php echo $bg_color; ?>; border-bottom: 1px solid #ddd;">
                                <td style="padding: 15px; text-align: center; font-weight: bold;">
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <?php if ($medal): ?>
                                            <span style="font-size: 24px;"><?php echo $medal; ?></span>
                                        <?php endif; ?>
                                        <span><?php echo $rank; ?></span>
                                    </div>
                                </td>
                                <td style="padding: 15px;"><?php echo esc_html($record['display_name']); ?></td>
                                <td style="padding: 15px; text-align: center; font-family: monospace; font-weight: bold; color: #199862;"><?php echo esc_html($record['selection_time_display']); ?></td>
                                <td style="padding: 15px; text-align: center;"><?php echo date('d.m.Y H:i', strtotime($record['submitted_at'])); ?></td>
                            </tr>
                            <?php
                            $rank++;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4" style="padding: 30px; text-align: center; color: #999;">Hələ ki nəticə yoxdur</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <?php if (is_user_logged_in()): ?>
        <!-- Personal Top 10 Tab -->
        <div id="personal-tab" class="mida-tab-content">
            <table style="width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background: #28a745; color: white;">
                        <th style="padding: 15px; text-align: center; width: 100px;">Sıra</th>
                        <th style="padding: 15px; text-align: center;">Vaxt</th>
                        <th style="padding: 15px; text-align: center;">Tarix</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (!empty($user_rankings)) {
                        $rank = 1;
                        foreach ($user_rankings as $record) {
                            $bg_color = ($rank % 2 == 0) ? '#f9f9f9' : '#ffffff';
                            ?>
                            <tr style="background: <?php echo $bg_color; ?>; border-bottom: 1px solid #ddd;">
                                <td style="padding: 15px; text-align: center; font-weight: bold;"><?php echo $rank; ?></td>
                                <td style="padding: 15px; text-align: center; font-family: monospace; font-weight: bold; color: #28a745;"><?php echo esc_html($record['selection_time_display']); ?></td>
                                <td style="padding: 15px; text-align: center;"><?php echo date('d.m.Y H:i', strtotime($record['submitted_at'])); ?></td>
                            </tr>
                            <?php
                            $rank++;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="3" style="padding: 30px; text-align: center; color: #999;">Hələ ki nəticəniz yoxdur. İlk cəhdinizi edin!</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.mida-tab-content').forEach(function(content) {
            content.classList.remove('active');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.mida-tab').forEach(function(tab) {
            tab.classList.remove('active');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-tab').classList.add('active');
        
        // Add active class to clicked tab
        event.target.classList.add('active');
    }
    </script>
    <?php
    return ob_get_clean();
}

/**
 * Debug database shortcode - shows raw database data
 */
function mida_debug_db_shortcode() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mida_submissions';
    
    // Check if table exists
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'");
    
    ob_start();
    ?>
    <div style="background: #f5f5f5; padding: 20px; margin: 20px 0; border: 2px solid #ddd;">
        <h3>🔍 Database Debug Info</h3>
        
        <p><strong>Table Name:</strong> <?php echo esc_html($table_name); ?></p>
        <p><strong>Table Exists:</strong> <?php echo $table_exists ? '✅ Yes' : '❌ No'; ?></p>
        
        <?php if ($table_exists): 
            // Get table structure
            $columns = $wpdb->get_results("DESCRIBE {$table_name}", ARRAY_A);
            ?>
            
            <h4>Table Structure:</h4>
            <pre style="background: white; padding: 10px; overflow-x: auto;"><?php print_r($columns); ?></pre>
            
            <h4>Last 10 Records:</h4>
            <?php
            $records = $wpdb->get_results("SELECT * FROM {$table_name} ORDER BY id DESC LIMIT 10", ARRAY_A);
            if (!empty($records)) {
                ?>
                <pre style="background: white; padding: 10px; overflow-x: auto;"><?php print_r($records); ?></pre>
                <?php
            } else {
                echo '<p style="color: red;">❌ No records found in table</p>';
            }
            
            // Count records
            $count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
            echo '<p><strong>Total Records:</strong> ' . $count . '</p>';
            
            // Check for errors
            if ($wpdb->last_error) {
                echo '<p style="color: red;"><strong>Database Error:</strong> ' . esc_html($wpdb->last_error) . '</p>';
            }
        endif;
        ?>
    </div>
    <?php
    return ob_get_clean();
}

