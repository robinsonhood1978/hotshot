<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 4/06/19
 * Time: 2:13 PM
 */

if( !defined( 'ABSPATH') ) exit();

$post = $_POST;

wp_enqueue_style( 'personal-detail-css', '/wp-content/themes/maxinet/broadband-application/css/personal-detail.css' );
wp_enqueue_script( 'personal-detail-js', '/wp-content/themes/maxinet/broadband-application/js/personal-detail.js', array( 'jquery' ), '', true );
wp_enqueue_style( 'bootstrap-datetimepicker-css', '/wp-content/themes/maxinet/broadband-application/css/bootstrap-datepicker3.min.css' );
wp_enqueue_script( 'bootstrap-datetimepicker-js', '/wp-content/themes/maxinet/broadband-application/js/bootstrap-datepicker.js', array(), '', true );
wp_enqueue_script( 'bootstrap-validator-js', '/wp-content/themes/maxinet/broadband-application/js/bootstrapValidator.min.js', array( 'jquery' ), '', true );

wp_enqueue_script( 'autocomplete', '/wp-content/themes/maxinet/broadband-application/js/google-place-autocomplete.js', array(), '', true );

$is_login = is_user_logged_in();

if ( $is_login ) {
    $user_id = get_current_user_id();
    $user_info = get_userdata( $user_id );
}

?>
<script>
    var application_data = <?php echo json_encode( $post ) ?>;
</script>
<div class="form-container">
    <form id="personal-detail">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h5 class="heading-title">About You</h5>
                </div>
                <p class="heading-description">Pop in your details</p>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first-name" class="input-label">First Name *</label>
                            <input type="text" id="first-name" name="first-name" class="form-control not-empty" placeholder="First Name" value="<?php echo $is_login ? $user_info->first_name : '' ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last-name" class="input-label">Last Name *</label>
                            <input type="text" id="last-name" name="last-name" class="form-control not-empty" placeholder="Last Name" value="<?php echo $is_login ? $user_info->last_name : '' ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="input-label">Email *</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?php echo $is_login ? $user_info->user_email : '' ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact-number" class="input-label">Contact Number *</label>
                            <input type="tel" id="contact-number" name="contact-number" class="form-control" placeholder="Contact Number" value="<?php echo $is_login ? get_user_meta( $user_id, 'billing_phone', true ) : '' ?>" />
                            <label for="contact-number" class="input-label">This number will be used to contact you if we have trouble delivering your order.</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h5 class="heading-title">Preferred connection date</h5>
                </div>
                <p class="heading-description">In most cases (some will take longer, for example a fibre install) it will take up to 5 to 7 working days to get you connected with HotShot Unlimited Broadband. We can't provide an exact date as the process involves third parties, but we'll do our best to be in touch as soon as we know when your connection will be switched on. Most connections won't even need a technician to come around, but in some cases one may need to pay a visit to make sure you're all set.</p>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 d-flex flex-column flex-md-row">
                        <div class="mr-5">
                            <input type="radio" id="connection-date-asap" name="connection-date" class="form-control" value="asap" checked="checked" />
                            <label for="connection-date-asap" class="radio-label">ASAP</label>
                        </div>
                        <div class="mr-5">
                            <input type="radio" id="connection-date-other" name="connection-date" class="form-control" value="other" />
                            <label for="connection-date-other" class="radio-label">Set a preferred connection date</label>
                        </div>
                    </div>
                    <div class="col-md-6 prefer-date-wrapper"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h5 class="heading-title">About your current connection</h5>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last-provider" class="input-label">Who was your last provider?</label>
                            <select class="form-control" id="last-provider" name="last-provider"></select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group other-provider-wrapper"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="input-label">Do you have a landline with your current provider?</label>
                    </div>
                    <div class="col-md-12 d-flex flex-column flex-md-row">
                        <div class="mr-5">
                            <input type="radio" id="has-landline-yes" name="has-landline" class="form-control" value="true" />
                            <label for="has-landline-yes" class="radio-label">Yes</label>
                        </div>
                        <div class="mr-5">
                            <input type="radio" id="has-landline-no" name="has-landline" class="form-control" value="false"  checked="checked" />
                            <label for="has-landline-no" class="radio-label">No</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="landline-tips-wrapper"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h5 class="heading-title">Router delivery details</h5>
                </div>
                <p class="heading-description">We want to make sure you get your whizzy router sent to the right place.</p>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        <label class="input-label">Where do you want it sent to?</label>
                    </div>
                    <div class="col-md-12 d-flex flex-column flex-md-row">
                        <div class="mr-5">
                            <input type="radio" id="has-delivery-address-no" name="has-delivery-address" class="form-control" value="false" checked="checked" />
                            <label for="has-delivery-address-no" class="radio-label">Same as connection address</label>
                        </div>
                        <div class="mr-5">
                            <input type="radio" id="has-delivery-address-yes" name="has-delivery-address" class="form-control" value="true" />
                            <label for="has-delivery-address-yes" class="radio-label">Different delivery address</label>
                        </div>
                    </div>
                </div>
                <div class="row delivery-detail-wrapper">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="delivery-name" class="input-label">Name *</label>
                                    <input type="text" class="form-control not-empty" id="delivery-name" name="delivery-name" disabled="disabled" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="delivery-company" class="input-label">Company</label>
                                    <input type="text" class="form-control" id="delivery-company" name="delivery-company" disabled="disabled" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="delivery-address" class="input-label">Address *</label>
                                    <input type="text" class="form-control autocomplete-address not-empty" id="delivery-address" name="delivery-address" autocomplete="off" disabled="disabled" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="delivery-suburb" class="input-label">Suburb *</label>
                                    <input type="text" class="form-control not-empty" id="delivery-suburb" name="delivery-suburb" disabled="disabled" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="delivery-city" class="input-label">City *</label>
                                    <input type="text" class="form-control not-empty" id="delivery-city" name="delivery-city" disabled="disabled" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="delivery-postcode" class="input-label">Postcode *</label>
                                    <input type="text" class="form-control not-empty" id="delivery-postcode" name="delivery-postcode" disabled="disabled" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="delivery-instruction" class="input-label">Delivery instructions are a best effort endeavour and the responsibility is on the courier company to follow through. Courier deliveries take place Monday to Friday and you'll need to sign for the package.</label>
                            <textarea class="form-control" id="delivery-instruction" name="delivery-instruction" placeholder="Delivery instructions (optional) e.g. We're the second house on the left down the long driveway"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h5 class="heading-title">Terms & Conditions</h5>
                </div>
            </div>
            <div class="col-md-9">
                <div class="mb-3">
                    <input type="checkbox" class="form-control" id="is-receive-update" name="is-receive-update" checked="checked" />
                    <label for="is-receive-update" class="checkbox-label">I want to receive updates & offers from HotShot</label>
                </div>
                <div class="mb-3">
                    <div class="form-group">
                        <input type="checkbox" class="form-control" id="is-accept-terms" name="is-accept-terms" />
                        <label for="is-accept-terms" class="checkbox-label">I have read and accept the <a href="/terms-conditions/">HotShot terms and conditions</a></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-9 text-center">
                <button id="submit" class="sc_button color_style_default sc_button_default sc_button_size_normal sc_button_icon_left px-5">
                    <span class="sc_button_text">
                        <span class="sc_button_title">Next</span>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
<div class="loading">
    <img src="/wp-content/themes/maxinet/broadband-application/loading.gif"/>
</div>
<script>
    /*$(function(){
        $("#last-provider").change(function(){
            var s = $("#last-provider").val();
        });
    })*/
</script>
