// var autocomplete;
// var componentForm = {
//     street_number: 'short_name',
//     route: 'long_name',
//     locality: 'long_name',
//     administrative_area_level_1: 'long_name',
//     country: 'long_name',
//     postal_code: 'short_name'
// };
// function autoComplete() {
//     jQuery( ".autocomplete-address" ).each(function ( index, value ) {
//         autocomplete = new google.maps.places.Autocomplete( value, {types: ['geocode']} );
//         autocomplete.setComponentRestrictions({'country': ['nz']});
//         autocomplete.addListener('place_changed', function () {
//             var info = {};
//             var place = autocomplete.getPlace();console.log(place);
//             for (var i = 0; i < place.address_components.length; i++) {
//                 var addressType = place.address_components[i].types[0];
//                 if (componentForm[addressType]) {
//                     info[addressType] = place.address_components[i][componentForm[addressType]];
//                 }
//             }
//             //invoke in application
//             if ( jQuery( "#lat" ).length > 0 ) jQuery( "#lat" ).val( place.geometry.location.lat() ).change();
//             if ( jQuery( "#lng" ).length > 0 ) jQuery( "#lng" ).val( place.geometry.location.lng() ).change();
//             if ( jQuery( "#is-auckland" ).length > 0 ) jQuery( "#is-auckland" ).val( info.administrative_area_level_1.toLowerCase() ).change();
//             if ( jQuery( "#address" ).length > 0 ) jQuery( "#address" ).val( place.formatted_address ).change();
//             if ( jQuery( "#street" ).length > 0 ) jQuery( "#street" ).val( info["street_number"] + " " + info["route"] ).change();
//             if ( jQuery( "#suburb" ).length > 0 ) jQuery( "#suburb" ).val( info["locality"] ).change();
//             if ( jQuery( "#city" ).length > 0 ) jQuery( "#city" ).val( info["administrative_area_level_1"] === null ? info["locality"] : info["administrative_area_level_1"]  ).change();
//             if ( jQuery( "#postcode" ).length > 0 ) jQuery( "#postcode" ).val( info["postal_code"] ).change();
//
//             //invoke in personal detail
//             if ( jQuery( "#delivery-address" ).length > 0 ) jQuery( "#delivery-address" ).val( info["street_number"] + " " + info["route"] ).change();
//             if ( jQuery( "#delivery-suburb" ).length > 0 ) jQuery( "#delivery-suburb" ).val( info["locality"] ).change();
//             if ( jQuery( "#delivery-city" ).length > 0 ) jQuery( "#delivery-city" ).val( info["administrative_area_level_1"] === null || info["administrative_area_level_1"] === "" ? info["locality"] : info["administrative_area_level_1"]  ).change();
//             if ( jQuery( "#delivery-postcode" ).length > 0 ) jQuery( "#delivery-postcode" ).val( info["postal_code"] ).change();
//         });
//     });
// }

(function() {
    var widget, initAddressFinder = function() {
        jQuery( ".autocomplete-address" ).each(function ( index, value ) {
            widget = new AddressFinder.Widget(
                value,
                'LQERUH6GDCA34P8VJXMB',
                'NZ', {
                    "address_params": {
                        "post_box": "0"
                    }
                }
            );
            widget.on('address:select', function(fullAddress, metaData) {

                var suburb = metaData.selected_suburb || metaData.selected_city;
                //invoke in application
                if ( jQuery( "#is-auckland" ).length > 0 ) jQuery( "#is-auckland" ).val( metaData.ta.toLowerCase() ).change();
                if ( jQuery( "#lat" ).length > 0 ) jQuery( "#lat" ).val( metaData.y ).change();
                if ( jQuery( "#lng" ).length > 0 ) jQuery( "#lng" ).val( metaData.x ).change();
                if ( jQuery( "#address" ).length > 0 ) jQuery( '#address' ).val( fullAddress ).change();
                if ( jQuery( "#street" ).length > 0 ) jQuery( "#street" ).val( metaData.address_line_1 ).change();
                if ( jQuery( "#suburb" ).length > 0 ) jQuery( "#suburb" ).val( suburb ).change();
                if ( jQuery( "#city" ).length > 0 ) jQuery( "#city" ).val( metaData.selected_city ).change();
                if ( jQuery( "#postcode" ).length > 0 ) jQuery( "#postcode" ).val( metaData.postcode ).change();
                //invoke in personal detail
                if ( jQuery( "#delivery-address" ).length > 0 ) jQuery( "#delivery-address" ).val(  metaData.address_line_1 ).change();
                if ( jQuery( "#delivery-suburb" ).length > 0 ) jQuery( "#delivery-suburb" ).val( suburb ).change();
                if ( jQuery( "#delivery-city" ).length > 0 ) jQuery( "#delivery-city" ).val( metaData.selected_city ).change();
                if ( jQuery( "#delivery-postcode" ).length > 0 ) jQuery( "#delivery-postcode" ).val( metaData.postcode ).change();
            });
        });





    };

    function downloadAddressFinder() {
        var script = document.createElement('script');
        script.src = 'https://api.addressfinder.io/assets/v3/widget.js';
        script.async = true;
        script.onload = initAddressFinder;
        document.body.appendChild(script);
    };

    document.addEventListener('DOMContentLoaded', downloadAddressFinder);
})();