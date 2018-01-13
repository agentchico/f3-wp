<?php



//add_action('single_template', 'f3_single_template');
add_action('archive_template', 'f3_archive_template');
add_action('author_template', 'f3_author_template');


function f3_archive_template($archive_template)
{
    if (is_post_type_archive('workout')) {
        wp_enqueue_script('f3_workouts_js', plugin_dir_url(__FILE__) . 'workouts/workout-list.js', array('f3-google-maps-api', 'jquery'));
        return plugin_dir_path(__FILE__) . 'workouts/list.php';
    }
    return $archive_template;
}

function f3_author_template($archive_template)
{
    return dirname(__FILE__) . '/pax/single.php';
}

function f3_single_template($single_template)
{
    global $post;

    if ($post->post_type == 'workout') {
        $single_template = dirname(__FILE__) . '/workouts/single.php';
    }

    return $single_template;
}
