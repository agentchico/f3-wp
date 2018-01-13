<?php

get_header();

$aos = get_terms('ao');

foreach ($aos as $ao) {

    $recentQuery = new WP_Query(array(
        'post_type' => 'workout',
        'posts_per_page' => 3,
        'tax_query' => array(
            array(
                'taxonomy' => 'ao',
                'field' => 'slug',
                'terms' => $ao->slug,
            )
        ),
        'order' => 'DESC',
        'meta_type' => 'date',
        'meta_key' => 'date',
        'orderby' => 'meta_value',
        'meta_query' => array(
            'key' => 'date',
            'value' => date('Y-m-d'),
            'type' => 'date',
            'compare' => '<'
        )
    ));

    $backblasts = $recentQuery->posts;

    $upcomingQuery = new WP_Query(array(
        'post_type' => 'workout',
        'posts_per_page' => 3,
        'order' => 'ASC',
        'post_status' => 'any',
        'meta_type' => 'date',
        'meta_key' => 'date',
        'orderby' => 'meta_value',
        'tax_query' => array(
            array(
                'taxonomy' => 'ao',
                'field' => 'slug',
                'terms' => $ao->slug,
            )
        ),
        'meta_query' => array(
            'key' => 'date',
            'value' => date('Y-m-d'),
            'type' => 'date',
            'compare' => '>='
        )
    ));
    $upcoming = $upcomingQuery->posts;
    ?>

    <div class="f3-ao">
        <div class="f3-ao-section">
            <div>
                <div class="ao-list-map"
                     data-lat="<?php echo get_term_meta($ao->term_id, 'location')[0]['latitude'] ?>"
                     data-lon="<?php echo get_term_meta($ao->term_id, 'location')[0]['longitude'] ?>"></div>
            </div>
            <div class="ao-list-info">
                <h3><?php echo $ao->name ?></h3>
                <p><?php echo $ao->description ?></p>
                <p><b>Days of Week: </b><?php echo implode(', ', get_term_meta($ao->term_id, 'days')[0]) ?></p>
                <p><b>Time: </b><?php echo get_term_meta($ao->term_id, 'time', true) ?></p>
            </div>
        </div>
        <div class="f3-ao-section">
            <div>
                <h4>Upcoming Workouts</h4>
                <?php if (empty($upcoming)) { ?>
                    <span class="f3-nothing-scheduled">No workouts scheduled</span>
                <?php } ?>
                <?php foreach ($upcoming as $p) { ?>
                    <?php echo get_post_meta($p->ID, 'date', true) ?> :
                    <a href="<?php echo get_permalink($p->ID) ?>"><?php echo $p->post_title ?></a><br/>
                <?php } ?>
            </div>

            <div>
                <h4>Recent BackBlasts</h4>
                <?php foreach ($backblasts as $p) { ?>
                    <?php echo get_post_meta($p->ID, 'date', true) ?> :
                    <a href="<?php echo get_permalink($p->ID) ?>"><?php echo $p->post_title ?></a><br/>
                <?php } ?>

                <?php if (empty($backblasts)) { ?>
                    <span class="f3-nothing-scheduled">No BackBlasts posted</span>
                <?php } elseif (sizeof($backblasts) == 3) { ?>
                    <div class="ao-view-all-link"><a href="/ao/<?php echo $ao->slug ?>">View All</a><br/></div>
                <?php } ?>
            </div>
        </div>
        <hr/>
    </div>

    <?php
}

get_footer();
?>
