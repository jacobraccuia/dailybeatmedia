<?php
defined('ABSPATH') OR exit;
// Class example (inside ex. filename.php):

add_action('db_global', array('DB_Global', 'init'));
if(!class_exists('DB_Global')):
/**
 * This class triggers functions that run during activation/deactivation & uninstallation
 * NOTE: All comments are just my *suggestions*.
 */
class DB_Global {
	
    protected static $instance;

    public static function init()
    {
        is_null( self::$instance ) AND self::$instance = new self;
        return self::$instance;
    }

	
    function __construct($case = false) {
        if (!$case)
            wp_die('Busted! You should not call this class directly', 'Doing it wrong!');

        switch($case)
        {
            case 'activate':
                // add_action calls and else
                # @example:
                add_action('init', array(&$this, 'activate_cb'));
                break;

            case 'deactivate': 
                // reset the options
                # @example:
                add_action('init', array(&$this, 'deactivate_cb'));
                break;

        }
    }

    /**
     * Set up tables, add options, etc. - All preparation that only needs to be done once
     **/
    function on_activation() {
	
		global $wpdb;
        global $db_db_version;
        $db_db_version = ".01"; // lol daily beat data base value
        
        $table = $wpdb->base_prefix . "db_stats";
        
            $sql_collection = "CREATE TABLE IF NOT EXISTS $table (
                ID bigint(9) NOT NULL AUTO_INCREMENT,
                blog_id mediumint(9) NOT NULL,
                post_id mediumint(9) NOT NULL,
                views mediumint(9) NOT NULL,
                date_added datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                UNIQUE KEY ID (ID)
            );";
        
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');    
            dbDelta($sql);

            add_option("db_db_version", $db_db_version);
    
    }

    /**
     * Do nothing like removing settings, etc. 
     * The user could reactivate the plugin and wants everything in the state before activation.
     * Take a constant to remove everything, so you can develop & test easier.
     */
    function on_deactivation() {
		
		if (!current_user_can('activate_plugins'))
            return;
        $plugin = isset($_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer("deactivate-plugin_{$plugin}");

        # Uncomment the following line to see the function in action
        # exit( var_dump( $_GET ) );
	//	new ABC_Gallery('deactivate');
	
	}

    /**
     * Remove/Delete everything - If the user wants to uninstall, then he wants the state of origin.
     * 
     * Will be called when the user clicks on the uninstall link that calls for the plugin to uninstall itself
     */
    function on_uninstall() {
        
		if (!current_user_can('activate_plugins'))
        	wp_die('<h1>I mean it.  Stop.  You don\'t have permissions.</h1>');
        check_admin_referer('bulk-plugins');
	
		// important: check if the file is the one that was registered with the uninstall hook (function)
        if (__FILE__ != WP_UNINSTALL_PLUGIN)
        	wp_die(WP_UNINSTALL_PLUGIN . '<h1>Deal with it.  You don\'t have permissions.</h1>');
		
    }

    function activate_cb() {
	}

    function deactivate_cb() {
	
	}
    /**
     * trigger_error()
     * 
     * @param (string) $error_msg
     * @param (boolean) $fatal_error | catched a fatal error - when we exit, then we can't go further than this point
     * @param unknown_type $error_type
     * @return void
     */
    function error($error_msg, $fatal_error = false, $error_type = E_USER_ERROR)
    {
        if(isset($_GET['action']) && 'error_scrape' == $_GET['action']) 
        {
            echo "{$error_msg}\n";
            if ($fatal_error)
                exit;
        }
        else 
        {
            trigger_error($error_msg, $error_type);
        }
    }
}
endif;
?>