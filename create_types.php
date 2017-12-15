<?php

/*
 * Create the custom post type (workout) and taxonomy (AO)
 */
add_action('init', 'create_workout_type', 0);
add_action('init', 'create_ao_tax', 0);

//Comments are disabled by default... turn them on
add_filter('wp_insert_post_data', 'default_comments_on');

//Hide the comments box from the edit screen
add_action('admin_menu', 'remove_workout_metaboxes');


//Allow authors to edit each other's posts. Not great for security, but necessary for now to enable
//setting QIC for unscheduled workouts

add_action('admin_init', 'add_theme_caps');

function create_workout_type()
{
    register_post_type('workout', array(
        'labels' => array(
            'name' => 'Workouts',
            'singular_name' => 'Workout',
            'add_new_item' => 'Add New Workout',
        ),
        'rewrite' => array(
            'slug' => 'workouts',
            'with_front' => FALSE,
        ),
        'description' => 'F3 workouts and BackBlasts',
        'menu_icon' => 'dashicons-flag',
        'hierarchical' => false,
        'show_in_rest' => true,
        'capability_type' => 'post',
        'public' => true,
        'has_archive' => true,
        'menu_position' => 2,
        'publicly_queryable' => true,
        'supports' => array('title', 'editor', 'comments')
    ));

    flush_rewrite_rules();
}


/*
 * Create the AO taxonomy to apply to workouts
 */

function create_ao_tax()
{
    $args = array(
        'labels' => array(
            'name' => 'AOs',
            'singular_name' => 'AO',
            'menu_name' => 'AOs',
            'add_new_item' => 'Add AO'
        ),
        'description' => __('Area of Operation', 'f3-ao'),
        'hierarchical' => false,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'meta_box_cb' => false,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'show_tagcloud' => false,
        'show_in_quick_edit' => false,
        'show_admin_column' => false,
    );

    register_taxonomy('ao', array('workout'), $args);
}

/*
 * Enable comments on new workouts by default
 */

function default_comments_on($data)
{
    if ($data['post_type'] == 'workout') {
        $data['comment_status'] = 1;
    }

    return $data;
}

function remove_workout_metaboxes()
{
    remove_meta_box('commentsdiv', 'workout', 'normal');
    remove_meta_box('commentstatusdiv', 'workout', 'normal');
}

function add_theme_caps()
{
    $role = get_role('author');
    $role->add_cap('edit_others_posts');
}

