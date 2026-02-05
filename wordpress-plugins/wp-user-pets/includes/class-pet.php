<?php
/**
 * Pet management class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WP_User_Pets_Pet {
    
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
        // AJAX actions for logged-in users
        add_action('wp_ajax_wp_user_pets_add', array($this, 'ajax_add_pet'));
        add_action('wp_ajax_wp_user_pets_update', array($this, 'ajax_update_pet'));
        add_action('wp_ajax_wp_user_pets_delete', array($this, 'ajax_delete_pet'));
        add_action('wp_ajax_wp_user_pets_get', array($this, 'ajax_get_pets'));
        
        // Shortcode for displaying user's pets
        add_shortcode('user_pets', array($this, 'user_pets_shortcode'));
        add_shortcode('user_pets_form', array($this, 'user_pets_form_shortcode'));
    }
    
    /**
     * AJAX: Add pet
     */
    public function ajax_add_pet() {
        check_ajax_referer('wp_user_pets_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('You must be logged in to add a pet.', 'wp-user-pets')));
        }
        
        $user_id = get_current_user_id();
        
        $required_fields = array('pet_name', 'pet_type');
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                wp_send_json_error(array('message' => sprintf(__('%s is required.', 'wp-user-pets'), ucfirst(str_replace('_', ' ', $field)))));
            }
        }
        
        $data = array(
            'user_id' => $user_id,
            'pet_name' => $_POST['pet_name'],
            'pet_type' => $_POST['pet_type'],
            'pet_breed' => isset($_POST['pet_breed']) ? $_POST['pet_breed'] : '',
            'pet_age' => isset($_POST['pet_age']) ? $_POST['pet_age'] : '',
            'pet_description' => isset($_POST['pet_description']) ? $_POST['pet_description'] : '',
            'pet_image_url' => isset($_POST['pet_image_url']) ? $_POST['pet_image_url'] : ''
        );
        
        $pet_id = WP_User_Pets_Database::add_pet($data);
        
        if ($pet_id) {
            wp_send_json_success(array(
                'message' => __('Pet added successfully!', 'wp-user-pets'),
                'pet_id' => $pet_id
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to add pet.', 'wp-user-pets')));
        }
    }
    
    /**
     * AJAX: Update pet
     */
    public function ajax_update_pet() {
        check_ajax_referer('wp_user_pets_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('You must be logged in to update a pet.', 'wp-user-pets')));
        }
        
        if (empty($_POST['pet_id'])) {
            wp_send_json_error(array('message' => __('Pet ID is required.', 'wp-user-pets')));
        }
        
        $pet_id = intval($_POST['pet_id']);
        $pet = WP_User_Pets_Database::get_pet($pet_id);
        
        if (!$pet) {
            wp_send_json_error(array('message' => __('Pet not found.', 'wp-user-pets')));
        }
        
        // Verify ownership
        if ($pet->user_id != get_current_user_id()) {
            wp_send_json_error(array('message' => __('You do not have permission to update this pet.', 'wp-user-pets')));
        }
        
        $data = array();
        $allowed_fields = array('pet_name', 'pet_type', 'pet_breed', 'pet_age', 'pet_description', 'pet_image_url');
        
        foreach ($allowed_fields as $field) {
            if (isset($_POST[$field])) {
                $data[$field] = $_POST[$field];
            }
        }
        
        $result = WP_User_Pets_Database::update_pet($pet_id, $data);
        
        if ($result !== false) {
            wp_send_json_success(array('message' => __('Pet updated successfully!', 'wp-user-pets')));
        } else {
            wp_send_json_error(array('message' => __('Failed to update pet.', 'wp-user-pets')));
        }
    }
    
    /**
     * AJAX: Delete pet
     */
    public function ajax_delete_pet() {
        check_ajax_referer('wp_user_pets_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('You must be logged in to delete a pet.', 'wp-user-pets')));
        }
        
        if (empty($_POST['pet_id'])) {
            wp_send_json_error(array('message' => __('Pet ID is required.', 'wp-user-pets')));
        }
        
        $pet_id = intval($_POST['pet_id']);
        $user_id = get_current_user_id();
        
        $result = WP_User_Pets_Database::delete_pet($pet_id, $user_id);
        
        if ($result) {
            wp_send_json_success(array('message' => __('Pet deleted successfully!', 'wp-user-pets')));
        } else {
            wp_send_json_error(array('message' => __('Failed to delete pet or pet not found.', 'wp-user-pets')));
        }
    }
    
    /**
     * AJAX: Get user pets
     */
    public function ajax_get_pets() {
        check_ajax_referer('wp_user_pets_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('You must be logged in.', 'wp-user-pets')));
        }
        
        $user_id = get_current_user_id();
        $pets = WP_User_Pets_Database::get_user_pets($user_id);
        
        wp_send_json_success(array('pets' => $pets));
    }
    
    /**
     * Shortcode: Display user's pets
     */
    public function user_pets_shortcode($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to view your pets.', 'wp-user-pets') . '</p>';
        }
        
        $atts = shortcode_atts(array(
            'show_form' => 'yes'
        ), $atts);
        
        $user_id = get_current_user_id();
        $pets = WP_User_Pets_Database::get_user_pets($user_id);
        
        ob_start();
        ?>
        <div class="wp-user-pets-container">
            <?php if ($atts['show_form'] === 'yes') : ?>
                <?php echo $this->render_pet_form(); ?>
            <?php endif; ?>
            
            <div class="wp-user-pets-list">
                <h3><?php _e('My Pets', 'wp-user-pets'); ?></h3>
                <?php if (empty($pets)) : ?>
                    <p><?php _e('You haven\'t added any pets yet.', 'wp-user-pets'); ?></p>
                <?php else : ?>
                    <div class="pets-grid">
                        <?php foreach ($pets as $pet) : ?>
                            <div class="pet-card" data-pet-id="<?php echo esc_attr($pet->id); ?>">
                                <?php if (!empty($pet->pet_image_url)) : ?>
                                    <img src="<?php echo esc_url($pet->pet_image_url); ?>" alt="<?php echo esc_attr($pet->pet_name); ?>" class="pet-image">
                                <?php endif; ?>
                                <div class="pet-info">
                                    <h4><?php echo esc_html($pet->pet_name); ?></h4>
                                    <p><strong><?php _e('Type:', 'wp-user-pets'); ?></strong> <?php echo esc_html($pet->pet_type); ?></p>
                                    <?php if (!empty($pet->pet_breed)) : ?>
                                        <p><strong><?php _e('Breed:', 'wp-user-pets'); ?></strong> <?php echo esc_html($pet->pet_breed); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($pet->pet_age)) : ?>
                                        <p><strong><?php _e('Age:', 'wp-user-pets'); ?></strong> <?php echo esc_html($pet->pet_age); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($pet->pet_description)) : ?>
                                        <p><?php echo esc_html($pet->pet_description); ?></p>
                                    <?php endif; ?>
                                    <div class="pet-actions">
                                        <button class="button edit-pet" data-pet-id="<?php echo esc_attr($pet->id); ?>"><?php _e('Edit', 'wp-user-pets'); ?></button>
                                        <button class="button delete-pet" data-pet-id="<?php echo esc_attr($pet->id); ?>"><?php _e('Delete', 'wp-user-pets'); ?></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Shortcode: Display pet form only
     */
    public function user_pets_form_shortcode($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to add pets.', 'wp-user-pets') . '</p>';
        }
        
        return $this->render_pet_form();
    }
    
    /**
     * Render pet form
     */
    private function render_pet_form() {
        ob_start();
        ?>
        <div class="wp-user-pets-form">
            <h3><?php _e('Add New Pet', 'wp-user-pets'); ?></h3>
            <form id="add-pet-form">
                <div class="form-group">
                    <label for="pet_name"><?php _e('Pet Name *', 'wp-user-pets'); ?></label>
                    <input type="text" id="pet_name" name="pet_name" required>
                </div>
                
                <div class="form-group">
                    <label for="pet_type"><?php _e('Pet Type *', 'wp-user-pets'); ?></label>
                    <select id="pet_type" name="pet_type" required>
                        <option value=""><?php _e('Select Type', 'wp-user-pets'); ?></option>
                        <option value="Dog"><?php _e('Dog', 'wp-user-pets'); ?></option>
                        <option value="Cat"><?php _e('Cat', 'wp-user-pets'); ?></option>
                        <option value="Bird"><?php _e('Bird', 'wp-user-pets'); ?></option>
                        <option value="Fish"><?php _e('Fish', 'wp-user-pets'); ?></option>
                        <option value="Rabbit"><?php _e('Rabbit', 'wp-user-pets'); ?></option>
                        <option value="Hamster"><?php _e('Hamster', 'wp-user-pets'); ?></option>
                        <option value="Other"><?php _e('Other', 'wp-user-pets'); ?></option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="pet_breed"><?php _e('Breed', 'wp-user-pets'); ?></label>
                    <input type="text" id="pet_breed" name="pet_breed">
                </div>
                
                <div class="form-group">
                    <label for="pet_age"><?php _e('Age', 'wp-user-pets'); ?></label>
                    <input type="text" id="pet_age" name="pet_age" placeholder="e.g., 2 years">
                </div>
                
                <div class="form-group">
                    <label for="pet_description"><?php _e('Description', 'wp-user-pets'); ?></label>
                    <textarea id="pet_description" name="pet_description" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="pet_image_url"><?php _e('Pet Photo', 'wp-user-pets'); ?></label>
                    <input type="hidden" id="pet_image_url" name="pet_image_url">
                    <button type="button" class="button upload-pet-image"><?php _e('Upload Photo', 'wp-user-pets'); ?></button>
                    <div class="pet-image-preview"></div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="button button-primary"><?php _e('Add Pet', 'wp-user-pets'); ?></button>
                </div>
                
                <div class="form-message"></div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
}
