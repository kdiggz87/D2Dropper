<?php
/**
 * Plugin Name: User Pets Manager
 * Plugin URI: https://github.com/kdiggz87/D2Dropper
 * Description: Allows registered users to add and manage their pets on their account. Users can add multiple pets with photos and information.
 * Version: 1.0.0
 * Author: D2Dropper
 * Author URI: https://github.com/kdiggz87/D2Dropper
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-user-pets
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WP_USER_PETS_VERSION', '1.0.0');
define('WP_USER_PETS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_USER_PETS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WP_USER_PETS_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Plugin Class
 */
class WP_User_Pets {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }
    
    /**
     * Include required files
     */
    private function includes() {
        require_once WP_USER_PETS_PLUGIN_DIR . 'includes/class-database.php';
        require_once WP_USER_PETS_PLUGIN_DIR . 'includes/class-pet.php';
        require_once WP_USER_PETS_PLUGIN_DIR . 'admin/class-admin.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        WP_User_Pets_Database::create_tables();
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize admin functionality
        if (is_admin()) {
            WP_User_Pets_Admin::get_instance();
        }
        
        // Initialize pet management
        WP_User_Pets_Pet::get_instance();
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'wp-user-pets',
            WP_USER_PETS_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            WP_USER_PETS_VERSION
        );
        
        wp_enqueue_script(
            'wp-user-pets',
            WP_USER_PETS_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            WP_USER_PETS_VERSION,
            true
        );
        
        wp_localize_script('wp-user-pets', 'wpUserPets', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_user_pets_nonce')
        ));
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'wp-user-pets') === false && $hook !== 'profile.php' && $hook !== 'user-edit.php') {
            return;
        }
        
        wp_enqueue_media();
        
        wp_enqueue_style(
            'wp-user-pets-admin',
            WP_USER_PETS_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            WP_USER_PETS_VERSION
        );
        
        wp_enqueue_script(
            'wp-user-pets-admin',
            WP_USER_PETS_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'wp-util'),
            WP_USER_PETS_VERSION,
            true
        );
        
        wp_localize_script('wp-user-pets-admin', 'wpUserPetsAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_user_pets_admin_nonce'),
            'strings' => array(
                'confirmDelete' => __('Are you sure you want to delete this pet?', 'wp-user-pets'),
                'uploadImage' => __('Upload Image', 'wp-user-pets'),
                'selectImage' => __('Select Image', 'wp-user-pets')
            )
        ));
    }
}

/**
 * Initialize the plugin
 */
function wp_user_pets_init() {
    return WP_User_Pets::get_instance();
}

// Start the plugin
wp_user_pets_init();
