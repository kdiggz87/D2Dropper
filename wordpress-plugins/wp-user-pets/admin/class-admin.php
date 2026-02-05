<?php
/**
 * Admin functionality class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WP_User_Pets_Admin {
    
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
        add_action('show_user_profile', array($this, 'show_user_pets_section'));
        add_action('edit_user_profile', array($this, 'show_user_pets_section'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('User Pets', 'wp-user-pets'),
            __('User Pets', 'wp-user-pets'),
            'manage_options',
            'wp-user-pets',
            array($this, 'admin_page'),
            'dashicons-pets',
            30
        );
        
        add_submenu_page(
            'wp-user-pets',
            __('All Pets', 'wp-user-pets'),
            __('All Pets', 'wp-user-pets'),
            'manage_options',
            'wp-user-pets',
            array($this, 'admin_page')
        );
        
        add_submenu_page(
            'wp-user-pets',
            __('Settings', 'wp-user-pets'),
            __('Settings', 'wp-user-pets'),
            'manage_options',
            'wp-user-pets-settings',
            array($this, 'settings_page')
        );
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        $pets = WP_User_Pets_Database::get_all_pets();
        $total_count = WP_User_Pets_Database::get_total_pets_count();
        ?>
        <div class="wrap">
            <h1><?php _e('User Pets', 'wp-user-pets'); ?></h1>
            
            <div class="wp-user-pets-stats">
                <p><?php printf(__('Total Pets: %d', 'wp-user-pets'), $total_count); ?></p>
            </div>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('ID', 'wp-user-pets'); ?></th>
                        <th><?php _e('Photo', 'wp-user-pets'); ?></th>
                        <th><?php _e('Pet Name', 'wp-user-pets'); ?></th>
                        <th><?php _e('Type', 'wp-user-pets'); ?></th>
                        <th><?php _e('Breed', 'wp-user-pets'); ?></th>
                        <th><?php _e('Owner', 'wp-user-pets'); ?></th>
                        <th><?php _e('Added', 'wp-user-pets'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pets)) : ?>
                        <tr>
                            <td colspan="7"><?php _e('No pets found.', 'wp-user-pets'); ?></td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($pets as $pet) : ?>
                            <?php
                            $user = get_userdata($pet->user_id);
                            $user_name = $user ? $user->display_name : __('Unknown User', 'wp-user-pets');
                            ?>
                            <tr>
                                <td><?php echo esc_html($pet->id); ?></td>
                                <td>
                                    <?php if (!empty($pet->pet_image_url)) : ?>
                                        <img src="<?php echo esc_url($pet->pet_image_url); ?>" alt="<?php echo esc_attr($pet->pet_name); ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php else : ?>
                                        <span>—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo esc_html($pet->pet_name); ?></td>
                                <td><?php echo esc_html($pet->pet_type); ?></td>
                                <td><?php echo esc_html($pet->pet_breed); ?></td>
                                <td>
                                    <a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $pet->user_id)); ?>">
                                        <?php echo esc_html($user_name); ?>
                                    </a>
                                </td>
                                <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($pet->created_at))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    /**
     * Settings page
     */
    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('User Pets Settings', 'wp-user-pets'); ?></h1>
            
            <div class="card">
                <h2><?php _e('Shortcodes', 'wp-user-pets'); ?></h2>
                <p><?php _e('Use these shortcodes to display pet management features:', 'wp-user-pets'); ?></p>
                <ul>
                    <li><code>[user_pets]</code> - <?php _e('Display user's pets with add/edit/delete form', 'wp-user-pets'); ?></li>
                    <li><code>[user_pets show_form="no"]</code> - <?php _e('Display only the user's pets list', 'wp-user-pets'); ?></li>
                    <li><code>[user_pets_form]</code> - <?php _e('Display only the add pet form', 'wp-user-pets'); ?></li>
                </ul>
            </div>
            
            <div class="card">
                <h2><?php _e('How to Use', 'wp-user-pets'); ?></h2>
                <ol>
                    <li><?php _e('Create a page where users can manage their pets', 'wp-user-pets'); ?></li>
                    <li><?php _e('Add the shortcode [user_pets] to that page', 'wp-user-pets'); ?></li>
                    <li><?php _e('Registered users will be able to add, edit, and delete their pets', 'wp-user-pets'); ?></li>
                    <li><?php _e('Install the "Pet of the Day" plugin to display a random pet daily', 'wp-user-pets'); ?></li>
                </ol>
            </div>
            
            <div class="card">
                <h2><?php _e('Plugin Information', 'wp-user-pets'); ?></h2>
                <p><strong><?php _e('Version:', 'wp-user-pets'); ?></strong> <?php echo WP_USER_PETS_VERSION; ?></p>
                <p><strong><?php _e('Total Pets:', 'wp-user-pets'); ?></strong> <?php echo WP_User_Pets_Database::get_total_pets_count(); ?></p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Show user pets section on profile page
     */
    public function show_user_pets_section($user) {
        $pets = WP_User_Pets_Database::get_user_pets($user->ID);
        ?>
        <h2><?php _e('User Pets', 'wp-user-pets'); ?></h2>
        <table class="form-table">
            <tr>
                <th><?php _e('Pets', 'wp-user-pets'); ?></th>
                <td>
                    <?php if (empty($pets)) : ?>
                        <p><?php _e('This user has not added any pets yet.', 'wp-user-pets'); ?></p>
                    <?php else : ?>
                        <table class="widefat">
                            <thead>
                                <tr>
                                    <th><?php _e('Photo', 'wp-user-pets'); ?></th>
                                    <th><?php _e('Name', 'wp-user-pets'); ?></th>
                                    <th><?php _e('Type', 'wp-user-pets'); ?></th>
                                    <th><?php _e('Breed', 'wp-user-pets'); ?></th>
                                    <th><?php _e('Age', 'wp-user-pets'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pets as $pet) : ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($pet->pet_image_url)) : ?>
                                                <img src="<?php echo esc_url($pet->pet_image_url); ?>" alt="<?php echo esc_attr($pet->pet_name); ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else : ?>
                                                —
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo esc_html($pet->pet_name); ?></td>
                                        <td><?php echo esc_html($pet->pet_type); ?></td>
                                        <td><?php echo esc_html($pet->pet_breed ? $pet->pet_breed : '—'); ?></td>
                                        <td><?php echo esc_html($pet->pet_age ? $pet->pet_age : '—'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <?php
    }
}
