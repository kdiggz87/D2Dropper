<?php
/**
 * Pet Selector class - Manages Pet of the Day selection
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WP_POD_Selector {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Transient key for pet of the day
     */
    const TRANSIENT_KEY = 'wp_pet_of_the_day';
    
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
        // Schedule daily cron job
        add_action('wp', array($this, 'schedule_daily_selection'));
        add_action('wp_pod_daily_selection', array($this, 'select_new_pet'));
    }
    
    /**
     * Schedule daily pet selection
     */
    public function schedule_daily_selection() {
        if (!wp_next_scheduled('wp_pod_daily_selection')) {
            wp_schedule_event(strtotime('tomorrow midnight'), 'daily', 'wp_pod_daily_selection');
        }
    }
    
    /**
     * Select a new pet of the day
     */
    public function select_new_pet() {
        // Delete the old transient
        delete_transient(self::TRANSIENT_KEY);
        
        // Get a random pet
        $pet = WP_User_Pets_Database::get_random_pet();
        
        if ($pet) {
            // Store in transient for 24 hours
            set_transient(self::TRANSIENT_KEY, $pet, DAY_IN_SECONDS);
            
            // Log the selection
            $this->log_pet_selection($pet->id);
        }
        
        return $pet;
    }
    
    /**
     * Get pet of the day
     */
    public static function get_pet_of_the_day() {
        $pet = get_transient(self::TRANSIENT_KEY);
        
        if (false === $pet) {
            // No pet selected yet, select one now
            $instance = self::get_instance();
            $pet = $instance->select_new_pet();
        }
        
        return $pet;
    }
    
    /**
     * Log pet selection (optional tracking)
     */
    private function log_pet_selection($pet_id) {
        $history = get_option('wp_pod_history', array());
        
        // Keep only last 30 days of history
        if (count($history) >= 30) {
            array_shift($history);
        }
        
        $history[] = array(
            'pet_id' => $pet_id,
            'date' => current_time('mysql')
        );
        
        update_option('wp_pod_history', $history);
    }
    
    /**
     * Get selection history
     */
    public static function get_history($limit = 30) {
        $history = get_option('wp_pod_history', array());
        
        if ($limit > 0) {
            $history = array_slice($history, -$limit);
        }
        
        return array_reverse($history);
    }
    
    /**
     * Force refresh pet of the day
     */
    public static function force_refresh() {
        $instance = self::get_instance();
        return $instance->select_new_pet();
    }
}
