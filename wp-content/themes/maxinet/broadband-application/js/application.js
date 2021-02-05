jQuery( function ( $ ) {
    var fn = {
        scrollTo: function ( target ) {
            if( target.length ) {
                event.preventDefault();
                $( "html, body" ).stop().animate({
                    scrollTop: target.offset().top - 168
                }, 1000);
            }
        }
    };

    $( document ).ready( function () {
        if ( $( "form[name='trx_addons_login_form'] input#login_redirect_to" ).length ==1 ) {
            $( "input#login_redirect_to" ).val( window.location.href );
        }
        $( "#application" ).on( "change", "#address", function () {
            var lat = $( "#lat" ).val();
            var lng = $( "#lng" ).val();
			//var lat = -36.8721637667;
			//var lng = 174.9077272833;
            console.log("lat:"+lat+"-lng:"+lng);
            if ( lat !== "" && lng !== "" ) {
                $.ajax({
                    url: "/info.php?lat="+lat+"&lng="+lng,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function (request) {
                        $(this).attr("disabled", "disabled");
                    },
                    success: function (response) {
                        var is_available = "false";
                        for (var i in response.results) {
                            var result = response.results[i];
                            if (result["technology"] === "fibre") {
                                if (result["availability"] === "available") {
                                    is_available = "true";
                                }
                            }
                        }
                        $(this).attr("disabled", false);
                        $("#ufb-available").val(is_available).change();
                    }
                });
            }
        })
            .on( "change", "#is-auckland", function () {
                var value = $( this ).val();
                if ( value !== "auckland" ) {
                    //$( ".is-country-false" ).addClass( "hidden" );
                    //$( ".is-country-false input" ).attr( "disabled", "disabled" );
                    $( ".is-country-false" ).removeClass( "hidden" );
                    $( ".is-country-false input" ).attr( "disabled", false );
                } else {
                    $( ".is-country-false" ).removeClass( "hidden" );
                    $( ".is-country-false input" ).attr( "disabled", false );
                }
            })
            .on( "change", "#ufb-available", function () {
                var is_available = ( $( "#ufb-available" ).val() === "true" );
                var success_text = "Success! It looks like you should be able to get HotShot Broadband Service at your place.";
                var failure_text = "Sorry! It looks like UFB is not ready at your place";
                $( ".address-field" ).hide();
                $( ".address-search-messages" ).slideDown();
                $( ".address-search-messages-show-address, .plan-summary-address-text" ).empty().html( $( "#address" ).val() );
                if ( is_available ) {
                    $( ".address-search-icon" ).html( "<i class='fa fa-4x fa-check-circle-o' style='color: #27ae60;'></i>" );
                    $( ".address-search-messages-text" ).html( success_text );
                    $( ".row.is-available" ).slideDown().css( "display", "flex" );
                    $( "input[name='plan']:not([disabled='disabled']):first" ).attr( "checked", "checked" ).change();
                    $( "input[name='is-byo-router'][value='false']").prop( "checked", true ).change();
                    var timeout;
                    var stickyDivHeight = $( ".sticky-div" ).outerHeight();
                    var hiddenDivHeight = $( ".is-router" ).outerHeight();
                    var stick = $( ".stick" );
                    var stickyDivTop = $( ".sticky-div" ).offset().top;
                    var scrollTop = 0;
                    var offset = $( ".header-bar" ).outerHeight() + 10;
                    $( window ).scroll( function() {
                        clearTimeout( timeout );
                        timeout = setTimeout( function () {
                            var totalHeight = $( "input[name='is-byo-router']:checked" ).val() === "true" ? stickyDivHeight + hiddenDivHeight : stickyDivHeight;
                            scrollTop = $( window ).scrollTop() - $( ".address-search-messages" ).outerHeight();
                            if ( ( scrollTop + offset ) >= stickyDivTop && ( scrollTop - stickyDivTop ) + stick.outerHeight() <= totalHeight ) {
                                stick.css( "margin-top", scrollTop - stickyDivTop + offset + "px" );
                            } else if ( ( scrollTop - stickyDivTop ) + stick.outerHeight() >= totalHeight ) {
                                stick.css( "margin-top", totalHeight - stick.outerHeight() + "px" );
                            } else {
                                stick.css( "margin-top", 0 );
                            }
                        }, 0 );
                    });
                } else {
                    $( ".address-search-icon" ).html( "<i class='fa fa-4x fa-times-circle-o' style='color: #e71a1a;'></i>" );
                    $( ".address-search-messages-text" ).html( failure_text );
                    $( "input[name='plan']" ).each(function () { $( this ).prop( 'checked', false ); }).change();
                }
            })
            .on( "click", ".address-search-messages-clear-address, .summary-clear-address", function (e) {
                e.preventDefault();
                $( ".address-field" ).slideDown();
                fn.scrollTo( $( ".address-search-title" ) );
                $( ".row.is-available" ).hide();
                $( ".address-search-messages" ).hide();
                $( "#ufb-available, #is-auckland, #lat, #lng" ).val( "" );
                $( ".address-search-messages-show-address, .address-search-icon, .address-search-messages-text" ).empty();
                $( "#address" ).attr({"disabled": false}).val( "" );
                $( "input[name='is-byo-router'][value='false']").prop( "checked", true ).change();
            })
            .on( "change", "input[name='is-byo-router']", function () {
                var is_byo = $( this ).val();
                if ( is_byo === "true" ) {
                    $( ".is-router, .show-router-fee" ).slideDown();
                    $( ".show-router-fee" ).css( "display","flex" );
                    $('#not_buy_router').hide();
                    //$( "input[name='router']:first" ).attr( "checked", "checked").change();
                } else {
                    $( ".is-router, .show-router-fee" ).slideUp();
                    $( ".router-selection" ).each(function () { $(this).prop('checked', false); }).change();
                    $('#not_buy_router').show();
                    //$( "#router-fee" ).val( "" ).change();
                }
            })
            //single line item
            .on( "change", "input[name='plan']", function () {
                var plan_fee = $( this ).data( "plan_fee" );
                $( "#plan-fee" ).val( plan_fee ).change();
                $( "#connection-fee" ).val( $( this ).data( "signup_fee" ) ).change();
                $( ".plan-summary-speed-text" ).empty().html( $( this ).data( "label" ) );
                $( ".plan-summary-plan-price-text, .plan-summary-plan-fee-text" ).empty().html( "$"  + parseFloat( plan_fee.toString() ).toFixed(2) );
                $( ".plan-summary-connection-fee-text" ).empty().html( "$"  + parseFloat( $( "#connection-fee" ).val().toString() ).toFixed(2) );
            })
            //single line item
            .on( "change", ".router-selection", function () {
                var router_fee = $( this ).data( "router_fee" );
                if ( $( this ).prop( "checked" ) ) {
                    var template = $( "#show-router-fee" );
                    var line_item = template
                        .clone()
                        .removeClass( "hidden" )
                        .removeAttr( 'id' )
                        .addClass( "router-item-" + $( this ).val() )
                        .insertBefore( template );
                    line_item.find( ".plan-summary-router-title-text" ).html( $( this ).data( "router_name" ) );
                    line_item.find( ".plan-summary-router-fee-text" ).html( "$" + parseFloat( router_fee.toString() ).toFixed(2) );
                    line_item.find( "input.router-quantity" ).attr({
                        "id": "router-quantity-" + $( this ).val(),
                        "name": "router-quantity-" + $( this ).val(),
                        "data-router_unit_price": router_fee,
                    });
                    line_item.find( ".router-fee" ).attr({
                        "id": "router-fee-" + $( this ).val(),
                        "value": router_fee
                    });
                    $( "#router-fee-" + $( this ).val() ).change();
                } else {
                    var router_id = $( this ).val();
                    $( "#router-fee-" + $( this ).val() ).val( 0 ).change();
                    $( ".router-item-" + router_id ).remove();
                }
            })
            //total pay now
            .on( "change", "#connection-fee, .router-fee, #plan-fee", function () {
                var connection_fee = $( "#connection-fee" ).val();
                var plan_fee = $( "#plan-fee" ).val();
                var router_items = $( "[id*='router-fee-']" );
                var router_fee = 0;
                router_items.each( function ( index, value ) {
                    router_fee += parseFloat( $( value ).val().toString() );
                });
                var total_fee = ( parseFloat( router_fee === "" ? 0 : router_fee.toString() ) + parseFloat( connection_fee === "" ? 0 : connection_fee.toString() ) + parseFloat( plan_fee === "" ? 0 : plan_fee.toString() ) ).toFixed(2);

                $( "#total-fee" ).val( total_fee );
                $( ".plan-summary-total-price-text" ).empty().html( "$" + total_fee );
            })
            .on( "click", ".router-quantity-selection-minus", function () {
                var quantity_selector = $( this ).siblings( "input" );
                quantity_selector.each( function ( index, value ) {
                    var id_array = $( value ).attr( "id" ).toString().split( "-" );
                    var router_id = id_array[id_array.length - 1];
                    var unit_price = parseFloat( $( value ).data( "router_unit_price" ).toString() );
                    var current_quantity = parseFloat( $( value ).val().toString() );
                    if ( current_quantity > 1 ) {
                        $( value ).val( current_quantity - 1 );
                        $( "#router-fee-" + router_id ).val( unit_price * ( current_quantity - 1 ) ).change();
                        $( ".router-item-" + router_id + " .plan-summary-router-fee-text").empty().html( "$" + ( unit_price * ( current_quantity - 1 ) ).toFixed(2) )
                    }
                });
            })
            .on( "click", ".router-quantity-selection-plus", function () {
                var quantity_selector = $( this ).siblings( "input" );
                quantity_selector.each( function ( index, value ) {
                    var id_array = $( value ).attr( "id" ).toString().split( "-" );
                    var router_id = id_array[id_array.length - 1];
                    var unit_price = parseFloat( $( value ).data( "router_unit_price" ).toString() );
                    var current_quantity = parseFloat( $( value ).val().toString() );
                    $( value ).val( current_quantity + 1 );
                    $( "#router-fee-" + router_id ).val( unit_price * ( current_quantity + 1 ) ).change();
                    $( ".router-item-" + router_id + " .plan-summary-router-fee-text").empty().html( "$" + ( unit_price * ( current_quantity + 1 ) ).toFixed(2) )
                });
            })
            .on( "click", "#checkout", function (e) {
                e.preventDefault();
                $( "input[name='ufb-avalibale'], input[name='is-byo-router'], input[name='plan-fee'], input[name='connection-fee'], input[name='total-fee']" ).prop( "disabled", true );
                var data = $( "form#application" ).serializeArray();
                $.redirect( "/broadband-application/personal-details/", data, "POST", "_self" );
                console.log(data);
            });

    });
});