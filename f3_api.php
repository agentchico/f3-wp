<?php

add_action('rest_api_init', function () {
    register_rest_route('f3/v1', '/workouts', array(
        'methods' => 'GET',
        'callback' => 'get_workouts',
    ));
    register_rest_route('f3/v1', '/workouts', array(
        'methods' => 'POST',
        'callback' => 'create_workout',
    ));
});

function create_workout(WP_REST_Request $request)
{
    $data = $request->get_body_params();

    if (!isset($data['ao']) || !isset($data['date'])) {
        return new WP_Error('invalid_post', 'Invalid data', array('status' => 400));
    }

    $aoObj = get_term_by('slug', $data['ao'], 'ao');

    $newPost = wp_insert_post(array(
        'post_title' => $aoObj->name . ': ' . $data['date'],
        'post_type' => 'workout',
        'meta_input' => array('date' => $data['date'])
    ));

    wp_set_object_terms($newPost, $aoObj->term_id, 'ao');

    return array('id' => $newPost);
}

function get_workouts(WP_REST_Request $request)
{
    $parameters = $request->get_query_params();

    $meta_query = array('relation' => 'AND');

    if (isset($parameters['qic'])) {
        $meta_query[] = array('key' => 'qic', 'value' => $parameters['qic']);
    }

    if (isset($parameters['start']) && isset($parameters['end'])) {
        $meta_query[] = array(
            'key' => 'date',
            'value' => array($parameters['start'], $parameters['end']),
            'type' => 'date',
            'compare' => 'BETWEEN'
        );
    }

    $args = array(
        'post_type' => 'workout',
        'post_status' => 'any',
        'meta_query' => $meta_query
    );

    $query = new WP_Query($args);
    $results = array();

    $userIds = array();

    foreach ($query->posts as $workout) {
        $userIds = array_merge($userIds, get_post_meta($workout->ID, 'pax'));
        $userIds[] = get_post_meta($workout->ID, 'qic', true);
    }

    $userMap = array();

    foreach (get_users(array('include' => $userIds)) as $user) {
        $userMap[$user->ID] = array(
            'id' => $user->ID,
            'f3' => $user->user_login,
            'name' => $user->display_name,
            'avatar' => get_avatar_url($user->ID)
        );
    }


    foreach ($query->posts as $workout) {
        setup_postdata($workout);
        $result = array(
            'id' => $workout->ID,
            'status' => get_post_status($workout->ID),
            'title' => get_the_title($workout->ID),
            'link' => get_the_permalink($workout->ID),
            'date' => get_post_meta($workout->ID, 'date', true),
            'excerpt' => get_the_excerpt()
        );

        $ao = wp_get_post_terms($workout->ID, 'ao');

        if (isset($ao[0])) {
            $aoObj = get_term_by('slug', $ao[0]->slug, 'ao');

            $result['ao'] = array(
                'title' => $aoObj->name,
                'color' => get_term_meta($aoObj->term_id, 'color', true),
                'slug' => $aoObj->slug
            );
        }

        $pax = get_post_meta($workout->ID, 'pax');
        if (isset($pax)) {
            $paxObjs = array();
            foreach ($pax as $paxId) {
                $paxObjs[] = $userMap[$paxId];
            }

            $result['pax'] = $paxObjs;
        }

        $qic = get_post_meta($workout->ID, 'qic', true);
        if (isset($qic)) {
            $result['qic'] = $userMap[$qic];
        }

        $results[] = $result;
    }


    return array('results' => $results, 'nonce' => wp_create_nonce('wp_rest'));
//    if ( $query->have_posts() ) {
//        echo '<h2>Films By Star Wards Directors</h2>';
//        echo '<ul>';
//
//        echo '</ul>';
//    }
}