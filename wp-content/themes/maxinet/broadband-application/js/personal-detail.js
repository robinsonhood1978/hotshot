jQuery( function ( $ ) {
    var cookieName = 'application_data';
    var fn = {
        setCookie: function ( cname, cvalue, exdays ) {
            var d = new Date();
            d.setTime( d.getTime() + ( exdays * 24 * 60 * 60 * 1000 ) );
            var expires = 'expires=' + d.toGMTString();
            document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
        },
        getCookie: function ( cname ) {
            var name = cname + '=';
            var decodedCookie = decodeURIComponent( document.cookie );
            var ca = decodedCookie.split( ';' );
            for ( var i = 0; i < ca.length; i++ ) {
                var c = ca[i];
                while ( c.charAt(0) == ' ' ) {
                    c = c.substring(1);
                }
                if ( c.indexOf(name) == 0 ) {
                    return c.substring( name.length, c.length );
                }
            }
            return false;
        },
        deleteCookie: function ( cname ) {
            document.cookie = cname + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        },
        addToCart: function ( form_data ) {
            form_data['action'] = "add_plan_to_cart";
            $.ajax({
                url: TRX_ADDONS_STORAGE.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: form_data,
                beforeSend: function () {
                    $( ".loading" ).show();
                },
                complete: function () {
                    $( ".loading" ).hide();
                },
                success: function () {
                    fn.deleteCookie( cookieName );
                    $.redirect( "/checkout/", "", "POST", "_self" );
                }
            });
        },
        scrollTo: function ( id ) {
            var offset = $( ".header-bar" ).outerHeight() + 50;
            $('html, body').animate({
                scrollTop: $( id ).offset().top - offset
            }, 2000);
        }
    };

    $( document ).ready( function () {
        if ( $( "form[name='trx_addons_login_form'] input#login_redirect_to" ).length == 1 ) {
            $( "input#login_redirect_to" ).val( window.location.href );
        }
        var cookies = fn.getCookie( cookieName );
        if ( cookies !== false ) {
            var data = JSON.parse( cookies );
            $.ajax({
                url: TRX_ADDONS_STORAGE.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: { email: data['email'], action: 'check_user_status' },
                beforeSend: function () {
                    $( ".loading" ).show();
                },
                complete: function () {
                    $( ".loading" ).hide();
                },
                success: function ( response ) {
                    if ( response.status === "is_login" ) {
                        //submit directly
                        fn.addToCart( data );
                    } else {
                        for ( var i in data ) {
                            $( "[name='" + i + "']").val( data[i] );
                        }
                    }
                }
            });

        }
        var provider_list = [
            "I don’t currently have broadband",
            "I don’t know who my provider is",
            "Skinny Wireless Broadband",
            "CallPlus",
            "Flip",
            "Slingshot",
            "Spark (Telecom)",
            "Vodafone",
            "Orcon",
            "Bigpipe",
            "Trustpower",
            "MyRepublic",
            "Other",
        ];
        for ( var i in provider_list ) {
            $( "#last-provider" ).append( "<option value='" + provider_list[i] + "'>" + provider_list[i] + "</option>" );
        }
        $( "#personal-detail" ).on( "change", "input[name='connection-date']", function () {
            var is_asap = $( this ).val() === "asap";
            if ( !is_asap ) {
                var html = "<div class='form-group'><label for='prefer-date' class='input-label'>Pick a connection date *</label><input type='text' id='prefer-date' name='prefer-date' class='form-control' placeholder='dd/mm/yyyy' /></div>";
                $( ".prefer-date-wrapper" ).append( html );
                $( "#prefer-date" ).datepicker({
                    autoclose: true,
                    format: "dd/mm/yyyy",
                    todayHighlight: true,
                    startDate: "today",
                    maxViewMode: 0,
                });
                $( 'form#personal-detail' ).bootstrapValidator( 'addField', "prefer-date" );
            } else {
                $( 'form#personal-detail' ).bootstrapValidator( 'removeField', "prefer-date" );
                $( ".prefer-date-wrapper" ).empty();
            }
        })
            .on( "change", "#last-provider", function () {
                var value = $( this ).val();
                if ( value.toLowerCase() === "other" ) {
                    $( ".other-provider-wrapper" ).append( "<label for='other-provider' class='input-label'>Enter the last provider</label><input type='text' class='form-control' id='other-provider' name='other-provider' />" );
                } else {
                    $( ".other-provider-wrapper" ).empty();
                    if ( value === "I don’t currently have broadband" ) {

                    } else {
                        $( ".other-provider-wrapper" ).append( "<label for='other-provider' class='input-label'>Your Account Number at the current provider</label><input type='text' class='form-control' id='current-provider-account' name='current-provider-account' />" );
                    }
                }
            })
            .on( "change", "input[name='has-landline']", function () {
                var value = $( this ).val();
                if ( value === "true" ) {
                    $( ".landline-tips-wrapper" ).append( "<label class='input-label'>Heads up, you may need to contact your landline service provider to cancel the service.</label>" );
                } else {
                    $( ".landline-tips-wrapper" ).empty();
                }
            })
            .on( "change", "input[name='has-delivery-address']", function () {
                var value = $( this ).val();
                if ( value === "true" ) {
                    $( ".delivery-detail-wrapper" ).slideDown().css( "display", "flex" );
                    $( ".delivery-detail-wrapper input" ).prop( "disabled", false );
                } else {
                    $( ".delivery-detail-wrapper" ).slideUp();
                    $( ".delivery-detail-wrapper input" ).val( "" ).attr( "disabled", "disabled" );
                }
            })
            .on( "click", "#submit", function (e) {
                e.preventDefault();
                var email = $( "#email" ).val();
                var formdata = $( "form#personal-detail" ).serializeArray();
                $.merge( formdata, application_data );
                var data = {};
                $( formdata ).each( function( index, obj ) {
                    if ( obj.name.includes( "[]" ) ) {
                        var string_arr = obj.name.split( "[]" );
                        if ( !Array.isArray( data[ string_arr[0] ] ) ) {
                            data[string_arr[0]] = [];
                        }
                        data[ string_arr[0] ].push( obj.value );
                    } else {
                        data[obj.name] = obj.value;
                    }
                });
                $( "form#personal-detail").data( "bootstrapValidator" ).validate();
                var invalid_fields = $( "form#personal-detail").data( "bootstrapValidator" ).getInvalidFields();
                if ( invalid_fields.length > 0 ) {
                    fn.scrollTo( invalid_fields[0] );
                } else {
                    $.ajax({
                        url: TRX_ADDONS_STORAGE.ajax_url,
                        type: 'POST',
                        dataType: 'json',
                        data: {email: email, action: 'check_user_status'},
                        beforeSend: function () {
                            $( ".loading" ).show();
                        },
                        complete: function () {
                            $(".loading").hide();
                        },
                        success: function (response) {
                            if ( response.status === "is_exist" ) {
                                //save to cookies
                                fn.setCookie( cookieName, JSON.stringify( data ), 30 );
                                $( "a.trx_addons_login_link" ).click();
                            } else if ( response.status === "is_login" ) {
                                //submit directly
                                fn.addToCart( data );
                            } else {
                                //create account and submit
                                data.action = "app_create_user";
                                $.ajax({
                                    url: TRX_ADDONS_STORAGE.ajax_url,
                                    type: 'POST',
                                    dataType: 'json',
                                    data: data,
                                    beforeSend: function () {
                                        $( ".loading" ).show();
                                    },
                                    complete: function () {
                                        $( ".loading" ).hide();
                                    },
                                    success: function () {
                                        fn.addToCart( data );
                                    }
                                });
                            }
                        }
                    });
                }
            });
        $( "form#personal-detail" ).bootstrapValidator({
            excluded: [":disabled"],
            feedbackIcons: {
                valid: "",
                invalid: "",
                validating: ""
            },
            live: "enabled",
            message: "This value is not valid",
            submitButtons: "button[id='submit']",
            fields: {
                "not-empty": {
                    selector: '.not-empty',
                    trigger: 'change keyup',
                    validators: {
                        notEmpty: {
                            message: 'This field cannot be blank'
                        },
                    }
                },
                "email": {
                    trigger: 'change keyup',
                    validators: {
                        notEmpty: {
                            message: 'This field cannot be blank'
                        },
                        emailAddress: {
                            message: 'Please enter a valid email address'
                        }
                    }
                },
                "prefer-date": {
                    trigger: 'change keyup',
                    validators: {
                        notEmpty: {
                            message: 'The date is required and cannot be empty'
                        },
                        date: {
                            format: 'DD/MM/YYYY'
                        }
                    }
                },
                "contact-number": {
                    trigger: 'change keyup',
                    validators: {
                        notEmpty: {
                            message: 'Please enter your contact number'
                        },
                        callback: {
                            message: 'Please enter valid contact number',
                            callback: function (value) {
                                return  (/^[0-9 ]*$/.test(value) );
                            }
                        }
                    }
                },
                "is-accept-terms": {
                    validators: {
                        choice: {
                            min: 1,
                            max: 1,
                            message: 'Please check this if you want to proceed'
                        }
                    }
                }
            }
        })
    });

});