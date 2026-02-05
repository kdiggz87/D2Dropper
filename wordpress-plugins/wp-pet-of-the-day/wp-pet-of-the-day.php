<?php
/**
 * Plugin Name: Pet of the Day
 * Plugin URI: https://github.com/kdiggz87/D2Dropper
 * Description: Displays a randomly selected "Pet of the Day" from all user pets. Requires the User Pets Manager plugin.
 * Version: 1.0.0
 * Author: D2Dropper
 * Author URI: https://github.com/kdiggz87/D2Dropper
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-pet-of-the-day
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WP_POD_VERSION', '1.0.0');
define('WP_POD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_POD_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WP_POD_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Plugin Class
 */
class WP_Pet_Of_The_Day {
    
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
        $this->check_dependencies();
        $this->includes();
        $this->init_hooks();
    }
    
    /**
     * Check if User Pets plugin is active
     */
    private function check_dependencies() {
        add_action('admin_notices', array($this, 'dependency_notice'));
    }
    
    /**
     * Display dependency notice
     */
    public function dependency_notice() {
        if (!class_exists('WP_User_Pets_Database')) {
            ?>
            <div class="notice notice-warning">
                <p>
                    <?php _e('Pet of the Day plugin requires the User Pets Manager plugin to be installed and activated.', 'wp-pet-of-the-day'); ?>
                </p>
            </div>
            <?php
        }
    }
    
    /**
     * Include required files
     */
    private function includes() {
        if (class_exists('WP_User_Pets_Database')) {
            require_once WP_POD_PLUGIN_DIR . 'includes/class-pet-selector.php';
            if (is_admin()) {
                require_once WP_POD_PLUGIN_DIR . 'admin/class-admin.php';
            }
        }
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        if (class_exists('WP_User_Pets_Database')) {
            // Initialize pet selector
            WP_POD_Selector::get_instance();
            
            // Initialize admin
            if (is_admin()) {
                WP_POD_Admin::get_instance();
            }
            
            // Register shortcode
            add_shortcode('pet_of_the_day', array($this, 'pet_of_the_day_shortcode'));
        }
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'wp-pet-of-the-day',
            WP_POD_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            WP_POD_VERSION
        );
    }
    
    /**
     * Pet of the Day shortcode
     */
    public function pet_of_the_day_shortcode($atts) {
        if (!class_exists('WP_User_Pets_Database')) {
            return '<p>' . __('Pet of the Day requires the User Pets Manager plugin.', 'wp-pet-of-the-day') . '</p>';
        }
        
        $atts = shortcode_atts(array(
            'title' => 'Pet of the Day',
            'show_owner' => 'yes',
            'show_description' => 'yes'
        ), $atts);
        
        $pet = WP_POD_Selector::get_pet_of_the_day();
        
        if (!$pet) {
            return '<div class="pet-of-the-day no-pet"><p>' . __('No pets available yet. Be the first to add your pet!', 'wp-pet-of-the-day') . '</p></div>';
        }
        
        $owner = get_userdata($pet->user_id);
        $owner_name = $owner ? $owner->display_name : __('Unknown', 'wp-pet-of-the-day');
        
        ob_start();
        ?>
        <div class="pet-of-the-day">
            <?php if (!empty($atts['title'])) : ?>
                <h2 class="pod-title"><?php echo esc_html($atts['title']); ?></h2>
            <?php endif; ?>
            
            <div class="pod-container">
                <?php if (!empty($pet->pet_image_url)) : ?>
                    <div class="pod-image">
                        <img src="<?php echo esc_url($pet->pet_image_url); ?>" alt="<?php echo esc_attr($pet->pet_name); ?>">
                    </div>
                <?php endif; ?>
                
                <div class="pod-info">
                    <h3 class="pod-pet-name"><?php echo esc_html($pet->pet_name); ?></h3>
                    
                    <div class="pod-details">
                        <p><strong><?php _e('Type:', 'wp-pet-of-the-day'); ?></strong> <?php echo esc_html($pet->pet_type); ?></p>
                        
                        <?php if (!empty($pet->pet_breed)) : ?>
                            <p><strong><?php _e('Breed:', 'wp-pet-of-the-day'); ?></strong> <?php echo esc_html($pet->pet_breed); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($pet->pet_age)) : ?>
                            <p><strong><?php _e('Age:', 'wp-pet-of-the-day'); ?></strong> <?php echo esc_html($pet->pet_age); ?></p>
                        <?php endif; ?>
                        
                        <?php if ($atts['show_owner'] === 'yes') : ?>
                            <p><strong><?php _e('Owner:', 'wp-pet-of-the-day'); ?></strong> <?php echo esc_html($owner_name); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($atts['show_description'] === 'yes' && !empty($pet->pet_description)) : ?>
                        <div class="pod-description">
                            <p><?php echo esc_html($pet->pet_description); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

/**
 * Initialize the plugin
 */
function wp_pet_of_the_day_init() {
    return WP_Pet_Of_The_Day::get_instance();
}

// Start the plugin
wp_pet_of_the_day_init();
