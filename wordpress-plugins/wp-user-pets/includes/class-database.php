<?php
/**
 * Database management class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WP_User_Pets_Database {
    
    /**
     * Create database tables
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'user_pets';
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned NOT NULL,
            pet_name varchar(255) NOT NULL,
            pet_type varchar(100) NOT NULL,
            pet_breed varchar(255) DEFAULT '',
            pet_age varchar(50) DEFAULT '',
            pet_description text DEFAULT '',
            pet_image_url varchar(500) DEFAULT '',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Store the database version
        add_option('wp_user_pets_db_version', WP_USER_PETS_VERSION);
    }
    
    /**
     * Get user pets
     */
    public static function get_user_pets($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_pets';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE user_id = %d ORDER BY created_at DESC",
            $user_id
        ));
    }
    
    /**
     * Get single pet
     */
    public static function get_pet($pet_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_pets';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $pet_id
        ));
    }
    
    /**
     * Add pet
     */
    public static function add_pet($data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_pets';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'user_id' => $data['user_id'],
                'pet_name' => sanitize_text_field($data['pet_name']),
                'pet_type' => sanitize_text_field($data['pet_type']),
                'pet_breed' => sanitize_text_field($data['pet_breed']),
                'pet_age' => sanitize_text_field($data['pet_age']),
                'pet_description' => sanitize_textarea_field($data['pet_description']),
                'pet_image_url' => esc_url_raw($data['pet_image_url'])
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        
        return $result ? $wpdb->insert_id : false;
    }
    
    /**
     * Update pet
     */
    public static function update_pet($pet_id, $data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_pets';
        
        $update_data = array();
        $format = array();
        
        if (isset($data['pet_name'])) {
            $update_data['pet_name'] = sanitize_text_field($data['pet_name']);
            $format[] = '%s';
        }
        if (isset($data['pet_type'])) {
            $update_data['pet_type'] = sanitize_text_field($data['pet_type']);
            $format[] = '%s';
        }
        if (isset($data['pet_breed'])) {
            $update_data['pet_breed'] = sanitize_text_field($data['pet_breed']);
            $format[] = '%s';
        }
        if (isset($data['pet_age'])) {
            $update_data['pet_age'] = sanitize_text_field($data['pet_age']);
            $format[] = '%s';
        }
        if (isset($data['pet_description'])) {
            $update_data['pet_description'] = sanitize_textarea_field($data['pet_description']);
            $format[] = '%s';
        }
        if (isset($data['pet_image_url'])) {
            $update_data['pet_image_url'] = esc_url_raw($data['pet_image_url']);
            $format[] = '%s';
        }
        
        return $wpdb->update(
            $table_name,
            $update_data,
            array('id' => $pet_id),
            $format,
            array('%d')
        );
    }
    
    /**
     * Delete pet
     */
    public static function delete_pet($pet_id, $user_id = null) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_pets';
        
        $where = array('id' => $pet_id);
        $where_format = array('%d');
        
        if ($user_id !== null) {
            $where['user_id'] = $user_id;
            $where_format[] = '%d';
        }
        
        return $wpdb->delete($table_name, $where, $where_format);
    }
    
    /**
     * Get all pets (for Pet of the Day plugin)
     */
    public static function get_all_pets($limit = null) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_pets';
        
        $sql = "SELECT * FROM $table_name ORDER BY created_at DESC";
        
        if ($limit !== null) {
            $sql .= $wpdb->prepare(" LIMIT %d", $limit);
        }
        
        return $wpdb->get_results($sql);
    }
    
    /**
     * Get random pet
     */
    public static function get_random_pet() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_pets';
        
        return $wpdb->get_row("SELECT * FROM $table_name ORDER BY RAND() LIMIT 1");
    }
    
    /**
     * Get total pets count
     */
    public static function get_total_pets_count() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_pets';
        
        return $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    }
}
