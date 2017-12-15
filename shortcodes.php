<?php

/*
 * Register our shortcodes, for the calendar and AO map
 */

add_shortcode('f3_calendar', 'f3_calendar_shortcode');
add_shortcode('f3_ao_list', 'f3_ao_list_shortcode');

/*
 * Calendar scripts
 * Most of the lifting/querying is done from calendar.js using the Wordpress API
 */
wp_register_script('moment', plugin_dir_url(__FILE__) . 'js/moment.min.js');
wp_register_script('f3_fullcalendar_io_js', plugin_dir_url(__FILE__) . 'js/fullcalendar.min.js', array('jquery', 'moment'));
wp_register_script('f3_remodal_js', plugin_dir_url(__FILE__) . 'js/remodal.js', array('jquery'));
wp_register_script('f3_calendar_js', plugin_dir_url(__FILE__) . 'js/calendar.js', array('f3_fullcalendar_io_js', 'f3_remodal_js'));

wp_register_style('f3_fullcalendar_io_css', plugin_dir_url(__FILE__) . 'css/fullcalendar.css');
wp_register_style('f3_remodal_css', plugin_dir_url(__FILE__) . 'css/remodal.css');
wp_register_style('f3_ao_list_css', plugin_dir_url(__FILE__) . 'css/ao_list.css');

/*
 * AO Map scripts
 * Most of the lifting/querying is done from ao_map.js using the Wordpress API
 */
wp_register_script('f3_ao_map_js', plugin_dir_url(__FILE__) . 'js/ao_map.js', array('f3-google-maps-api'));

function f3_calendar_shortcode($atts = [], $content = null)
{
    //Enqueue the required scripts
    wp_enqueue_script('f3_calendar_js');
    wp_enqueue_style('f3_fullcalendar_io_css');
    wp_enqueue_style('f3_remodal_css');

    //Return the div that will be manipulated by the calendar.js file
    return
        "
<div data-remodal-id=\"calendar-modal\"></div>
<select class='select-ao'><option value=''>All AOs</option></select>
<div class='f3-calendar-sc fc fc-unthemed fc-ltr'></div>
";
}

function f3_ao_list_shortcode($atts = [], $content = null)
{
    wp_enqueue_script('f3_ao_map_js');
    wp_enqueue_style('f3_ao_list_css');

//    return "<div>HELLO WORLD</div>";

    ob_start();
    include('shortcodes/ao_list.php');
    return ob_get_clean();
}


