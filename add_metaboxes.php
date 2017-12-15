<?php

/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourplugin_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/CMB2/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if (file_exists(dirname(__FILE__) . '/cmb2/init.php')) {
    require_once dirname(__FILE__) . '/cmb2/init.php';
} elseif (file_exists(dirname(__FILE__) . '/CMB2/init.php')) {
    require_once dirname(__FILE__) . '/CMB2/init.php';
}

/*
 * Import the Custom Meta Box extensions for the Google map and the ajax search (for PAX)
 */
require_once(dirname(__FILE__) . '/cmb2_field_ajax_search/cmb2-field-ajax-search.php');
require_once(dirname(__FILE__) . '/cmb2_field_map/cmb-field-map.php');

/*
 * Hooks to add the metaboxes to the Workout (PAX, QIC, etc) and the AO taxonomy (location, days, time)
 */

add_action('cmb2_init', 'f3_register_taxonomy_metabox');
add_action('cmb2_init', 'f3_register_workout_metabox');


/*
 * These functions change the AJAX search metabox display string to use F3 name (username) rather than just display name
 */
add_filter('cmb_pax_ajax_search_result_text', 'cmb_f3_workout_users_result_text', 10, 3);
add_filter('cmb_qic_ajax_search_result_text', 'cmb_f3_workout_users_result_text', 10, 3);
function cmb_f3_workout_users_result_text($text, $object_id, $object_type)
{
    $f3name = get_the_author_meta('user_nicename', $object_id);
    $name = get_the_author_meta('display_name', $object_id);

    $text = sprintf('%s (%s)', $f3name, $name);

    return $text;
}

/**
 * Hook in and add the workout metabox.
 */
function f3_register_workout_metabox()
{
    $prefix = '';

    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb_workout = new_cmb2_box(array(
        'id' => $prefix . 'meta',
//        'title' => esc_html__('Workout Meta', 'cmb2'),
        'remove_box_wrap' => true,
        'object_types' => array('workout'), // Post type
        'show_in_rest' => WP_REST_Server::ALLMETHODS,
        'context' => 'after_title',
        'priority' => 'high',
    ));

    $cmb_workout->add_field(array(
        'name' => 'Date',
        'id' => $prefix . 'date',
        'type' => 'text_date',
        'default' => date('Y-m-d'),
        'date_format' => 'Y-m-d',
        'attributes' => array(
            'data-datepicker' => json_encode(array(
                'firstDay' => 0
            )),
        )
    ));

    $cmb_workout->add_field(array(
        'name' => esc_html__('AO', 'cmb2'),
        'id' => $prefix . 'wo_ao',
        'type' => 'taxonomy_radio',
        'taxonomy' => 'ao',
        'show_option_none' => false
    ));

    $cmb_workout->add_field(array(
        'name' => esc_html__('QIC', 'cmb2'),
        'desc' => esc_html__('Q In Charge', 'cmb2'),
        'id' => $prefix . 'qic',
        'type' => 'user_ajax_search'
    ));

    $cmb_workout->add_field(array(
        'name' => esc_html__('PAX', 'cmb2'),
        'desc' => esc_html__('List all PAX who posted this workout', 'cmb2'),
        'id' => $prefix . 'pax',
        'type' => 'user_ajax_search',
        'multiple' => true
    ));

    $cmb_workout->add_field(array(
        'name' => 'BackBlast',
        'desc' => 'QIC: Write your BackBlast in the editor below and publish.',
        'type' => 'title',
        'tag' => 'h1',
        'id' => $prefix . 'backblast_title',
    ));
}

/**
 * Hook in and add a metabox to add fields to the AO taxonomy
 */
function f3_register_taxonomy_metabox()
{
    $prefix = '';

    /**
     * Metabox to add fields to categories and tags
     */
    $cmb_ao_tax = new_cmb2_box(array(
        'id' => $prefix . 'edit',
        'classes' => 'cmb2-tax-edit',
        'object_types' => array('term'), // Tells CMB2 to use term_meta vs post_meta
        'context' => 'side',
        'show_in_rest' => WP_REST_Server::ALLMETHODS,
        'taxonomies' => array('ao'), // Tells CMB2 which taxonomies should have these fields
    ));

    $cmb_ao_tax->add_field(array(
        'name' => esc_html__('Days of Week', 'cmb2'),
        'id' => $prefix . 'days',
        'options' => array(
            'sun' => 'Sun',
            'mon' => 'Mon',
            'tue' => 'Tue',
            'wed' => 'Wed',
            'thur' => 'Thur',
            'fri' => 'Fri',
            'sat' => 'Sat',
        ),
        'select_all_button' => false,
        'type' => 'multicheck_inline',
    ));

    $cmb_ao_tax->add_field(array(
        'name' => esc_html__('Time', 'cmb2'),
        'id' => $prefix . 'time',
        'type' => 'text',
    ));


    $cmb_ao_tax->add_field(array(
        'name' => esc_html__('Color', 'cmb2'),
        'id' => $prefix . 'color',
        'type' => 'colorpicker',
    ));

    $cmb_ao_tax->add_field(array(
        'name' => esc_html__('Location', 'cmb2'),
        'id' => $prefix . 'location',
        'type' => 'pw_map',
    ));

}
