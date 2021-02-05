<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 17/06/19
 * Time: 3:01 PM
 */

if( !defined( 'ABSPATH') ) exit();

add_action( 'custom_profile_dashboard', 'custom_profile_dashboard' );

function custom_profile_dashboard() {
    wp_enqueue_style( 'custom-profile-dashboard', '/wp-content/themes/maxinet/customer-profile/css/dashboard.css' );
    wp_enqueue_script( 'custom-profile-dashboard', '/wp-content/themes/maxinet/customer-profile/js/dashboard.js', array( 'jquery' ), '', true );
    wp_enqueue_script( 'autocomplete', '/wp-content/themes/maxinet/broadband-application/js/google-place-autocomplete.js', array(), '', true );

    require ( MAXINET_THEME_DIR . 'customer-profile/dashboard.php');
}

add_action( 'wp_ajax_save_meta', 'save_meta' );
function save_meta() {
    $post = $_POST;
    $user_id = get_current_user_id();
    foreach ( $post as $key => $value ) {
        if ( $key == 'action' ) continue;
        if ( $key == 'password' ) {
            wp_set_password( $value, $user_id );
        } else {
            update_user_meta( $user_id, $key, $value );
        }
    }
    wp_send_json_success();
    exit;
}