<?php

function send_workout_email($post_id, $user_id)
{
    $post_title = get_the_title($post_id);
    $post_url = get_permalink($post_id);

    $subject = $post_title;

    ob_start();

    include("./email_header.php");

    ?>

    <h1><?php echo $post_title ?></h1>
    <h3><?php echo $post_title ?></h3>
    <?php the_excerpt() ?>

    <a href="<?php the_permalink() ?>"
       style="display:inline-block;text-decoration:none;text-align:center;background-color:#ffffff;border-color:#00ddff;border-width:1px;border-radius:5px;border-style:solid;width:118px;line-height:30px;color:#00ddff;font-family:Arial;font-size:20px;font-weight:normal"
       target="_blank">
        Read more
    </a>

    <?php
    include("./email_footer.php");

    $message = ob_get_contents();
    ob_end_clean();

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail('andrew@asmiller.com', $subject, $message, $headers);
}

function f3_new_post($post_id)
{
    // If this is just a revision, don't send the email.
    if (wp_is_post_revision($post_id))
        return;

    $type = get_post_type($post_id);

    if ($type == 'workout') {
        send_workout_email($post_id, 1);
    }
}

add_action('save_post', 'f3_new_post');