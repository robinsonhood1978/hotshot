<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 23/05/19
 * Time: 11:55 AM
 */

if( !defined( 'ABSPATH') ) exit();

wp_enqueue_style( 'application-css', '/wp-content/themes/maxinet/broadband-application/css/application.css' );
wp_enqueue_script( 'autocomplete', '/wp-content/themes/maxinet/broadband-application/js/google-place-autocomplete.js', array(), '', true );
wp_enqueue_script( 'application-js', '/wp-content/themes/maxinet/broadband-application/js/application.js', array(), '', true );

$is_move_house = isset( $_POST['is_move_house'] ) && $_POST['is_move_house'] == 'true' ? true : false;
$origin_subscription_id = isset( $_POST['origin_subscription_id'] ) ? $_POST['origin_subscription_id'] : false;

$products = wc_get_products( array(
    'category' => array( 'slug' => 'router' ),
  	'order'          => 'ASC',
    'orderby'        => 'date'
) );

$plans = wc_get_products( array(
    'category' => array( 'slug' => 'subscription' ),
    'order'          => 'ASC',
    'orderby'        => 'date'
) );

if ( is_user_logged_in() ) {
	
	$user_active_subcription_product_id = '';
	$user = wp_get_current_user();
	
	if ( in_array( 'customer', (array) $user->roles ) ) {
		
		$user_id = $user->ID;
		$user_active_subscription = WC_Subscriptions_Manager::get_users_subscriptions($user_id);
		foreach ($user_active_subscription as $single_subscription){
			if( $single_subscription['status'] == 'active'){
				$user_active_subcription_product_id = $single_subscription['product_id'];
			}
		}
		
		$html = '<div class="custom-label">Current Plan</div>';
	}
    
} else {
    $html = '';
}

    

?>
<div class="form-container">
    <form id="application">
        <div class="row">
            <div class="col-md-12">
                <h4 class="address-search-title">Nice! You've selected HotShot Unlimited Broadband.</h4>
            </div>
        </div>

        <div class="row py-3 my-3">
            <div class="col-md-12">
                <div class="section-heading">
                    <div class="heading-number address-search-heading-number">
                        <span>1</span>
                    </div>
                    <h5 class="heading-title address-search-section-title">Check if you can get it at your place.</h5>
                </div>
                <p class="heading-description">Pop your home address in here to see if you can get HotShot Ultra Fast Broadband at your place.</p>
            </div>
            <div class="col-md-8">
                <div class="address-form-group-wrapper">
                    <div class="form-group address-field">
                        <?php if ( $is_move_house && $origin_subscription_id != false ): ?>
                            <input type="hidden" id="is-move-house" name="is-move-house" value="true" />
                            <input type="hidden" id="origin-subscription-id" name="origin-subscription-id" value="<?php echo $origin_subscription_id; ?>" />
                        <?php endif; ?>
                        <input class="form-control autocomplete-address" type="text" placeholder="Start typing your address" id="address" name="address" />
                        <input type="hidden" id="is-auckland" name="is-auckland" />
                        <input type="hidden" disabled="disabled" id="lat" />
                        <input type="hidden" disabled="disabled" id="lng" />
                        <input type="hidden" id="street" name="street" />
                        <input type="hidden" id="suburb" name="suburb" />
                        <input type="hidden" id="city" name="city" />
                        <input type="hidden" id="postcode" name="postcode" />
                    </div>
                </div>
                <input type="hidden" name="ufb-available" id="ufb-available" />
                <div class="address-search-messages">
                    <div class="address-search-messages-wrapper">
                        <h6 class="address-search-messages-show-address"></h6>
                        <p class="address-search-clear">
                            <a class="address-search-messages-clear-address" href="/">Change address</a>
                        </p>
                    </div>
                    <div class="address-search-messages-text-wrapper">
                        <div class="address-search-icon"></div>
                        <div class="address-search-messages-text"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row py-3 my-3 is-available">
            <div class="col-md-8 sticky-div" >
                <div class="choose-speed">
                    <div class="section-heading">
                        <div class="heading-number speed-choose-heading-number">
                            <span>2</span>
                        </div>
                        <h5 class="heading-title speed-choose-section-title">Choose your speed</h5>
                    </div>
                    <p class="heading-description">Select the speed of your Fibre <i class="fa fa-question-circle plan-speed-tip" data-toggle="modal" data-target="#plan-speed-tip-modal"></i></p>
                    <?php foreach ( $plans as $plan ): ?>
                    <div class="row speed-section is-country-<?php echo $plan->get_attribute( 'isCountry' );?> <?php if($user_active_subcription_product_id == $plan->get_id()){ echo 'fc-active-plan'; } ?>">
                        <div class="col-sm-9 px-sm-0">
                            <input class="form-control" type="radio" id="speed-<?php echo $plan->get_id(); ?>" name="plan" value="<?php echo $plan->get_id(); ?>" data-plan_fee="<?php echo $plan->get_price(); ?>" data-signup_fee="<?php echo $plan->get_sign_up_fee_excluding_tax(); ?>" data-label="<?php echo get_the_title( $plan->get_id() ); ?>" <?php if($user_active_subcription_product_id == $plan->get_id()){ echo 'disabled'; } ?> />
                            <label for="speed-<?php echo $plan->get_id(); ?>" class="radio-label"><?php echo get_the_title( $plan->get_id() ); if($user_active_subcription_product_id == $plan->get_id()){ echo $html; }  ?></label>
                            <p class="radio-description "><?php echo get_the_excerpt( $plan->get_id() ); ?></p>
                            <div class="plan-speeds">
                                <div class="plan-speed-download">
                                    <div class="plan-up fa fa-arrow-circle-o-up font-large left "></div>
                                    <div class="plan-speed-text ">
                                        <a class="tooltip-link" data-toggle="modal" data-target="#speed-mbps-tip-modal"><span> <?php echo $plan->get_attribute( 'download' );?>Mbps</span></a>
                                        download
                                    </div>
                                </div>
                                <div class="plan-speed-upload">
                                    <div class="plan-down fa fa-arrow-circle-o-down font-large left "></div>
                                    <div class="plan-speed-text ">
                                        <a class="tooltip-link" data-toggle="modal" data-target="#speed-mbps-tip-modal"><span> <?php echo $plan->get_attribute( 'upload' );?>Mbps</span></a>
                                        upload
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 px-sm-0 py-3 py-sm-0">
                            <div class="speed-price">$<?php echo $plan->get_price(); ?></div>
                            <div class="speed-price-suffix">per month</div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="choose-router">
                    <div class="section-heading">
                        <div class="heading-number speed-choose-heading-number">
                            <span>3</span>
                        </div>
                        <h5 class="heading-title speed-choose-section-title">Choose your router</h5>
                    </div>
                    <div class="router-section">
                        <h5 class="router-choose-title">Do you want to buy a router from us?</h5>
                        <input class="form-control" type="radio" id="is-byo-router-no" name="is-byo-router" value="false" checked="checked" />
                        <label for="is-byo-router-no" class="radio-label">No</label>
                        <input class="form-control" type="radio" id="is-byo-router-yes" name="is-byo-router" value="true" />
                        <label for="is-byo-router-yes" class="radio-label">Yes</label>
                    </div>
                    <div id="not_buy_router">
                        Some modem setting changes needed. Not sure if your modem is compatible?
                        <a href="/support-centre/everything-about-hotshot-modem/" target="_blank">Here</a> are some guidelins on how to configure your router.
                    </div>
                    <div class="is-router">
                        <p class="heading-description">Select the router for your Fibre</p>
                        <?php foreach ( $products as $product ): ?>
                            <div class="row choose-router-section">
                                <div class="col-sm-9 d-flex align-items-center">
                                    <div class="">
                                        <input class="form-control router-selection" type="checkbox" id="router-<?php echo $product->get_id(); ?>" name="router[]" value="<?php echo $product->get_id(); ?>" data-router_fee="<?php echo $product->get_price(); ?>" data-router_name="<?php echo get_the_title( $product->get_id() ); ?>" />
                                        <label for="router-<?php echo $product->get_id(); ?>" class="checkbox-label"></label>
                                    </div>
                                    <div class="d-flex flex-sm-row flex-column align-items-center w-100">
                                        <span class="router-choose-image mx-3"><img src="<?php echo get_the_post_thumbnail_url( $product->get_id(), apply_filters( 'single_product_archive_thumbnail_size', 'thumbnail' ) );?>" /></span>
                                        <div class="router-details d-sm-flex flex-sm-column text-sm-left text-center w-100">
                                            <h5 class="router-choose-title"><?php echo get_the_title( $product->get_id() ); ?></h5>
                                            <div class="router-description"><?php echo get_the_excerpt( $product->get_id() ); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 d-sm-flex align-items-sm-center py-sm-0 py-3">
                                    <div class="router-price w-100">$<?php echo $product->get_price(); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- floating summary -->
            <div class="col-md-4" id="sidebar">
                <div class="summary-container stick">
                    <div class="summary-heading">
                        <div class="section-heading">
                            <h5 class="heading-title summary-heading-title">Summary</h5>
                        </div>
                    </div>
                    <div class="sub-section-container">
                        <h6 class="sub-section-title">Your address</h6>
                        <div class="sub-section">
                            <div class="sub-section-left-content">
                                <p class="plan-summary-text plan-summary-address-text"></p>
                            </div>
                        </div>
                        <div class="sub-section">
                            <div class="sub-section-left-content">
                                <a class="summary-clear-address" href="/">Change</a>
                            </div>
                        </div>
                    </div>
                    <div class="sub-section-container">
                        <h6 class="sub-section-title">Monthly plan</h6>
                        <div class="sub-section">
                            <div class="sub-section-left-content">
                                <p class="plan-summary-text plan-summary-speed-text"></p>
                                <p class="plan-summary-text plan-summary-contract-text">No term contract</p>
                            </div>
                        </div>
                        <div class="sub-section border-top">
                            <div class="sub-section-left-content">
                                <p class="plan-summary-text font-weight-bold">Plan per month</p>
                            </div>
                            <div class="sub-section-right-content">
                                <p class="plan-summary-text plan-summary-plan-price-text font-weight-bold"></p>
                                <input type="hidden" id="plan-fee" name="plan-fee" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="sub-section-container">
                        <h6 class="sub-section-title">Total to pay now</h6>
                        <div class="sub-section">
                            <div class="sub-section-left-content">
                                <p class="plan-summary-text">Connection fee</p>
                            </div>
                            <div class="sub-section-right-content">
                                <p class="plan-summary-text plan-summary-connection-fee-text"></p>
                                <input type="hidden" name="connection-fee" id="connection-fee" />
                            </div>
                        </div>
                        <div class="sub-section show-router-fee hidden" id="show-router-fee">
                            <div class="sub-section-left-content" style="flex: 75%;">
                                <p class="plan-summary-text plan-summary-router-title-text"></p>
                            </div>
                            <div class="sub-section-center-content" style="flex: 15%;">
                                <div class="plan-summary-router-quantity-selection">
                                    <div class="router-quantity-selection-minus"><i class="fa fa-minus-circle"></i></div>
                                    <input type="number" class="form-control router-quantity" readonly value="1" min="1" title="Router Quantity" />
                                    <div class="router-quantity-selection-plus"><i class="fa fa-plus-circle"></i></div>
                                </div>
                            </div>
                            <div class="sub-section-right-content" style="flex: 10%;">
                                <p class="plan-summary-text plan-summary-router-fee-text"></p>
                            </div>
                            <input type="hidden" class="router-fee" />
                        </div>
                        <div class="sub-section show-plan-fee">
                            <div class="sub-section-left-content">
                                <p class="plan-summary-text">First month plan fee</p>
                            </div>
                            <div class="sub-section-right-content">
                                <p class="plan-summary-text plan-summary-plan-fee-text"></p>
                            </div>
                        </div>
                        <div class="sub-section border-top">
                            <div class="sub-section-left-content">
                                <p class="plan-summary-text font-weight-bold">Total</p>
                            </div>
                            <div class="sub-section-right-content">
                                <p class="plan-summary-text plan-summary-total-price-text font-weight-bold"></p>
                                <input type="hidden" name="total-fee" id="total-fee" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="sub-section-container text-center">
                        <a href="/" id="checkout" class="sc_button color_style_default sc_button_default sc_button_size_normal sc_button_icon_left">
                            <span class="sc_button_text">
                                <span class="sc_button_title">Next</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="plan-speed-tip-modal" tabindex="-1" role="dialog" aria-labelledby="plan-speed-tip-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="plan-speed-tip-modal-title">How fast is it?</h5>
                        <a class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                    <div class="modal-body">
                        Speeds are based on theoretical maximums. Actual speeds will be affected by NZ and overseas networks, your modem and your personal devices, WiFi capability, internal home wiring and other factors.
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="speed-mbps-tip-modal" tabindex="-1" role="dialog" aria-labelledby="speed-mbps-tip-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="plan-speed-tip-modal-title">How fast is it?</h5>
                        <a class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                    <div class="modal-body">
                        Mbps = Megabits per second.
                        Speeds are based on theoretical maximums. Actual speeds will be affected by NZ and overseas networks, your modem and your personal devices, WiFi capability, internal home wiring and other factors.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
   /* $(function(){
        $("input[name='is-byo-router']").change(function(){
            var s = $("input[name='is-byo-router']:checked").val();
            //alert("aa is "+ s);
            if(s=='true'){
                $('#not_buy_router').hide();
            }
            else{
                $('#not_buy_router').show();
            }
        });
    })*/
</script>