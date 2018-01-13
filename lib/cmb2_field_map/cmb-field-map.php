<?php
/*
Plugin Name: CMB2 Field Type: Google Maps
Plugin URI: https://github.com/mustardBees/cmb_field_map
GitHub Plugin URI: https://github.com/mustardBees/cmb_field_map
Description: Google Maps field type for CMB2.
Version: 2.1.2
Author: Phil Wylie
Author URI: http://www.philwylie.co.uk/
License: GPLv2+
*/

/**
 * Class PW_CMB2_Field_Google_Maps
 */
class PW_CMB2_Field_Google_Maps
{

    /**
     * Current version number
     */
    const VERSION = '2.1.1';

    /**
     * Initialize the plugin by hooking into CMB2
     */
    public function __construct()
    {
        add_filter('cmb2_render_pw_map', array($this, 'render_pw_map'), 10, 5);
        add_filter('cmb2_sanitize_pw_map', array($this, 'sanitize_pw_map'), 10, 4);
    }

    /**
     * Render field
     */
    public function render_pw_map($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object)
    {
        $this->setup_admin_scripts();

        echo '<input type="text" class="large-text pw-map-search" id="' . $field->args('id') . '" />';

        echo '<div class="pw-map"></div>';

        $field_type_object->_desc(true, true);

        echo $field_type_object->input(array(
            'type' => 'hidden',
            'name' => $field->args('_name') . '[lat]',
            'value' => isset($field_escaped_value['lat']) ? $field_escaped_value['lat'] : '',
            'class' => 'pw-map-latitude',
            'desc' => '',
        ));
        echo $field_type_object->input(array(
            'type' => 'hidden',
            'name' => $field->args('_name') . '[lng]',
            'value' => isset($field_escaped_value['lng']) ? $field_escaped_value['lng'] : '',
            'class' => 'pw-map-longitude',
            'desc' => '',
        ));
    }

    /**
     * Optionally save the latitude/longitude values into two custom fields
     */
    public function sanitize_pw_map($override_value, $value, $object_id, $field_args)
    {
        if (isset($field_args['split_values']) && $field_args['split_values']) {
            if (!empty($value['lat'])) {
                update_post_meta($object_id, $field_args['id'] . '_lat', $value['lat']);
            }

            if (!empty($value['lng'])) {
                update_post_meta($object_id, $field_args['id'] . '_lng', $value['lng']);
            }
        }

        return $value;
    }

    /**
     * Enqueue scripts and styles
     */
    public function setup_admin_scripts()
    {
        wp_enqueue_script('pw-google-maps', plugins_url('js/script.js', __FILE__), array('f3-google-maps-api'), self::VERSION);
        wp_enqueue_style('pw-google-maps', plugins_url('css/style.css', __FILE__), array(), self::VERSION);
    }
}

$pw_cmb2_field_google_maps = new PW_CMB2_Field_Google_Maps();
