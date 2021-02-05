<?php
/**
 * Grouped product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/grouped.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $post;

do_action( 'woocommerce_before_add_to_cart_form' );

if( get_the_id() == 25452 ) {
	$grouped_id = get_the_id();
	//Switch Plan
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
	

}

 ?>

<form class="cart grouped_form" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
	<table cellspacing="0" class="woocommerce-grouped-product-list group_table">
		<tbody>
			<?php
			$quantites_required      = false;
			$previous_post           = $post;
			$grouped_product_columns = apply_filters( 'woocommerce_grouped_product_columns', array(
				'quantity',
				'label',
				'price',
			), $product );

			foreach ( $grouped_products as $grouped_product_child ) {
				$post_object        = get_post( $grouped_product_child->get_id() );
				$quantites_required = $quantites_required || ( $grouped_product_child->is_purchasable() && ! $grouped_product_child->has_options() );
				$post               = $post_object; // WPCS: override ok.
				setup_postdata( $post );

				echo '<tr id="product-' . esc_attr( $grouped_product_child->get_id() ) . '" class="woocommerce-grouped-product-list-item ' . esc_attr( implode( ' ', wc_get_product_class( '', $grouped_product_child ) ) ) . '">';

				// Output columns for each product.
				foreach ( $grouped_product_columns as $column_id ) {
					do_action( 'woocommerce_grouped_product_list_before_' . $column_id, $grouped_product_child );

					switch ( $column_id ) {
						case 'quantity':
							ob_start();

							if ( ! $grouped_product_child->is_purchasable() || $grouped_product_child->has_options() || ! $grouped_product_child->is_in_stock() ) {
								woocommerce_template_loop_add_to_cart();
							} elseif ( $grouped_product_child->is_sold_individually() ) {
								echo '<input type="checkbox" name="' . esc_attr( 'quantity[' . $grouped_product_child->get_id() . ']' ) . '" value="1" class="wc-grouped-product-add-to-cart-checkbox" />';
							} else {
								do_action( 'woocommerce_before_add_to_cart_quantity' );

								woocommerce_quantity_input( array(
									'input_name'  => 'quantity[' . $grouped_product_child->get_id() . ']',
									'input_value' => isset( $_POST['quantity'][ $grouped_product_child->get_id() ] ) ? wc_stock_amount( wc_clean( wp_unslash( $_POST['quantity'][ $grouped_product_child->get_id() ] ) ) ) : 0, // WPCS: CSRF ok, input var okay, sanitization ok.
									'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 0, $grouped_product_child ),
									'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $grouped_product_child->get_max_purchase_quantity(), $grouped_product_child ),
								) );

								do_action( 'woocommerce_after_add_to_cart_quantity' );
							}

							$value = ob_get_clean();
							break;
						case 'label':
							$value  = '<label for="product-' . esc_attr( $grouped_product_child->get_id() ) . '">';
							$value .= $grouped_product_child->is_visible() ? '<a href="' . esc_url( apply_filters( 'woocommerce_grouped_product_list_link', $grouped_product_child->get_permalink(), $grouped_product_child->get_id() ) ) . '">' . $grouped_product_child->get_name() . '</a>' : $grouped_product_child->get_name();
							$value .= '</label>';
							break;
						case 'price':
							$value = $grouped_product_child->get_price_html() . wc_get_stock_html( $grouped_product_child );
							break;
						default:
							$value = '';
							break;
					}

					echo '<td class="woocommerce-grouped-product-list-item__' . esc_attr( $column_id ) . '">' . apply_filters( 'woocommerce_grouped_product_list_column_' . $column_id, $value, $grouped_product_child ); // WPCS: XSS ok.
					
					if( esc_attr( $column_id ) == 'label' && $grouped_id == 25452) {
						if( $user_active_subcription_product_id == $grouped_product_child->get_id() ) { echo $html; }
						echo '
						<p class="radio-description ">'. get_the_excerpt( $grouped_product_child->get_id() ) . '</p>
                           <div class="plan-speeds">
                                <div class="plan-speed-download">
                                    <div class="plan-up fa fa-arrow-circle-o-up font-large left "></div>
                                    <div class="plan-speed-text ">
                                        <a class="tooltip-link" data-toggle="modal" data-target="#speed-mbps-tip-modal"><span>' . $grouped_product_child->get_attribute( 'download' ) . 'Mbps</span></a>
                                        download
                                    </div>
                                </div>
                                <div class="plan-speed-upload">
                                    <div class="plan-down fa fa-arrow-circle-o-down font-large left "></div>
                                    <div class="plan-speed-text ">
                                        <a class="tooltip-link" data-toggle="modal" data-target="#speed-mbps-tip-modal"><span>' . $grouped_product_child->get_attribute( 'upload' ) . ' Mbps</span></a>
                                        upload
                                    </div>
                                </div>
                            </div>
							';
					}
					echo '</td>';
					do_action( 'woocommerce_grouped_product_list_after_' . $column_id, $grouped_product_child );
				}

				echo '</tr>';
			}
			$post = $previous_post; // WPCS: override ok.
			setup_postdata( $post );
			?>
		</tbody>
	</table>

	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />

	<?php if ( $quantites_required ) : ?>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php endif; ?>
	
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
