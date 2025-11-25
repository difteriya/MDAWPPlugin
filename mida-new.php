<?php
/**
 * Plugin Name: Mida
 * Plugin URI: https://example.com/mida
 * Description: A custom WordPress plugin - Pixel Perfect MIDA Form
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
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
define('MIDA_VERSION', '1.0.0');
define('MIDA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MIDA_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_mida() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mida_submissions';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        form_data longtext NOT NULL,
        submitted_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        user_ip varchar(100) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
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
}
add_action('plugins_loaded', 'mida_init');

/**
 * Multi-step house picking form shortcode - EXACT HTML FROM TARGET
 */
function mida_house_form_shortcode($atts) {
    ob_start();
    ?>
    <!-- EXACT HTML FROM MIDA TARGET WEBSITE -->
    <div class="cont body-content">
    
    <!-- Optional watermark - remove if not needed -->
    <div id="watermark" style="width: 100%; height: 100vh; position: fixed; top: 0; left: 0; z-index: 1; opacity: 0.15; pointer-events: none; display: flex; justify-content: space-evenly; flex-direction: column;">
        <p style="display: flex;justify-content: space-evenly;">
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
        </p>
        <p style="display: flex;justify-content: space-evenly;">
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
        </p>
        <p style="display: flex;justify-content: space-evenly;">
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
        </p>
        <p style="display: flex;justify-content: space-evenly;">
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
        </p>
        <p style="display: flex;justify-content: space-evenly;">
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
        </p>
        <p style="display: flex;justify-content: space-evenly;">
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
            <span style="font-size: 2rem;">SINAQ</span>
        </p>
    </div>
    
    <div id="options" class="w-100">
        <div id="page-loading-spinner" class="overflow-hidden d-flex justify-content-center align-items-center loader-container" style="z-index: 1000000000; display: none;">
            <div class="loader"></div>
        </div>
        
        <div class="editor-content">
            <!-- Breadcrumb Navigation - EXACT FROM TARGET -->
            <div class="breadcrumb mt-4 mb-5 px-3">
                <div class="bc-item success pending">
                    <div class="d-flex w-100 align-items-center test">
                        <div class="bc-circle">
                            <span>1</span>
                            <small class="mt-2 breadcrumb-title">Seçimlər</small>
                        </div>
                        <div class="bc-line2 w-100"></div>
                    </div>
                </div>
                
                <div class="bc-item success" style="display: none;">
                    <div class="d-flex w-100 align-items-center test">
                        <div class="bc-circle">
                            <span>0</span>
                            <small class="mt-2 breadcrumb-title">Axtarış</small>
                        </div>
                        <div class="bc-line2 w-100"></div>
                    </div>
                </div>
                
                <div class="bc-item success">
                    <div class="d-flex w-100 align-items-center test">
                        <div class="bc-circle">
                            <span>2</span>
                            <small class="mt-2 breadcrumb-title">Mənzil</small>
                        </div>
                        <div class="bc-line2 w-100"></div>
                    </div>
                </div>
                
                <div class="bc-item success flex-grow-0 min-w-0">
                    <div class="d-flex align-items-center">
                        <div class="bc-circle">
                            <span>3</span>
                            <small class="mt-2 breadcrumb-title">Ərizə</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form - EXACT FROM TARGET -->
            <form action="" id="options" method="post" class="w-100" style="margin-top: 4.5rem;">
                <?php wp_nonce_field('mida_house_form_submit', 'mida_house_form_nonce'); ?>
                
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
                                        <option value="e3eec46c-8f80-42e8-b31c-334efface7c2" disabled="disabled">Yasamal Yaşayış Kompleksi</option>
                                        <option value="5c1b506e-c751-4252-8d6b-5a78a89458fa">Hövsan Yaşayış Kompleksi</option>
                                        <option value="3217e53d-8023-4610-8e5c-7b20683174fb" disabled="disabled">Sumqayıt şəhərində güzəştli mənzillər</option>
                                        <option value="f3ffc990-85c9-4653-bc56-2075f4cce7be" disabled="disabled">Gəncə Yaşayış Kompleksi</option>
                                        <option value="719a8862-4827-4485-829c-fc4f81b7d100" disabled="disabled">Yasamal Yaşayış Kompleksinin ikinci mərhələsi</option>
                                        <option value="4bd99e4b-59b1-4d75-ab18-c7f9d6654882">Hövsan Yaşayış Kompleksinin ikinci mərhələsi</option>
                                        <option value="f72b646c-5ae2-4451-850d-317840567ccc">Lənkəran Yaşayış Kompleksi</option>
                                        <option value="cd552f9e-a16f-44ed-a900-2646e3b9f816">Sumqayıt Yaşayış Kompleksi</option>
                                        <option value="25f4cebc-4469-4de6-9993-828c52367e1b">Binəqədi Yaşayış Kompleksi</option>
                                        <option value="9788c664-aab1-4701-a868-e0b01ffa8ddf">Şirvan Yaşayış Kompleksi</option>
                                        <option value="d310da24-a222-466e-924e-fd7c12531934">Yevlax Yaşayış Kompleksi</option>
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
                                                <input type="radio" name="payment-selectionnull" id="cash-payment-choice" disabled="disabled" autocomplete="off" class="form-check-input radio-input" value="Cash">
                                                <label for="cash-payment-choice">Öz vəsaiti hesabına</label>
                                            </div>
                                            <div class="d-flex gap-3 position-relative">
                                                <input type="radio" name="payment-selectionnull" id="loan-payment-choice" disabled="disabled" autocomplete="off" class="form-check-input radio-input" value="Loan">
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
                                                <input v:checked="explorer == 'Map'" type="radio" name="flat-selection" id="explorer-map" autocomplete="off" class="form-check-input radio-input">
                                                <label for="explorer-map">Xəritə üzərində</label>
                                            </div>
                                            <div class="d-flex gap-3">
                                                <input v:checked="explorer == 'Search'" type="radio" name="flat-selection" id="explorer-search" autocomplete="off" class="form-check-input radio-input">
                                                <label for="explorer-search">Parametrlər üzrə</label>
                                            </div>
                                            <div class="d-flex gap-3">
                                                <input v:checked="explorer == 'Address'" type="radio" name="flat-selection" id="explorer-address" autocomplete="off" class="form-check-input radio-input">
                                                <label for="explorer-address">Ünvan üzrə</label>
                                            </div>
                                        </div>
                                    </div>
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
                
                <!-- Warning Alert -->
                <div role="alert" class="alert alert-warning flex-column my-0 my-4">
                    <div class="d-flex gap-3 m-0">
                        <div style="line-height: 0;">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="svg-icon-size-2">
                                <g clip-path="url(#clip0_2237_1442)">
                                    <path d="M13.473 2.34795C13.3246 2.0893 13.1105 1.8744 12.8524 1.72496C12.5942 1.57552 12.3013 1.49683 12.003 1.49683C11.7048 1.49683 11.4118 1.57552 11.1537 1.72496C10.8956 1.8744 10.6815 2.0893 10.533 2.34795L0.247541 19.8485C-0.437959 21.0155 0.384041 22.499 1.71754 22.499H22.287C23.6205 22.499 24.444 21.014 23.757 19.8485L13.473 2.34795ZM12 7.49895C12.8025 7.49895 13.431 8.19195 13.35 8.99145L12.825 14.252C12.8074 14.4586 12.7128 14.6511 12.5601 14.7914C12.4073 14.9317 12.2075 15.0095 12 15.0095C11.7926 15.0095 11.5928 14.9317 11.44 14.7914C11.2872 14.6511 11.1927 14.4586 11.175 14.252L10.65 8.99145C10.6312 8.8028 10.6521 8.61229 10.7113 8.4322C10.7706 8.25211 10.8669 8.08642 10.9941 7.94582C11.1212 7.80521 11.2765 7.69281 11.4497 7.61584C11.623 7.53888 11.8105 7.49906 12 7.49895V7.49895ZM12.003 16.499C12.4009 16.499 12.7824 16.657 13.0637 16.9383C13.345 17.2196 13.503 17.6011 13.503 17.999C13.503 18.3968 13.345 18.7783 13.0637 19.0596C12.7824 19.3409 12.4009 19.499 12.003 19.499C11.6052 19.499 11.2237 19.3409 10.9424 19.0596C10.6611 18.7783 10.503 18.3968 10.503 17.999C10.503 17.6011 10.6611 17.2196 10.9424 16.9383C11.2237 16.657 11.6052 16.499 12.003 16.499Z" fill="#FAA309"></path>
                                </g>
                            </svg>
                        </div>
                        <p class="w-100 fs-6">DİQQƏT! Mənzil seçimi prosesində əlavə təhlükəsizlik tədbirləri tətbiq edilə bilər.</p>
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
                        <button type="submit" disabled="disabled" class="mb-0 btn btn-success gap-3">
                            Növbəti&nbsp;&nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#e8eaed" class="svg-icon-size-1">
                                <path d="m321-80-71-71 329-329-329-329 71-71 400 400L321-80Z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
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
    // Enqueue target website styles - pixel perfect CSS with high priority
    wp_enqueue_style('mida-target-style', MIDA_PLUGIN_URL . 'assets/css/target-styles.css', array(), MIDA_VERSION, 'all');
    
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
