<?php

class Custom_Woocommerce_Status_For_Subscription
{

    /**
     * Initialize Hooks.
     *
     * @access public
     */
    public function run()
    {
        // a woocommerce function to register new woocommerce status
        add_action('init', array($this, 'register_pending_active_order_statuses'), 100);

        /**
         * Following hooks are from woocommerce. You can find its implementation for on-hold status
         * in file `woocommerce-subscriptions/includes/class-wc-subscriptions-manager.php`
         */
        add_filter('wc_order_statuses', array($this, 'pending_active_wc_order_statuses'), 100, 1);
        add_action('woocommerce_order_status_pending-active', array($this, 'put_subscription_on_pending_active_for_order'), 100);
    }

    /**
     * Registered new woocommerce status for `Pending Active`.
     *
     * @access public
     *
     */
    public function register_pending_active_order_statuses()
    {
        register_post_status('wc-pending-active', array(
            'label' => _x('Pending Active', 'Order status', 'custom-wcs-status-texts'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Pending Active <span class="count">(%s)</span>', 'Pending Active<span class="count">(%s)</span>', 'woocommerce'),
        ));
    }

    /**
     * Add new status `Pending Active` to $order_statuses array.
     *
     * @access public
     *
     * @param array $order_statuses current order statuses array.
     * @return array $order_statuses with the new status added to it.
     */
    public function pending_active_wc_order_statuses($order_statuses)
    {
        $order_statuses['wc-pending-active'] = _x('Pending Active', 'Order status', 'custom-wcs-status-texts');
        return $order_statuses;
    }

    /**
     * Change status of all the subscription in an order to `Pending Active` when order status is changed to `Pending Active`.
     *
     * @param object $order woocommerce order.
     * @access public
     */
    public function put_subscription_on_pending_active_for_order($order)
    {
        $subscriptions = wcs_get_subscriptions_for_order($order, array('order_type' => 'parent'));

        if (!empty($subscriptions)) {
            foreach ($subscriptions as $subscription) {
                try {
                    if (!$subscription->has_status(wcs_get_subscription_ended_statuses())) {
                        $subscription->update_status('pending-active');
                    }
                } catch (Exception $e) {
                    // translators: $1: order number, $2: error message
                    $subscription->add_order_note(sprintf(__('Failed to update subscription status after order #%1$s was put to pending-active: %2$s', 'woocommerce-subscriptions'), is_object($order) ? $order->get_order_number() : $order, $e->getMessage()));
                }
            }

            // Added a new action the same way subscription plugin has added for on-hold
            do_action('subscriptions_put_to_pending_active_for_order', $order);
        }
    }
}
