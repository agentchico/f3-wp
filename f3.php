<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dev.am
 * @since             1.0.0
 * @package           F3
 *
 * @wordpress-plugin
 * Plugin Name:       F3
 * Plugin URI:        https://f3nation.com
 * Description:       Plugin for Wordpress for F3 BackBlasts and AOs
 * Version:           1.0.0
 * Author:            Andrew Miller
 * Author URI:        https://dev.am
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       f3
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('PLUGIN_NAME_VERSION', '1.0.0');

/*
 * Load all required files
 */

//Google Maps key - replace with your own,
//or email andrew@dev.am to add your domain to the trusted domains list

$MAPS_KEY = 'AIzaSyAeWkoyGJqPGS5J5V6MBczlLQYOe8mkTaQ';

//Google Maps is used in several places - register it once so we can use it elsewhere
wp_register_script('f3-google-maps-api', '//maps.googleapis.com/maps/api/js?libraries=places&key=' . $MAPS_KEY, null, null);

//Create the custom post type and taxonomy
require_once(dirname(__FILE__) . '/create_types.php');

//Update the register screen to ask for F3 Username
require_once(dirname(__FILE__) . '/register.php');

//Remove crap from the admin panel for authors
require_once(dirname(__FILE__) . '/disable_admin.php');

//Register shortcodes `f3_calendar` and `f3_ao_list`
require_once(dirname(__FILE__) . '/shortcodes.php');

//Add F3 fields to the workout post type and the ao taxonomy
require_once(dirname(__FILE__) . '/add_metaboxes.php');

//Enable the custom API so anonymous users can retrieve workout drafts
require_once(dirname(__FILE__) . '/f3_api.php');

