<?php

$libpath = plugin_dir_path(__FILE__) . '../lib';

require_once $libpath . '/titan-framework/titan-framework-embedder.php';

add_action('tf_create_options', 'f3_create_admin_page');

function f3_create_admin_page()
{
    $titan = TitanFramework::getInstance('F3');
    $panel = $titan->createContainer(array(
        "name" => "F3 Options",
        "position" => 5,
        "type" => "admin-page"
    ));

    $panel->createOption(array(
        'name' => 'Google Maps API key',
        'id' => 'google_maps',
        'type' => 'text',
        'desc' => 'Register for a Google Maps API key here: https://developers.google.com/maps/documentation/javascript/get-api-key'
    ));

    $panel->createOption(array(
        'name' => 'Twitter Feed',
        'id' => 'twitter',
        'type' => 'text',
        'desc' => 'Enter your twitter account to show tweets on the Comms page'
    ));

    $panel->createOption(array(
        'name' => 'Custom Pages',
        'type' => 'heading',
        'desc' => 'Uncheck boxes below if you create a custom page to use instead of the plugin defaults.'
    ));

    $panel->createOption(array(
        'name' => 'Workouts page',
        'id' => 'intercept_workouts',
        'type' => 'checkbox',
        'default' => true,
        'desc' => 'Let the F3 Plugin handle the /workouts/ route'
    ));

    $panel->createOption(array(
        'name' => 'PAX list page',
        'id' => 'intercept_pax_list',
        'type' => 'checkbox',
        'default' => true,
        'desc' => 'Let the F3 Plugin handle the /pax/ route'
    ));

    $panel->createOption(array(
        'name' => 'PAX profile page',
        'id' => 'intercept_pax',
        'type' => 'checkbox',
        'default' => true,
        'desc' => 'Let the F3 Plugin handle the /pax/:username route (authors.php page)'
    ));

    $panel->createOption(array(
        'type' => 'save'
    ));

// Create an admin tab inside the admin page above
    $tab = $panel->createTab(array(
        "name" => "General Options",
    ));
}


