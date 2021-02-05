jQuery( function( $ ) {
    $( document ).ready( function () {
        $( '.wpcf7-form' ).on( 'change', '#enquiry-subject', function(){
            $( '.dynamic-1, .dynamic-2' ).empty();
            switch( $( this ).val() ) {
                case 'Problems with my broadband':
                    $( '.dynamic-1').html('<div class="columns_wrap"><div class="column-1_1"><label>Choose from these options</label><br><span class="wpcf7-form-control-wrap broadband-problems"><div class="select_container"><select name="broadband-problems" class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required fill_inited" id="broadband-problems" aria-required="true" aria-invalid="false"><option value="">---</option><option value="No connection">No connection</option><option value="Slow connection">Slow connection</option><option value="Regular dropouts">Regular dropouts</option></select></div></span></div></div>');
                    break;
            }
        });
    });
});