/**
 * Child Theme Main Javascript File
 */

(function( $ ) {
	
	"use strict";
	
		$("input.wc-grouped-product-add-to-cart-checkbox").click(function() {
			$("input.wc-grouped-product-add-to-cart-checkbox").prop("checked", false);
			this.checked = true;
		});
	

} )(jQuery);