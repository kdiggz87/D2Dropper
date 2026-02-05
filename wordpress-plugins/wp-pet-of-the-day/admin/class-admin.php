<?php
/**
 * Admin functionality class for Pet of the Day
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WP_POD_Admin {
    
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
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_post_wp_pod_force_refresh', array($this, 'handle_force_refresh'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'wp-user-pets',
            __('Pet of the Day', 'wp-pet-of-the-day'),
            __('Pet of the Day', 'wp-pet-of-the-day'),
            'manage_options',
            'wp-pet-of-the-day',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        $current_pet = WP_POD_Selector::get_pet_of_the_day();
        $total_pets = WP_User_Pets_Database::get_total_pets_count();
        $history = WP_POD_Selector::get_history(10);
        ?>
        <div class="wrap">
            <h1><?php _e('Pet of the Day', 'wp-pet-of-the-day'); ?></h1>
            
            <?php if (isset($_GET['refreshed']) && $_GET['refreshed'] === '1') : ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e('Pet of the Day has been refreshed!', 'wp-pet-of-the-day'); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <h2><?php _e('Current Pet of the Day', 'wp-pet-of-the-day'); ?></h2>
                
                <?php if ($current_pet) : ?>
                    <?php $owner = get_userdata($current_pet->user_id); ?>
                    <div class="pod-admin-current">
                        <?php if (!empty($current_pet->pet_image_url)) : ?>
                            <img src="<?php echo esc_url($current_pet->pet_image_url); ?>" alt="<?php echo esc_attr($current_pet->pet_name); ?>" style="max-width: 300px; height: auto; border-radius: 8px; margin-bottom: 15px;">
                        <?php endif; ?>
                        
                        <p><strong><?php _e('Name:', 'wp-pet-of-the-day'); ?></strong> <?php echo esc_html($current_pet->pet_name); ?></p>
                        <p><strong><?php _e('Type:', 'wp-pet-of-the-day'); ?></strong> <?php echo esc_html($current_pet->pet_type); ?></p>
                        <?php if (!empty($current_pet->pet_breed)) : ?>
                            <p><strong><?php _e('Breed:', 'wp-pet-of-the-day'); ?></strong> <?php echo esc_html($current_pet->pet_breed); ?></p>
                        <?php endif; ?>
                        <p><strong><?php _e('Owner:', 'wp-pet-of-the-day'); ?></strong> <?php echo $owner ? esc_html($owner->display_name) : __('Unknown', 'wp-pet-of-the-day'); ?></p>
                    </div>
                    
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="wp_pod_force_refresh">
                        <?php wp_nonce_field('wp_pod_force_refresh', 'wp_pod_nonce'); ?>
                        <p>
                            <button type="submit" class="button button-secondary"><?php _e('Select New Pet Now', 'wp-pet-of-the-day'); ?></button>
                        </p>
                    </form>
                <?php else : ?>
                    <p><?php _e('No pet selected yet. A pet will be automatically selected once pets are added.', 'wp-pet-of-the-day'); ?></p>
                    
                    <?php if ($total_pets > 0) : ?>
                        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                            <input type="hidden" name="action" value="wp_pod_force_refresh">
                            <?php wp_nonce_field('wp_pod_force_refresh', 'wp_pod_nonce'); ?>
                            <p>
                                <button type="submit" class="button button-primary"><?php _e('Select Pet of the Day', 'wp-pet-of-the-day'); ?></button>
                            </p>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <h2><?php _e('Statistics', 'wp-pet-of-the-day'); ?></h2>
                <p><?php printf(__('Total Pets Available: %d', 'wp-pet-of-the-day'), $total_pets); ?></p>
            </div>
            
            <?php if (!empty($history)) : ?>
                <div class="card">
                    <h2><?php _e('Selection History (Last 10)', 'wp-pet-of-the-day'); ?></h2>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('Pet Name', 'wp-pet-of-the-day'); ?></th>
                                <th><?php _e('Owner', 'wp-pet-of-the-day'); ?></th>
                                <th><?php _e('Date Selected', 'wp-pet-of-the-day'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $entry) : ?>
                                <?php
                                $pet = WP_User_Pets_Database::get_pet($entry['pet_id']);
                                if (!$pet) continue;
                                $owner = get_userdata($pet->user_id);
                                ?>
                                <tr>
                                    <td><?php echo esc_html($pet->pet_name); ?></td>
                                    <td><?php echo $owner ? esc_html($owner->display_name) : __('Unknown', 'wp-pet-of-the-day'); ?></td>
                                    <td><?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($entry['date']))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <h2><?php _e('Shortcode', 'wp-pet-of-the-day'); ?></h2>
                <p><?php _e('Use this shortcode to display the Pet of the Day on any page or post:', 'wp-pet-of-the-day'); ?></p>
                <p><code>[pet_of_the_day]</code></p>
                
                <h3><?php _e('Shortcode Options:', 'wp-pet-of-the-day'); ?></h3>
                <ul>
                    <li><code>[pet_of_the_day title="Meet Today's Featured Pet"]</code> - <?php _e('Custom title', 'wp-pet-of-the-day'); ?></li>
                    <li><code>[pet_of_the_day show_owner="no"]</code> - <?php _e('Hide owner name', 'wp-pet-of-the-day'); ?></li>
                    <li><code>[pet_of_the_day show_description="no"]</code> - <?php _e('Hide description', 'wp-pet-of-the-day'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }
    
    /**
     * Handle force refresh
     */
    public function handle_force_refresh() {
        check_admin_referer('wp_pod_force_refresh', 'wp_pod_nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to do this.', 'wp-pet-of-the-day'));
        }
        
        WP_POD_Selector::force_refresh();
        
        wp_redirect(add_query_arg('refreshed', '1', admin_url('admin.php?page=wp-pet-of-the-day')));
        exit;
    }
}
