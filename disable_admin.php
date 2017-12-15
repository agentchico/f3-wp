<?php

add_action('admin_menu', 'remove_links');

function remove_links()
{
    if (!current_user_can('administrator')) {
        remove_menu_page('edit-comments.php');
        remove_menu_page('upload.php');
        remove_menu_page('index.php');
        remove_menu_page('tools.php');
        remove_menu_page('profile.php');
        remove_menu_page('wpcf7');

//        add_action( 'admin_menu', 'custom_menu_page_removing' );
//        wp_redirect(home_url());
//        show_admin_bar(false);
    }

}
