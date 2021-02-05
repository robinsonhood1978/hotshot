<?php

/**
 * A sample plugin to demonstrate how to create a custom Woocommerce Subscription status
 * For demonstration we will be implementing `On Hold` like functionality, and name the new status `Like On Hold`
 * Things covered in this sample plugin are :-
 * - Add new status to the status list.
 * - Show new status in drop down only if the current subscription has certain status.
 * - Handle update code for new status.
 * - Show status option in bulk action dropdown on the listing page
 * - Add custom color for the new status tag on the list page
 * - Handle bulk update action
 * - Add Same status in Woocommerce
 * - Mark all subscription in an order with same status if status is changed from woocommerce order page.
 *
 * @package custom-woocommerce-subscription-status
 *
 * Plugin Name:       Custom Woocommerce Subscription Status by Wisdmlabs.
 * Description:       A sample plugin to demostrate how to add a new custom woocommerce subscription status called `Like On Hold` which works similart to `On Hold`.
 * Version:           1.0.0
 * Author:            WisdmLabs
 * Author URI:        http://wisdmLabs.com/
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require plugin_dir_path(__FILE__) . 'class-custom-woocommerce-subscriptions-status.php';
$CWSS = new Custom_Woocommerce_Subscription_Status();
$CWSS->run(); // initiate the status hooks from woocommerce subscription

require plugin_dir_path(__FILE__) . 'class-custom-woocommerce-status-for-subscription.php';
$CWSFS = new Custom_Woocommerce_Status_For_Subscription();
$CWSFS->run(); // initiate the status hooks from woocommerce

add_action( 'woocommerce_thankyou', 'custom_thankyou_subscription_action', 50, 1 );
function custom_thankyou_subscription_action( $order_id ){
    if( ! $order_id ) return;

    $order = wc_get_order( $order_id ); // Get an instance of the WC_Order object

    // If the order has a 'processing' status and contains a subscription
    if( wcs_order_contains_subscription( $order ) && $order->has_status( 'processing' ) ) {

        // Get an array of WC_Subscription objects
        $subscriptions = wcs_get_subscriptions_for_order( $order_id );
        foreach( $subscriptions as $subscription_id => $subscription ){
            $switch = get_post_meta( $subscription_id, '_switch', true );
            if ( isset( $switch ) && $switch != '' ) {
                $old_subscription_id = $switch['old_subs_id'];
                $new_start_date = $switch['old_subs_expire_date'];
                $new_next_payment_date = $switch['old_subs_next_payment_date'];
                //expire old plan
                $old_subscription = wcs_get_subscription( $old_subscription_id );
                $old_subscription->update_dates( array( 'next_payment' => 0 ) );
                //set new plan
                $subscription->update_status( 'switching' );
                $subscription->update_dates( array( 'start' => 0 ) );
                $subscription->update_dates( array( 'next_payment' => 0 ) );
                $subscription->update_dates( array( 'end' => 0 ) );
                wp_schedule_single_event( strtotime( $new_start_date ), 'woocommerce_switching_order_start', array( $old_subscription_id, $subscription_id, $new_start_date, $new_next_payment_date ));
            } else {
                // Change the status of the WC_Subscription object
                $subscription->update_status( 'pending-active' );
                $subscription->update_dates( array( 'start' => 0 ) );
                $subscription->update_dates( array( 'end' => 0 ) );
                $id = $subscription->get_id();
                //put original subscription on hold, if applicable
                $is_move_house = get_post_meta( $id, '_is_move_house', true );
                $origin_subscription_id = get_post_meta( $id, '_origin_subscription_id', true );
                if ( isset( $is_move_house ) && $is_move_house != '' && isset( $origin_subscription_id ) && $origin_subscription_id != '' ) {
                    $origin_subscription = wcs_get_subscription( $origin_subscription_id );
                    $origin_subscription->update_status( 'on-hold' );
                }
            }
        }
    }
}

add_action( 'woocommerce_switching_order_start', 'switching_order_start', 10, 4 );
function switching_order_start( $old_subscription_id, $new_subscription_id, $new_start_date, $new_next_payment_date ) {
    //setting old subscription
    $old_subscription = wcs_get_subscription( $old_subscription_id );
    $old_subscription->update_status( 'expired' );
    //setting new subscription
    $new_subscription = wcs_get_subscription( $new_subscription_id );
    $new_subscription->update_status( 'active' );
    $new_subscription->update_dates( array( 'start' => gmdate( 'Y-m-d H:i:s', strtotime( $new_start_date ) ) ) );
    $new_subscription->update_dates( array( 'next_payment' => gmdate( 'Y-m-d H:i:s', strtotime( $new_next_payment_date . ' +1 month' ) ) ) );
    $new_subscription->update_dates( array( 'end' => 0 ) );
}

add_action( 'woocommerce_order_status_completed','updating_order_status_completed_with_subscription' );
function updating_order_status_completed_with_subscription( $order_id ) {
    $order = wc_get_order( $order_id );  // Get an instance of the WC_Order object

    if ( wcs_order_contains_subscription( $order ) ) {

        // Get an array of WC_Subscription objects
        $subscriptions = wcs_get_subscriptions_for_order( $order_id );
        foreach( $subscriptions as $subscription_id => $subscription ){
            // Change the status of the WC_Subscription object

            $subscription->update_status( 'active' );
            $subscription->update_dates( array( 'start' => gmdate( 'Y-m-d H:i:s' ) ) );
            $subscription->update_dates( array( 'end' => 0 ) );
        }
    }
}

add_action( 'woocommerce_subscription_status_updated', 'update_next_payment_date', 10, 3 );
function update_next_payment_date( $subscription, $new_status, $old_status ) {
    if ( $old_status == 'pending-active' && $new_status == 'active' ) {
        $subscription->update_dates( array( 'next_payment' => gmdate( 'Y-m-d H:i:s', strtotime( '+3 week' ) ) ) );
    } elseif ( $old_status == 'on-hold' && $new_status == 'active' ) {
        $subscription->update_dates( array( 'next_payment' => gmdate( 'Y-m-d H:i:s', strtotime( '+1 month' ) ) ) );
        $renewal_ids = $subscription->get_related_orders( 'ids', 'renewal' );
          foreach ( $renewal_ids as $id ) {
              $order = wc_get_order( $id );
              if ( $order->has_status( 'processing' ) ) {
                  remove_action( 'woocommerce_order_status_completed','updating_order_status_completed_with_subscription' );
                  $order->update_status( 'completed', 'Order status changed from processing to completed since renewal payment is received.' );
                  add_action( 'woocommerce_order_status_completed', 'updating_order_status_completed_with_subscription' );
              }
          }
    }
}

/* add_filter( 'wcs_default_retry_rules', 'custom_retry_rules' );
function custom_retry_rules( $default_retry_rules_array ) {
    return array(
        array(
            'retry_after_interval'            =>  DAY_IN_SECONDS,
            'email_template_customer'         => 'WCS_Email_Customer_Payment_Retry',
            'email_template_admin'            => 'WCS_Email_Payment_Retry',
            'status_to_apply_to_order'        => 'pending',
            'status_to_apply_to_subscription' => 'on-hold',
        ),
        array(
            'retry_after_interval'            =>  DAY_IN_SECONDS,
            'email_template_customer'         => '',
            'email_template_admin'            => 'WCS_Email_Payment_Retry',
            'status_to_apply_to_order'        => 'pending',
            'status_to_apply_to_subscription' => 'on-hold',
        ),
        array(
            'retry_after_interval'            =>  DAY_IN_SECONDS,
            'email_template_customer'         => 'WCS_Email_Customer_Payment_Retry',
            'email_template_admin'            => 'WCS_Email_Payment_Retry',
            'status_to_apply_to_order'        => 'pending',
            'status_to_apply_to_subscription' => 'on-hold',
        ),
    );
} */
