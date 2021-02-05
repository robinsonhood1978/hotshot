<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 17/06/19
 * Time: 3:01 PM
 */

if( !defined( 'ABSPATH' ) ) exit();

$user = new WP_User( get_current_user_id() );
$user_subscription = wcs_get_users_subscriptions( get_current_user_id() );

$plans = wc_get_products( array(
    'category' => array( 'slug' => 'subscription' ),
    'order'          => 'ASC',
    'orderby'        => 'date'
) );

?>
<div class="profile-wrapper">
    <div class="row">
        <div class="col-md-4 py-3 py-md-0">
            <div class="block-wrapper customer_details row box-shadow">
                <div class="block-icon col-md-12">
                    <i class="fa fa-user"></i>
                </div>

                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-label col-md-5">
                            Customer Name
                        </div>
                        <div class="block-detail-value col-md-7">
                            <div class="block-detail-value-field">
                                <?php if ( isset( $user->first_name ) && isset( $user->last_name ) ):
                                    echo $user->first_name . ' ' . $user->last_name;
                                else :
                                    echo isset( $user->first_name ) ? $user->first_name : '';
                                    echo isset( $user->last_name ) ? $user->last_name : '';
                                endif;?>
                            </div>
                            <input type="text" class="input-field hidden" name="first-name" id="first-name" title="First Name" placeholder="First Name" />
                            <input type="text" class="input-field hidden mt-2" name="last-name" id="last-name" title="Last Name" placeholder="Last Name" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-label col-md-5">
                            Email
                        </div>
                        <div class="block-detail-value col-md-7">
                            <?php echo $user->user_email; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-label col-md-5">
                            Contact Number
                        </div>
                        <div class="block-detail-value col-md-7">
                            <div class="block-detail-value-field">
                                <?php echo get_user_meta( $user->ID, 'mobile', true ); ?>
                            </div>
                            <input type="tel" class="input-field hidden" name="phone-number" id="phone-number" title="Phone Number" placeholder="Phone Number" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-label col-md-5">
                            Password
                        </div>
                        <div class="block-detail-value col-md-7">
                            <div class="block-detail-value-field">
                                *************
                            </div>
                            <input type="text" class="input-field hidden" name="new-password" id="new-password" title="Password" placeholder="New Password" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12 align-self-end">
                    <div class="block-button">
                        <a href="/" id="edit-personal-detail" class="sc_button color_style_link2 sc_button_default sc_button_size_small sc_button_icon_left">
                            <span class="sc_button_text">
                                <span class="sc_button_title">Edit</span>
                            </span>
                        </a>
                        <a href="/" id="cancel-personal-detail" class="sc_button color_style_link2 sc_button_default sc_button_size_small sc_button_icon_left hidden">
                            <span class="sc_button_text">
                                <span class="sc_button_title">Cancel</span>
                            </span>
                        </a>
                        <a href="/" id="save-personal-detail" class="sc_button color_style_link2 sc_button_default sc_button_size_small sc_button_icon_left hidden">
                            <span class="sc_button_text">
                                <span class="sc_button_title">Save</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 py-3 py-md-0">
            <div class="block-wrapper billing_details row box-shadow">
                <div class="block-icon col-md-12">
                    <i class="fa fa-book"></i>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-label col-md-5">
                            Billing Name
                        </div>
                        <div class="block-detail-value col-md-7">
                            <div class="block-detail-value-field">
                                <?php if ( get_user_meta( $user->ID, 'billing_first_name', true ) != '' && get_user_meta( $user->ID, 'billing_last_name', true ) != '' ) :
                                    echo get_user_meta( $user->ID, 'billing_first_name', true ) . ' ' . get_user_meta( $user->ID, 'billing_last_name', true );
                                else :
                                    echo get_user_meta( $user->ID, 'billing_first_name', true ) . get_user_meta( $user->ID, 'billing_first_name', true );
                                endif;?>
                            </div>
                            <input type="text" class="input-field hidden" name="billing-first-name" id="billing-first-name" title="Billing First Name" placeholder="Billing First Name" />
                            <input type="text" class="input-field hidden mt-2" name="billing-last-name" id="billing-last-name" title="Billing Last Name" placeholder="Billing Last Name" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-label col-md-5">
                            Billing Email
                        </div>
                        <div class="block-detail-value col-md-7">
                            <div class="block-detail-value-field">
                                <?php echo get_user_meta( $user->ID, 'billing_email', true ); ?>
                            </div>
                            <input type="email" class="input-field hidden" name="billing-email" id="billing-email" title="Billing Email" placeholder="Billing Email" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-label col-md-5">
                            Billing Phone
                        </div>
                        <div class="block-detail-value col-md-7">
                            <div class="block-detail-value-field">
                                <?php echo get_user_meta( $user->ID, 'billing_phone', true ); ?>
                            </div>
                            <input type="tel" class="input-field hidden" name="billing-phone" id="billing-phone" title="Billing Phone" placeholder="Billing Phone" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-label col-md-5">
                            Billing Address
                        </div>
                        <div class="block-detail-value col-md-7">
                            <div class="block-detail-value-field">
                                <?php echo get_user_meta( $user->ID, 'billing_address_1', true ) . ' ' . get_user_meta( $user->ID, 'billing_address_2', true ) . ' ' . get_user_meta( $user->ID, 'billing_city', true ) . ' ' . get_user_meta( $user->ID, 'billing_postcode', true ); ?>
                            </div>
                            <input type="text" class="input-field hidden autocomplete-address" name="street" id="street" title="Street" placeholder="Street" />
                            <input type="text" class="input-field hidden mt-2" name="suburb" id="suburb" title="Suburb" placeholder="Suburb" />
                            <input type="text" class="input-field hidden mt-2" name="city" id="city" title="City" placeholder="City" />
                            <input type="text" class="input-field hidden mt-2" name="postcode" id="postcode" title="Postcode" placeholder="Postcode" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12 align-self-end">
                    <div class="block-button text-center">
                        <a href="/" id="edit-billing-detail" class="sc_button color_style_link2 sc_button_default sc_button_size_small sc_button_icon_left">
                            <span class="sc_button_text">
                                <span class="sc_button_title">Edit</span>
                            </span>
                        </a>
                        <a href="/" id="cancel-billing-detail" class="sc_button color_style_link2 sc_button_default sc_button_size_small sc_button_icon_left hidden">
                            <span class="sc_button_text">
                                <span class="sc_button_title">Cancel</span>
                            </span>
                        </a>
                        <a href="/" id="save-billing-detail" class="sc_button color_style_link2 sc_button_default sc_button_size_small sc_button_icon_left hidden">
                            <span class="sc_button_text">
                                <span class="sc_button_title">Save</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 py-3 py-md-0">
            <div class="block-wrapper shipping_details row  box-shadow">
                <div class="block-icon col-md-12">
                    <i class="fa fa-ship"></i>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-label col-md-5">
                            Shipping Name
                        </div>
                        <div class="block-detail-value col-md-7">
                            <div class="block-detail-value-field">
                                <?php echo get_user_meta( $user->ID, 'shipping_first_name', true ) != '' ? get_user_meta( $user->ID, 'shipping_first_name', true ) : 'Unset'; ?>
                            </div>
                            <input type="text" class="input-field hidden" name="shipping-first-name" id="shipping-first-name" title="Shipping Name" placeholder="Shipping Name" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-label col-md-5">
                            Shipping Company
                        </div>
                        <div class="block-detail-value col-md-7">
                            <div class="block-detail-value-field">
                                <?php echo get_user_meta( $user->ID, 'shipping_company', true ) != '' ? get_user_meta( $user->ID, 'shipping_company', true ) : 'Unset'; ?>
                            </div>
                            <input type="text" class="input-field hidden" name="shipping-company" id="shipping-company" title="Shipping Company" placeholder="Shipping Company" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-label col-md-5">
                            Shipping Address
                        </div>
                        <div class="block-detail-value col-md-7">
                            <div class="block-detail-value-field">
                                <?php echo get_user_meta( $user->ID, 'shipping_address_1', true ) . ' ' . get_user_meta( $user->ID, 'shipping_address_2', true ) . ' ' . get_user_meta( $user->ID, 'shipping_city', true ) . ' ' . get_user_meta( $user->ID, 'shipping_postcode', true ); ?>
                            </div>
                            <input type="text" class="input-field hidden autocomplete-address" name="delivery-address" id="delivery-address" title="Shipping Street" placeholder="Shipping Street" />
                            <input type="text" class="input-field hidden mt-2" name="delivery-suburb" id="delivery-suburb" title="Shipping Suburb" placeholder="Shipping Suburb" />
                            <input type="text" class="input-field hidden mt-2" name="delivery-city" id="delivery-city" title="Shipping City" placeholder="Shipping City" />
                            <input type="text" class="input-field hidden mt-2" name="delivery-postcode" id="delivery-postcode" title="Shipping Postcode" placeholder="Shipping Postcode" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12 align-self-end">
                    <div class="block-button text-center">
                        <a href="/" id="edit-shipping-detail" class="sc_button color_style_link2 sc_button_default sc_button_size_small sc_button_icon_left">
                            <span class="sc_button_text">
                                <span class="sc_button_title">Edit</span>
                            </span>
                        </a>
                        <a href="/" id="cancel-shipping-detail" class="sc_button color_style_link2 sc_button_default sc_button_size_small sc_button_icon_left hidden">
                            <span class="sc_button_text">
                                <span class="sc_button_title">Cancel</span>
                            </span>
                        </a>
                        <a href="/" id="save-shipping-detail" class="sc_button color_style_link2 sc_button_default sc_button_size_small sc_button_icon_left hidden">
                            <span class="sc_button_text">
                                <span class="sc_button_title">Save</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-5">
        <div class="col-md-4 py-3 py-md-0">
            <div class="block-wrapper shipping_details row  box-shadow">
                <div class="block-icon col-md-12">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-icon col-md-12">
                            Change Credit Card
                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-value col-md-12">
                            <div class="block-detail-value-field">
                                Stored credit card expired? Switched to a new bank? Want to use a better looking credit card? No worries! Update your credit card details here.
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 align-self-end">
                    <div class="block-button text-center" onclick="javascript:location.href='/checkout/?add-to-cart=13770&quantity=1';">
                        <select class="subscription-action" name="subscription-action" id="subscription-action-4354" title="Action" data-subscription_id="4354">
                            <option value="action" disabled selected>EDIT</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 py-3 py-md-0">
            <div class="block-wrapper shipping_details row  box-shadow">
                <div class="block-icon col-md-12">
                    <i class="fa fa-file"></i>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-icon col-md-12">
                            Order Records and Invoices
                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block-detail row">
                        <div class="block-detail-value col-md-12">
                            <div class="block-detail-value-field">
                                Here are the records of your completed orders, payment history and invoices.
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 align-self-end">
                    <div class="block-button text-center" onclick="javascript:location.href='/my-account/orders/';">
                        <select class="subscription-action" name="subscription-action" id="subscription-action-4354" title="Action" data-subscription_id="4354">
                            <option value="action" disabled selected>EDIT</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 py-3 py-md-0"></div>
    </div>
        <div class="row pt-5">
        <?php foreach ( $user_subscription as $subscription ):
			
		?>

            <div class="col-md-4 py-3 pt-md-0">
                <div class="block-wrapper row  box-shadow">
                    <?php foreach ( $subscription->get_items() as $item ): ?>
					
					
                        <div class="block-icon col-md-12">
                            <img src="<?php echo get_the_post_thumbnail_url( $item->get_product_id(), apply_filters( 'single_product_archive_thumbnail_size', 'full' ) );?>" style="max-height:100px; margin: 15px 0; "/>
                        </div>
                        <div class="col-md-12">
                            <div class="block-detail row">
                                <div class="block-detail-label col-md-5">
                                    Plan Name
                                </div>
                                <div class="block-detail-value col-md-7">
                                    <?php echo get_the_title( $item->get_product_id() ); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="col-md-12">
                        <div class="block-detail row">
                            <div class="block-detail-label col-md-5">
                                Installation Address
                            </div>
                            <div class="block-detail-value col-md-7">
                                <?php echo get_post_meta( $subscription->get_id(), '_installation_address', true ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="block-detail row">
                            <div class="block-detail-label col-md-5">
                                Plan Status
                            </div>
                            <div class="block-detail-value col-md-7">
                                <?php 
								if($subscription->get_status() == 'pending') { echo 'Pending'; }
								else if($subscription->get_status() == 'active') { echo 'Active'; }
								else { echo $subscription->get_status(); } ?> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="block-detail row">
                            <div class="block-detail-label col-md-5">
                                Start Date
                            </div>
                            <div class="block-detail-value col-md-7">
                                <?php $date = explode( ' ', $subscription->get_date( 'start' ) ); echo $date[0]; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="block-detail row">
                            <div class="block-detail-label col-md-5">
                                Next Payment Date
                            </div>
                            <div class="block-detail-value col-md-7">
                                <?php $date = explode( ' ', $subscription->get_date( 'next_payment' ) ); echo $date[0]; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 align-self-end">
                        <div class="block-button text-center">
                            <?php if ( $subscription->get_status() == 'pending' ||  $subscription->get_status() == 'on-hold' ): ?>
                            <?php $related_orders_ids_array = $subscription->get_related_orders();
                                foreach ( $related_orders_ids_array as $id ) {
                                    $order = wc_get_order( $id );
                                    if ( $order->get_status() == 'pending' ) {
                                    $link = esc_url( $order->get_checkout_payment_url() );
                                    }
                                }
                            ?>
                                <a class="sc_price_item_link sc_button" href="<?php echo $link; ?>">Pay Balance</a>
                            <?php else: ?>
							
							 <select class="subscription-action" name="subscription-action" id="subscription-action-<?php echo $subscription->get_id(); ?>" title="Action" data-subscription_id="<?php echo $subscription->get_id(); ?>">
                                    <option value="action" disabled selected>ACTION</option>
                                    <?php if ( $subscription->get_status() == 'active' ): ?>
                                        <option value="move">Moving</option>
                                        <option value="cancel">Cancel</option>
                                    <?php endif; ?>
                                </select>
							
							<?php
								$subscription_status = $subscription->get_status();
								if( ($subscription_status == 'active') || ($subscription_status == 'pending-active') ) :
								?>
								<br/>
								<a href="<?php echo esc_url( $subscription->get_view_order_url() ) ?>" class="sc_button button view"><?php echo esc_html_x( 'Switch Plan', 'view a subscription', 'woocommerce-subscriptions' ); ?></a>
							
                            <?php endif; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ( $subscription->get_status() == 'active' ): ?>
                <a class="hide switch-plan-<?php echo $subscription->get_id();?>" data-toggle="modal" data-target="#switch-subscription-modal-<?php echo $subscription->get_id();?>"></a>
                <div class="modal fade" id="switch-subscription-modal-<?php echo $subscription->get_id();?>" tabindex="-1" role="dialog" aria-labelledby="switch-subscription-modal-<?php echo $subscription->get_id();?>" aria-hidden="true" data-subscription_id="<?php echo $subscription->get_id();?>">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="switch-subscription-modal-title">Choose a subscription plan</h5>
                                <a class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                            </div>
                            <div class="modal-body">
                                <?php
                                    $exclude = array();
                                    foreach ( $subscription->get_items() as $item ) {
                                        $product = $item->get_product();
                                        $exclude[] = $product->get_id();
                                    }
                                ?>
                                <?php if ( get_post_meta( $subscription->get_id(), '_is_auckland' ) == 'auckland' ): ?>
                                    <?php foreach ( $plans as $plan ): ?>
                                        <?php if ( !in_array( $plan->get_id(), $exclude ) ): ?>
                                            <div class="w-100">
                                                <input type="radio" class="" name="new-subscription-plan-<?php echo $subscription->get_id(); ?>" id="<?php echo $subscription->get_id(); ?>-to-<?php echo $plan->get_id(); ?>" value="<?php echo $plan->get_id(); ?>" data-old_plan="<?php echo $subscription->get_id(); ?>"/>
                                                <label for="<?php echo $subscription->get_id(); ?>-to-<?php echo $plan->get_id(); ?>">
                                                    <?php echo get_the_title( $plan->get_id() ); ?>
                                                </label>
                                            </div>
                                        <?php endif; 
									endforeach; ?>
                                <?php else: ?>
                                    <p>Sorry, there is no more available plans in your address.</p>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <?php if ( get_post_meta( $subscription->get_id(), '_is_auckland' ) == 'auckland' ): ?>
                                    <button type="button" class="btn btn-default" data-target="new-subscription-plan-<?php echo $subscription->get_id(); ?>" disabled>Confirm</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    </div>
</div>
