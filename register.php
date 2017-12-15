<?php
add_action( 'register_form', 'f3_register_form' );
function f3_register_form() {

    $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
    $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';

    ?>
    <p>
        <label for="first_name"><?php _e( 'First Name', 'mydomain' ) ?><br />
            <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
    </p>

    <p>
        <label for="last_name"><?php _e( 'Last Name', 'mydomain' ) ?><br />
            <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
    </p>

    <?php
}

//2. Add validation. In this case, we make sure first_name is required.
add_filter( 'registration_errors', 'f3_registration_errors', 10, 3 );
function f3_registration_errors( $errors, $sanitized_user_login, $user_email ) {

    if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
        $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: You must include a first name.', 'mydomain' ) );
    }
    if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['first_name'] ) == '' ) {
        $errors->add( 'last_name_error', __( '<strong>ERROR</strong>: You must include a first name.', 'mydomain' ) );
    }
    return $errors;
}

//3. Finally, save our extra registration user meta.
add_action( 'user_register', 'f3_user_register' );
function f3_user_register( $user_id ) {
    if ( ! empty( $_POST['first_name'] ) ) {
        update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
        update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
    }
}

if ($pagenow == 'wp-login.php') {
    add_filter('gettext', 'user_email_login_text', 20, 3);
    function user_email_login_text($translated_text, $text, $domain)
    {
        if ($translated_text == 'Username') {
            $translated_text = 'F3 Name (Username)';
        }

        return $translated_text;
    }
}