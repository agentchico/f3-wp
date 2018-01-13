<?php

$plugin_path = plugin_dir_path(__FILE__);

//Create the custom post type and taxonomy
require_once($plugin_path . 'create_types.php');

//Remove crap from the admin panel for authors
require_once($plugin_path . 'disable_admin.php');

//Add F3 fields to the workout post type and the ao taxonomy
require_once($plugin_path . 'add_metaboxes.php');

//Custom plugin settings page
require_once($plugin_path . 'settings.php');

//Update the register screen to ask for F3 Username
require_once($plugin_path . 'register.php');


//Rewrite 'author' to 'pax'
add_action('init', 'author_to_pax');
function author_to_pax()
{
    global $wp_rewrite;
    $wp_rewrite->author_base = 'pax';
    $wp_rewrite->author_structure = '/' . $wp_rewrite->author_base . '/%author%';
}
