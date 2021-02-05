<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 23/05/19
 * Time: 11:41 AM
 */

if( !defined( 'ABSPATH') ) exit();

function application_shortcode() {
    require ( MAXINET_THEME_DIR . 'broadband-application/application.php');
}
add_shortcode( 'broadband_application', 'application_shortcode' );

function personal_detail_shortcode() {
    if ( empty( $_POST ) && !isset( $_COOKIE['application_data'] ) )
        echo '<script>window.location.href = "/broadband-application";</script>';
    else
        require ( MAXINET_THEME_DIR . 'broadband-application/personal-detail.php');
}
add_shortcode( 'broadband_application_personal_detail', 'personal_detail_shortcode' );

function register_script() {
    wp_register_script( 'bootstrap-js', '/wp-content/themes/maxinet/broadband-application/js/bootstrap.min.js', array( 'jquery' ), '', true );
    wp_register_style( 'bootstrap-css', '/wp-content/themes/maxinet/broadband-application/css/bootstrap.min.css' );
    wp_register_style( 'font-awesome-css', '/wp-content/themes/maxinet/broadband-application/font-awesome/css/font-awesome.min.css' );
    wp_register_script( 'jquery-redirect-js', '/wp-content/themes/maxinet/js/jquery.redirect.js', array( 'jquery' ), '', true );
    wp_enqueue_script( 'bootstrap-js' );
    wp_enqueue_style( 'bootstrap-css' );
    wp_enqueue_style( 'font-awesome-css' );
    wp_enqueue_script( 'jquery-redirect-js' );
}

add_action( 'init', 'register_script' );

function check_for_shortcode() {

    // false because we have to search through the posts first
    $found = false;

    if ( stripos( $_SERVER['REQUEST_URI'], 'broadband-application' ) ) {
        $found = true;
    }

    if ( is_account_page() ) {
        $found = true;
    }

    if ( !$found ) {
        wp_dequeue_style( 'bootstrap-css' );
        wp_dequeue_script( 'bootstrap-js' );
    }
}
// perform the check when the_posts() function is called
add_action( 'wp_enqueue_scripts', 'check_for_shortcode', 9 );

add_action('admin_init', function (){wp_dequeue_style( 'bootstrap-css' );wp_dequeue_script( 'bootstrap-js' );} );

function check_user_status() {
    $post = $_POST;
    $status = [];
    if ( is_user_logged_in() ) {
        $status['status'] = 'is_login';
    } else {
        if ( email_exists( $post['email'] ) ) {
            $status['status'] = 'is_exist';
        } else {
            $status['status'] = false;
        }
    }
    echo json_encode( $status );
    exit;
}
add_action( 'wp_ajax_check_user_status', 'check_user_status' );
add_action( 'wp_ajax_nopriv_check_user_status', 'check_user_status' );

function app_create_user() {
    global $wpdb;
    $data = $_POST;

    $username = str_replace( ' ', '', strtolower( $data['first-name'] ) ) . '-' . str_replace( ' ', '', strtolower( $data['last-name'] ) );
    $user_data = [];
    $user_data['customer_status'] = 'on-going';
    if ( isset( $data['contact-number'] ) ) {
        $user_data['mobile'] = $data['contact-number'];
    }
    if ( isset( $data['first-name'] ) ) {
        $user_data['first_name'] = $data['first-name'];
    }
    if ( isset( $data['last-name'] ) ) {
        $user_data['last_name'] = $data['last-name'];
    }
    add_filter('pre_option_woocommerce_registration_generate_password', 'wcrm_enable_generate_password');
    $user_id = wc_create_new_customer( $data['email'], $username );
    remove_filter('pre_option_woocommerce_registration_generate_password', 'wcrm_enable_generate_password');

    foreach ( $user_data as $key => $value ) {
        update_user_meta( $user_id, $key, $value );
    }
    $c_data = array(
        'email' => $user_data['user_email'],
        'first_name' => ucfirst($user_data['first_name']),
        'last_name' => ucfirst($user_data['last_name']),
        'status' => $user_data['customer_status']
    );

    $wpdb->update("{$wpdb->prefix}wc_crm_customer_list", $c_data, array( 'c_id' => $user_id ));
    wc_set_customer_auth_cookie( $user_id );
    echo is_user_logged_in();
    exit;
}
add_action( 'wp_ajax_nopriv_app_create_user', 'app_create_user' );

function add_plan_to_cart() {
    $post = $_POST;
    $application_data = [
        "installation_address"      => $post["address"],
        "installation_street"       => $post["street"],
        "is_auckland"               => $post["is-auckland"],
        "installation_suburb"       => $post["suburb"],
        "installation_city"         => $post["city"],
        "installation_postcode"     => $post["postcode"],
        "contact_first_name"        => $post["first-name"],
        "contact_last_name"         => $post["last-name"],
        "connection_date"           => $post["connection-date"],
        "prefer_date"               => $post["prefer-date"],
        "contact_number"            => $post["contact-number"],
        "contact_email"             => $post["email"],
        "has_delivery_address"      => $post["has-delivery-address"],
        "has_landline"              => $post["has-landline"],
        "last_provider"             => $post["last-provider"],
        "current_provider_account"  => $post["current-provider-account"],
        "shipping_first_name"       => $post["delivery-name"],
        "shipping_company"          => $post["delivery-company"],
        "shipping_address_1"        => $post["delivery-address"],
        "shipping_address_2"        => $post["delivery-suburb"],
        "shipping_city"             => $post["delivery-city"],
        "shipping_postcode"         => $post["delivery-postcode"],
        "order_comments"            => $post["delivery-instruction"],
        "is_move_house"             => isset( $post["is-move-house"] ) && $post["is-move-house"] == 'true' ? true : null,
        "origin_subscription_id"    => isset( $post["origin-subscription-id"] ) ? $post["origin-subscription-id"] : null,
    ];
    WC()->cart->empty_cart();
    $plan_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $post['plan'] ) );
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $plan_id, 1 );
    $product_status = get_post_status( $plan_id );

    if ( $passed_validation && 'publish' === $product_status ) {
        WC()->cart->add_to_cart( $plan_id, 1, 0, array(), array( 'application_data' => $application_data ) );
    }

    if ( isset( $post['router'] ) && is_array( $post['router'] ) ) {
        foreach ( $post['router'] as $router_id ) {
            $quantity = $post['router-quantity-' . $router_id];
            $router = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $router_id ) );
            $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $router, $quantity );
            if ( $passed_validation && 'publish' === get_post_status( $router ) ) {
                WC()->cart->add_to_cart( $router, $quantity );
            }
        }
    }
    wp_send_json_success();
    exit;
}

add_action('wp_ajax_add_plan_to_cart', 'add_plan_to_cart');
add_action('wp_ajax_nopriv_add_plan_to_cart', 'add_plan_to_cart');

function add_application_data_to_subscription( $subscription, $posted_data, $order, $cart ) {
    $subscription_id = $subscription->get_id();
    $application_data = array();
    foreach ( $cart->get_cart_contents() as $cart_item_key => $values ) {
        if ( isset( $values['application_data'] ) ) {
            $application_data = $values['application_data'];
        }
    }
    if ( !empty( $application_data ) ) {
        foreach ( $application_data as $key => $value ) {
            update_post_meta( $subscription_id, '_' . $key, is_array( $value ) ? json_encode( $value ) : $value );
        }
    }
}
add_action( 'woocommerce_checkout_create_subscription', 'add_application_data_to_subscription', 10, 4 );

function add_posted_data_to_checkout() {
    //var_dump('<pre>', $_POST);
    //var_dump('<pre>', WC()->cart->get_cart());
}

add_action( 'woocommerce_checkout_after_customer_details', 'add_posted_data_to_checkout', 10 );

function populating_checkout_fields ( $value, $input ) {
    $cart = WC()->cart;
    $application_data = array();
    foreach ( $cart->get_cart_contents() as $cart_item_key => $values ) {
        if ( isset( $values['application_data'] ) ) {
            $application_data = $values['application_data'];
        }
    }
    if ( !empty( $application_data ) ) {
        $checkout_fields = array(
            'billing_first_name'    => $application_data['contact_first_name'],
            'billing_last_name'     => $application_data['contact_last_name'],
            'billing_address_1'     => $application_data['installation_street'],
            'billing_address_2'     => $application_data['installation_suburb'],
            'billing_city'          => $application_data['installation_city'],
            'billing_postcode'      => $application_data['installation_postcode'],
            'billing_phone'         => $application_data['contact_number'],
            'billing_email'         => $application_data['contact_email'],
        );
        foreach ( $checkout_fields as $key_field => $field_value ) {
            if ( $input == $key_field && ! empty( $field_value ) ) {
                $value = $field_value;
            }
        }
        return $value;
    }
    else {
        return null;
    }
}
add_filter( 'woocommerce_checkout_get_value', 'populating_checkout_fields', 10, 2 );

function custom_checkout_fields( $fields ) {
    unset( $fields['billing']['billing_state'] );
    unset( $fields['order']['order_comments'] );
    return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_checkout_fields' );
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );

function cancel_subscription() {
    $post = $_POST;
    $plan_id = $post['plan_id'];
    $subscription = wcs_get_subscription( $plan_id );
    $subscription->update_status( 'pending-cancel' );
    if ( $subscription->get_status() == 'pending-cancel' ) {
        wp_send_json_success();
    } else {
        wp_send_json_error('error');
    }
    exit;
}
add_action( 'wp_ajax_cancel_subscription', 'cancel_subscription' );

function switch_subscription() {
    $post = $_POST;
    $old_subscription_id = $post['old_subscription_id'];
    $new_plan_id = $post['new_plan_id'];
    //expire old plan
    $old_subscription = wcs_get_subscription( $old_subscription_id );
    //transfer old plan data to new plan
    $application_data = [
        'installation_address',
        'installation_street',
        'is_auckland',
        'installation_suburb',
        'installation_city',
        'installation_postcode',
        'contact_first_name',
        'contact_last_name',
        'connection_date',
        'contact_number',
        'contact_email',
        'has_delivery_address',
        'has_landline',
        'last_provider',
        'current_provider_account',
        'shipping_first_name',
        'shipping_company',
        'shipping_address_1',
        'shipping_address_2',
        'shipping_city',
        'shipping_postcode',
        'order_comments',
        'is_move_house',
        'origin_subscription_id',
    ];
    $new_subscription_data = array();
    foreach ( $application_data as $key ) {
        $new_subscription_data[ $key ] = get_post_meta( $old_subscription_id, '_' . $key, true );
    }
    $new_subscription_data['switch'] = array(
        'old_subs_id'                   => $old_subscription_id,
        'old_subs_next_payment_date'    => $old_subscription->get_date( 'next_payment' ),
        'old_subs_expire_date'          => gmdate( 'Y-m-d H:i:s', strtotime( $old_subscription->get_date( 'next_payment' ) . ' +7 day' ) ),
    );
    WC()->cart->empty_cart();
    $plan_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $new_plan_id ) );
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $plan_id, 1 );
    $product_status = get_post_status( $plan_id );

    if ( $passed_validation && 'publish' === $product_status ) {
        WC()->cart->add_to_cart( $plan_id, 1, 0, array(), array( 'application_data' => $new_subscription_data ) );
    }
    wp_send_json_success();
    exit;
}
add_action( 'wp_ajax_switch_subscription', 'switch_subscription' );