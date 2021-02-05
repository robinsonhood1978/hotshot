jQuery( function ( $ ) {

    var fn = {
        save_metadata: function ( meta ) {
            meta = fn.remove_unchange_metadata( meta );
            meta.action = "save_meta";
            $.ajax({
                url: TRX_ADDONS_STORAGE.ajax_url,
                type: "POST",
                dataType: "json",
                data: meta,
                beforeSend: function () {
                    $( ".loading" ).show();
                },
                complete: function () {
                    $( ".loading" ).hide();
                },
                success: function ( response ) {
                    if ( response.success ) {
                        location.reload();
                    }
                }
            });
        },
        remove_unchange_metadata: function ( meta ) {
            var result = {};
            for ( var i in meta ) {
                if ( meta.hasOwnProperty(i) && meta[i] !== "" ) {
                    result[i] = meta[i];
                }
            }
            return result;
        },
    };

    $( document ).ready( function () {
        $( ".profile-wrapper" ).on( "click", "#edit-personal-detail, #cancel-personal-detail", function (e) {
            e.preventDefault();
            $( ".customer_details .block-detail-value-field, .customer_details .input-field, #edit-personal-detail, #cancel-personal-detail, #save-personal-detail" ).toggleClass( "hidden" );
        })
            .on( "click", "#save-personal-detail", function (e) {
                e.preventDefault();
                var meta = {
                    first_name: $( "#first-name" ).val(),
                    last_name: $( "#last-name" ).val(),
                    mobile: $( "#phone-number" ).val(),
                    password: $( "#new-password" ).val(),
                };
                fn.save_metadata( meta );
            })
            .on( "click", "#edit-billing-detail, #cancel-billing-detail", function (e) {
                e.preventDefault();
                $( ".billing_details .block-detail-value-field, .billing_details .input-field, #edit-billing-detail, #cancel-billing-detail, #save-billing-detail" ).toggleClass( "hidden" );
            })
            .on( "click", "#save-billing-detail", function (e) {
                e.preventDefault();
                var meta = {
                    billing_first_name: $( "#billing-first-name" ).val(),
                    billing_last_name: $( "#billing-last-name" ).val(),
                    billing_email: $( "#billing-email" ).val(),
                    billing_phone: $( "#billing-phone" ).val(),
                    billing_address_1: $( "#street" ).val(),
                    billing_address_2: $( "#suburb" ).val(),
                    billing_city: $( "#city" ).val(),
                    billing_postcode: $( "#postcode" ).val(),
                };
                fn.save_metadata( meta );
            })
            .on( "click", "#edit-shipping-detail, #cancel-shipping-detail", function (e) {
                e.preventDefault();
                $( ".shipping_details .block-detail-value-field, .shipping_details .input-field, #edit-shipping-detail, #cancel-shipping-detail, #save-shipping-detail" ).toggleClass( "hidden" );
            })
            .on( "click", "#save-shipping-detail", function (e) {
                e.preventDefault();
                var meta = {
                    shipping_first_name: $( "#shipping-first-name" ).val(),
                    shipping_company: $( "#shipping-company" ).val(),
                    shipping_address_1: $( "#delivery-address" ).val(),
                    shipping_address_2: $( "#delivery-suburb" ).val(),
                    shipping_city: $( "#delivery-city" ).val(),
                    shipping_postcode: $( "#delivery-postcode" ).val(),
                };
                fn.save_metadata( meta );
            })
            .on( "hidden.bs.modal", "[id*='switch-subscription-modal-']", function () {
                var subscription_id = $( this ).data( "subscription_id" );
                var t = "new-subscription-plan-" + subscription_id;
                $( "select#subscription-action-" + subscription_id ).val( "action" );
                $( "input[name='" + t + "']").prop( "checked", false );
                $( "button[data-target='" + t + "']" ).prop( "disabled", true );
            })
            .on( "change", ".subscription-action", function () {
                var action = $( this ).val();
                var plan_id = $( this ).data( "subscription_id" );
                switch ( action ) {
                    case "move":
                        $.redirect( "/broadband-application", {"is_move_house": "true", "origin_subscription_id": $( this ).data( "subscription_id" )}, "POST", "_self" );
                        break;
                    case "cancel":
                        DayPilot.Modal.confirm("We're sorry to see you go. Please do tell us if we could do better to make you stay. <br/>" +
                            "Do you still want to cancel your Hotshot Unlimited Fibre? Please note it can't be changed once cancelled. ", { okText: "Cancel service", cancelText: "Stay connected" })
                            .then(function(args) {
                                if (args.result) {
                                    $.ajax({
                                        type: "post",
                                        dataType: "json",
                                        url: TRX_ADDONS_STORAGE.ajax_url,
                                        data: {action: "cancel_subscription", plan_id: plan_id},
                                        beforeSend: function () {
                                            $( ".loading" ).show();
                                        },
                                        success: function ( response ) {
                                            if ( response.success ) {
                                                location.reload();
                                            } else {
                                                alert( "Please refresh the page" );
                                            }
                                        }
                                    });
                                }
                                else {
                                    DayPilot.Modal.alert("You canceled the cancellation operation.");
                                    $('#subscription-action-'+plan_id).val("action");
                                }
                            });
                        break;
                    case "switch":
                        $( ".switch-plan-" + plan_id ).click();
                        break;
                    default:
                        break;
                }
            })
            .on( "change", "input[name*='new-subscription-plan-']", function () {
                var id = $( this ).data( "old_plan" );
                var t = "new-subscription-plan-" + id;
                $( "button[data-target='" + t + "']" ).prop( "disabled", false );
            })
            .on( "click", "button[data-target*='new-subscription-plan-']", function () {
                var target = $( this ).data( "target" ).split( "-" );
                var id = target.pop();
                var new_plan = $( "input[name='new-subscription-plan-" + id + "']:checked" ).val();

                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: TRX_ADDONS_STORAGE.ajax_url,
                    data: {action: "switch_subscription", old_subscription_id: id, new_plan_id: new_plan},
                    beforeSend: function () {
                        $( ".loading" ).show();
                    },
                    success: function ( response ) {
                        if ( response.success ) {
                            window.location.href = '/checkout/';
                        } else {
                            alert( "Please refresh the page" );
                        }
                    }
                });
            })
    });

});