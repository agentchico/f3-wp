<?php

add_action('rest_api_init', function () {
    register_rest_route('f3/v1', '/aos', array(
        'methods' => 'GET',
        'callback' => 'get_aos',
    ));
    register_rest_route('f3/v1', '/workouts', array(
        'methods' => 'GET',
        'callback' => 'get_workouts',
    ));
    register_rest_route('f3/v1', '/pax', array(
        'methods' => 'GET',
        'callback' => 'get_pax',
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
        'post_title' => $aoObj->name,
        'post_type' => 'workout',
        'meta_input' => array('date' => $data['date'])
    ));

    wp_set_object_terms($newPost, $aoObj->term_id, 'ao');

    return array('id' => $newPost);
}

function get_pax(WP_REST_Request $request)
{
    $users = get_users(array('fields' => array('ID', 'user_login')));
    $retval = array();

    foreach ($users as $user_id) {
        $meta = get_user_meta($user_id->ID);
        $retval[] = array(
            "id" => $user_id->ID,
            "f3" => $user_id->user_login,
            "avatar" => get_avatar_url($user_id->ID)
        );
    }

    return $retval;
}

function get_aos(WP_REST_Request $request)
{
    $aos = get_terms(array('ao'));

    $retval = array();

    foreach ($aos as $aoObj) {
        $meta = get_term_meta($aoObj->term_id);
        $loc = get_term_meta($aoObj->term_id, 'location', true);

        $retval[] = array(
            'id' => $aoObj->term_id,
            'name' => $aoObj->name,
            'slug' => $aoObj->slug,
            'description' => $aoObj->description,
            'color' => $meta['color'][0],
            'time' => $meta['time'][0],
            'days' => get_term_meta($aoObj->term_id, 'days', true),
            'loc' => array('lat' => floatval($loc['lat']), 'lng' => floatval($loc['lng'])),
            'thumb' => wp_get_attachment_thumb_url($meta['thumb_id'][0])
        );
    }

    return $retval;
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
        'posts_per_page' => 500,
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

    $aoList = get_terms(array('ao'));
    $aos = array();
    foreach ($aoList as $aoObj) {
        $meta = get_term_meta($aoObj->term_id);

        $aos[$aoObj->slug] = array(
            'id' => $aoObj->term_id,
            'name' => $aoObj->name,
            'color' => $meta['color'][0],
            'thumb' => wp_get_attachment_thumb_url($meta['thumb_id'][0])
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
            $result['ao'] = $aos[$ao[0]->slug];
        }

        $pax = get_post_meta($workout->ID, 'pax');
        if (isset($pax)) {
            $paxIds = array();
            foreach ($pax as $paxId) {
                $paxIds[] = $paxId;
            }

            $result['pax'] = $paxIds;
        }

        $qic = get_post_meta($workout->ID, 'qic', true);
        $result['qic'] = $userMap[$qic];

        $results[] = $result;
    }

    return $results;
}