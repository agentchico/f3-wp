<?php

function f3_calendar_shortcode($atts = [], $content = null)
{
    //Enqueue the required scripts
    wp_enqueue_script('f3_calendar_vendor_js', plugin_dir_url(__FILE__) . 'calendar/vendor.js', array('jquery'));
    wp_enqueue_script('f3_calendar_js', plugin_dir_url(__FILE__) . 'calendar/calendar.js', array('f3_calendar_vendor_js'));
    wp_enqueue_style('f3_calendar_vendor_css', plugin_dir_url(__FILE__) . 'calendar/vendor.css');
    wp_enqueue_style('f3_calendar_css', plugin_dir_url(__FILE__) . 'calendar/calendar.css');

    //Return the div that will be manipulated by the calendar.js file
    return
        "
<div data-remodal-id=\"calendar-modal\"></div>
<select class='select-ao'><option value=''>All AOs</option></select>
<div class='f3-calendar-holder'>
<div class='f3-calendar-sc fc fc-unthemed fc-ltr'></div>
</div>
";
}

function f3_workout_calendar_shortcode($atts = [], $content = null)
{
    //Enqueue the required scripts
    wp_enqueue_script('f3_cal_vendor_js', plugin_dir_url(__FILE__) . 'workout_calendar/cal.js', array('jquery'));
    wp_enqueue_script('f3_cal_js', plugin_dir_url(__FILE__) . 'workout_calendar/script.js', array('f3_cal_vendor_js'));
    wp_enqueue_style('f3_cal_vendor_css', plugin_dir_url(__FILE__) . 'workout_calendar/cal.css');

    //Return the div that will be manipulated by the calendar.js file
    return "<div class='workout-calendar'></div>";
}

function f3_ao_map_shortcode($atts = [], $content = null)
{
    wp_enqueue_script('f3-ao-map-script', plugin_dir_url(__FILE__) . 'ao_map/ao_map.js', array('f3', 'f3-google-maps-api'));
    wp_enqueue_style('f3-ao-map-style', plugin_dir_url(__FILE__) . 'ao_map/ao_map.css');

    return "
     <div id='workout-map-holder'>
         <div id='workout-map'></div>
         <div id='ao-list'></div>
     </div> 
    ";
}

function f3_pax_list_shortcode($atts = [], $content = null)
{
    wp_enqueue_script('f3_pax_list_js', plugin_dir_url(__FILE__) . 'pax_list/pax_list.js', array('f3'));
    wp_enqueue_style('f3_pax_list_css', plugin_dir_url(__FILE__) . 'pax_list/pax_list.css');

    ob_start();
    include("pax_list/pax_list.php");
    return ob_get_clean();
}

function f3_ao_list_shortcode($atts = [], $content = null)
{
    wp_enqueue_script('f3_ao_map_js', plugin_dir_url(__FILE__) . 'ao_list/ao_list.js', array('f3-google-maps-api', 'f3'));
    wp_enqueue_style('f3_ao_list_css', plugin_dir_url(__FILE__) . 'ao_list/ao_list.css');

    ob_start();
    include("ao_list/ao_list.php");
    return ob_get_clean();
}

add_shortcode('f3_ao_list', 'f3_ao_list_shortcode');
add_shortcode('f3_pax_list', 'f3_pax_list_shortcode');
add_shortcode('f3_ao_map', 'f3_ao_map_shortcode');
add_shortcode('f3_calendar', 'f3_calendar_shortcode');
add_shortcode('workout_calendar', 'f3_workout_calendar_shortcode');
