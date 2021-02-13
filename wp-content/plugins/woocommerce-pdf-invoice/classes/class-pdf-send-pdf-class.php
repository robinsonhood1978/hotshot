<?php

		// include DomPDF autoloader
        require_once ( PDFPLUGINPATH . "lib/dompdf/autoload.inc.php" );

        // reference the Dompdf namespaces
		use WooCommercePDFInvoice\Dompdf;
		use WooCommercePDFInvoice\Options;

        class WC_send_pdf {

            public function __construct() {
            	$this->wc_version = get_option( 'woocommerce_version' );
				add_action( 'init', array( $this, 'init' ) );
            }

            function init() {
            	/**
				 * Check the email being sent and attach a PDF if it's the right one
				 */
				add_filter( 'woocommerce_email_attachments' , array( $this, 'pdf_attachment' ) ,10, 3 );
            }

            /**
			 * Check the email being sent and attach a PDF if it's the right one
             */
		 	public static function pdf_attachment( $attachment = NULL, $id = NULL, $order = NULL ) {

		 		// Stop everything if iconv or mbstring are not loaded, prevents fatal errors
            	if ( ! extension_loaded( 'iconv' ) || ! extension_loaded( 'mbstring' ) || ! $id || ! $order ) {
            		return $attachment;
            	}

            	$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );

            	// Make the array for email ids
            	$email_ids = array();
            	if( isset($woocommerce_pdf_invoice_options['attach_multiple']) && $woocommerce_pdf_invoice_options['attach_multiple'] !='' ) {
            		$email_ids = $woocommerce_pdf_invoice_options['attach_multiple'];
            	}

            	// Make sure the completed order IDs are in there
            	$email_ids[] = 'customer_completed_order';
            	$email_ids[] = 'customer_completed_renewal_order';

            	// Make sure it's a unique array
            	$email_ids = array_unique( $email_ids );

            	// Add a filter for the array
            	$email_ids = apply_filters( 'pdf_invoice_email_ids', $email_ids, $order );

            	if ( !empty( $email_ids ) && in_array( $id, $email_ids ) ) {
            		// Create the PDF
            		$pdf = WC_send_pdf::get_woocommerce_invoice( $order );

            		// Apply a filter to modify the PDF if required
            		$pdf = apply_filters( 'pdf_invoice_modify_attachment', $pdf, $id, $order );

            		// Add the PDF to the attachments array
            		$attachment[] = $pdf;	
				}

				return array_unique( $attachment );
				
		 	} // pdf_attachment

			Public Static function get_woocommerce_invoice( $order = NULL, $stream = NULL ) {
				
				// Stop everything if iconv or mbstring are not loaded, prevents fatal errors
				if ( ! extension_loaded( 'iconv' ) || ! extension_loaded( 'mbstring' ) || ! $order ) {
 					return array();
 				}

				// WooCommerce 3.0 compatibility 
        		$order_id   = is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : $order->id;

        		// Set the temp directory
        		$pdftemp = WC_send_pdf::get_pdf_temp();

				$pdf = new WC_send_pdf();
				
				$woocommerce_pdf_invoice_options = get_option('woocommerce_pdf_invoice_settings');

				// And now for the user variables, paper size and the like.
				$papersize 			= $woocommerce_pdf_invoice_options['paper_size']; 			// Currently A4 or Letter
				$paperorientation 	= $woocommerce_pdf_invoice_options['paper_orientation']; 	// Portrait or Landscape
				$customlogo			= '';														// No logo? No problem, we'll just use get_bloginfo('name')
				$footertext			= '';														// This is the legal stuff that you should be including everywhere!

				if( !isset($woocommerce_pdf_invoice_options['enable_remote']) || $woocommerce_pdf_invoice_options['enable_remote'] == 'false' ) {
					$pdfremoteimages = false;
				} else {
					$pdfremoteimages = true;
				}

				if( !isset($woocommerce_pdf_invoice_options['enable_subsetting']) || $woocommerce_pdf_invoice_options['enable_subsetting'] == 'true' ) {
					$fontsubsetting	= true;
				} else {
					$fontsubsetting	= true;
				}

				// Get the filename
				$filename 	= WC_send_pdf::create_filename( $order_id, $woocommerce_pdf_invoice_options );

				$messagetext  = '';
				$messagetext .= $pdf->get_woocommerce_invoice_content( $order_id );
					
				if ( $stream && 
					( !isset($woocommerce_pdf_invoice_options['pdf_termsid']) || $woocommerce_pdf_invoice_options['pdf_termsid'] == 0 ) && 
					( !isset($woocommerce_pdf_invoice_options['pdf_creation']) || $woocommerce_pdf_invoice_options['pdf_creation'] == 'standard' )
				) {
					// Start the PDF Generator for the invoice
					@ob_clean();

					// Set Option for remote images
					$options = new Options();
					$options->set([
						'isRemoteEnabled' 		=> $pdfremoteimages,
						'isHtml5ParserEnabled' 	=> true,
						'enable_font_subsetting'=> $fontsubsetting,
						'tempDir'				=> $pdftemp,
						'logOutputFile'			=> $pdftemp . DIRECTORY_SEPARATOR . "log.htm"
					]);

					$dompdf = new DOMPDF();
					$dompdf->setOptions($options);
					$dompdf->load_html( $messagetext );
					$dompdf->set_paper( $papersize, $paperorientation );
					$dompdf->render();
						
					// Output the PDF for download
					return $dompdf->stream( $filename );
						
				} elseif ( 
					( isset($woocommerce_pdf_invoice_options['pdf_termsid']) && $woocommerce_pdf_invoice_options['pdf_termsid'] != 0 ) || 
					( isset($woocommerce_pdf_invoice_options['pdf_creation']) && $woocommerce_pdf_invoice_options['pdf_creation'] == 'file' )
				) {
					/**
					 * This section deals with sending / generating a PDF Invoice that will include a Terms and Conditions page
					 * Uses PDF Merge library
					 *
					 * REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
					 * You do not need to give a file path for browser, string, or download - just the name.
					 */
					
					// Add PDF extension 
					if (strpos($filename, '.pdf') === false) {
						$filename =  $filename . '.pdf';
					}
						 
					// Set Option for remote images
					$options = new Options();
					$options->set([
						'isRemoteEnabled' 		=> $pdfremoteimages,
						'isHtml5ParserEnabled' 	=> true,
						'enable_font_subsetting'=> $fontsubsetting,
						'tempDir'				=> $pdftemp,
						'logOutputFile'			=> $pdftemp . DIRECTORY_SEPARATOR . "log.htm"
					]);

					$dompdf = new DOMPDF();
					$dompdf->setOptions($options);
					$dompdf->load_html( $messagetext );
					$dompdf->set_paper( $papersize, $paperorientation );
					$dompdf->render();
						
					$invattachments = $pdftemp . '/inv' . $filename;
						
					// Write the PDF to the TMP directory		
					file_put_contents( $invattachments, $dompdf->output() );
						
					@ob_clean();

					if ( !class_exists('PDFMerger') ) {
						include ( PDFPLUGINPATH . 'lib/PDFMerger/PDFMerger.php' );
					}

					if ( isset($woocommerce_pdf_invoice_options['pdf_termsid']) && $woocommerce_pdf_invoice_options['pdf_termsid'] != 0 ) {

						$options = new Options();
						$options->set([
							'isRemoteEnabled' 		=> $pdfremoteimages,
							'isHtml5ParserEnabled' 	=> true,
							'enable_font_subsetting'=> $fontsubsetting,
							'tempDir'				=> $pdftemp,
							'logOutputFile'			=> $pdftemp . DIRECTORY_SEPARATOR . "log.htm" 
						]);

						// Start the PDF Generator for the terms
						$dompdf = new Dompdf();
						$dompdf->setOptions($options);
						$dompdf->load_html( $pdf->get_woocommerce_invoice_terms( $woocommerce_pdf_invoice_options['pdf_termsid'], $order_id ) );
						$dompdf->set_paper( $papersize, $paperorientation );
						$dompdf->render();
						
						$termsattachments = $pdftemp . '/terms-' . $filename;
						
						// Write the PDF to the TMP directory		
						file_put_contents( $termsattachments, $dompdf->output() );
					
						$pdf = new PDFMerger;
						
						if ( $stream ) {
							$pdf->addPDF( $invattachments, 'all' )
								->addPDF( $termsattachments, 'all' )
								->merge( 'download', $filename );
								exit;
						} else {
							$pdf->addPDF( $invattachments, 'all' )
								->addPDF( $termsattachments, 'all' )
								->merge( 'file', $pdftemp . '/' . $filename );
						}

					} else {
					
						$pdf = new PDFMerger;

						if ( $stream ) {
							$pdf->addPDF( $invattachments, 'all' )
								->merge( 'download', $filename );
								exit;
						} else {
							$pdf->addPDF( $invattachments, 'all' )
								->merge( 'file', $pdftemp . '/' . $filename );
						}

					}
						
					// Send the file name and location to the Email
					// return 	array( $invattachments, $termsattachments );
					return ( $pdftemp . '/' . $filename );
											
				} else {
					// Add PDF extension 
					if (strpos($filename, '.pdf') === false) {
						$filename =  $filename . '.pdf';
					}

					@ob_clean();
					// Set Option for remoate images
					$options = new Options();
					$options->set([
						'isRemoteEnabled' 		=> $pdfremoteimages,
						'isHtml5ParserEnabled' 	=> true,
						'enable_font_subsetting'=> $fontsubsetting,
						'tempDir'				=> $pdftemp,
						'logOutputFile'			=> $pdftemp . DIRECTORY_SEPARATOR . "log.htm" 
					]);

					$dompdf = new DOMPDF();
					$dompdf->setOptions($options);
					$dompdf->load_html( $messagetext );
					$dompdf->set_paper( $papersize, $paperorientation );
					$dompdf->render();
					
					$attachments = $pdftemp . '/' . $filename;
					
					// Write the PDF to the TMP directory		
					file_put_contents( $attachments, $dompdf->output() );
		
					// Send the file name and location to the Email
					return 	$attachments;
						
				}

			}

			/**
			 * Create the file name based on the settings
			 *
			 * Allowed variables
			 *
			 * companyname
			 * invoicedate
			 * invoicenumber
			 * month
			 * mon
			 * year
			 */
			private static function create_filename( $order_id, $woocommerce_pdf_invoice_options ) {

				$pdf = new WC_send_pdf();

				$replace 	= array( ' ', "/", "'",'"', "--" );
				$clean_up	= array( ',' );
				$filename	= $woocommerce_pdf_invoice_options['pdf_filename'];

				if ( $filename == '' ) {

					$filename	= get_bloginfo('name') . '-' . $order_id;

				} else {

					$invoice_date = $pdf->get_woocommerce_pdf_date( $order_id,'completed', true );

					$filename	= str_replace( '{{company}}',	$woocommerce_pdf_invoice_options['pdf_company_name'] , $filename );
					$filename	= str_replace( '{{invoicedate}}', $invoice_date, $filename );
					$filename	= str_replace( '{{invoicenumber}}',	( $pdf->get_woocommerce_pdf_invoice_num( $order_id ) ? $pdf->get_woocommerce_pdf_invoice_num( $order_id ) : $order_id ) , $filename );
					$filename	= str_replace( '{{month}}',	date( 'F', strtotime( $invoice_date ) ) , $filename );
					$filename	= str_replace( '{{mon}}',	date( 'M', strtotime( $invoice_date ) ) , $filename );
					$filename	= str_replace( '{{year}}',	date( 'Y', strtotime( $invoice_date ) ) , $filename );
					
				}

				// Clean up the filename
				$filename	= str_replace( $replace, '-' , $filename );
				$filename	= str_replace( $clean_up, '' , $filename );

				// Filter the filename
				$filename 	= apply_filters( 'pdf_output_filename', $filename, $order_id );

				return $filename;

			}

			/**
			 * Get the PDF order details in a table
			 * @param  [type] $order_id 
			 * @return [type]           
			 */
			function get_woocommerce_pdf_order_details( $order_id ) {
				global $woocommerce;
				$order 	 = new WC_Order( $order_id );

				// Check WC version - changes for WC 3.0.0
				$pre_wc_30 		= version_compare( WC_VERSION, '3.0', '<' );
				$order_currency = $pre_wc_30 ? $order->get_order_currency() : $order->get_currency();
							
				$pdflines  = '<table width="100%" class="shop_table ordercontent">';
				$pdflines .= '<tbody>';

				if ( sizeof( $order->get_items() ) > 0 ) : 

					foreach ( $order->get_items() as $item ) {	

						if ( $item['quantity'] ) {
							
							$line = '';
							// $item_loop++;

							$_product 	= $order->get_product_from_item( $item );
							$item_name 	= $item['name'];
							$item_id 	= $pre_wc_30 ? $item['variation_id'] : $item->get_id();

							$meta_display = '';
							if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
								$item_meta  = new WC_Order_Item_Meta( $item );
								$meta_display = $item_meta->display( true, true );
								$meta_display = $meta_display ? ( ' ( ' . $meta_display . ' )' ) : '';
			 				} else {
								foreach ( $item->get_formatted_meta_data() as $meta_key => $meta ) {
									$meta_display .= '<br /><small>(' . $meta->display_key . ':' . wp_kses_post( strip_tags( $meta->display_value ) ) . ')</small>';
								}
			 				}

			 				// Add Booking details
			 				if ( class_exists( 'WC_Booking_Data_Store' ) ) {
								$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );

								if ( $booking_ids ) {
									foreach ( $booking_ids as $booking_id ) {

										$booking = new WC_Booking( $booking_id );

										$product  = $booking->get_product();
										$resource = $booking->get_resource();
										$label    = $product && is_callable( array( $product, 'get_resource_label' ) ) && $product->get_resource_label() ? $product->get_resource_label() : __( 'Type', 'woocommerce-bookings' );

										if ( strtotime( 'midnight', $booking->get_start() ) === strtotime( 'midnight', $booking->get_end() ) ) {
											$booking_date = sprintf( '%1$s', $booking->get_start_date() );
										} else {
											$booking_date = sprintf( '%1$s / %2$s', $booking->get_start_date(), $booking->get_end_date() );
										}

										$meta_display .= '<br /><small>' . esc_html( sprintf( __( 'Booking ID : %d', 'woocommerce-pdf-invoice' ), $booking_id ) ) . '</small>';
										$meta_display .= '<br /><small>' . esc_html( sprintf( __( 'Booking Date : %s', 'woocommerce-pdf-invoice' ), apply_filters( 'wc_bookings_summary_list_date', $booking_date, $booking->get_start(), $booking->get_end() ) ) ) . '</small>';

										if ( $resource ) :
											$meta_display .= '<br /><small>' . esc_html( sprintf( __( '%s: %s', 'woocommerce-bookings' ), $label, $resource->get_name() ) ) . '</small>';
										endif;

										if ( $product->has_persons() ) {
											if ( $product->has_person_types() ) {
												$person_types  = $product->get_person_types();
												$person_counts = $booking->get_person_counts();

												if ( ! empty( $person_types ) && is_array( $person_types ) ) {
													foreach ( $person_types as $person_type ) {

														if ( empty( $person_counts[ $person_type->get_id() ] ) ) {
															continue;
														}

														$meta_display .= '<br /><small>' . esc_html( sprintf( '%s: %d', $person_type->get_name(), $person_counts[ $person_type->get_id() ] ) ) . '</small>';

													}
												}
											} else {

												$meta_display .= '<br /><small>Persons : ' . esc_html( sprintf( __( '%d Persons', 'woocommerce-bookings' ), array_sum( $booking->get_person_counts() ) ) ) . '</small>';

											}
										}

									}

								}

			 				} // Add Booking details
 
							if ( $meta_display ) {

								$meta_output	 = apply_filters( 'pdf_invoice_meta_output', $meta_display );
								$item_name 		.= $meta_output;

 							}

 							/**
 							 * Allow additional info to be added to the $item_name
							 *
 							 * add_filter( 'pdf_invoice_item_name', 'add_product_description_pdf_invoice_item_name', 10, 4 );
 							 * 
 							 * function add_product_description_pdf_invoice_item_name( $item_name, $item, $product, $order ) {
 							 * 	
 							 *	// Use $product->get_id() if you want to get the post id for the product.
 							 * 	$item_name .= '<p>' . $product->get_description() . '</p>';
 							 * 	return $item_name;
	 						 * 
 							 * }
 							 */
 							$item_name = apply_filters( 'pdf_invoice_item_name', $item_name, $item, $_product, $order );

							$line = 	'<tr>' .
										'<td valign="top" width="5%" align="right">' . $item['quantity'] . ' x</td>' .
										'<td valign="top" width="50%">' .  stripslashes( $item_name ) . '</td>' .
										'<td valign="top" width="9%" align="right">'  .  wc_price( ($item['subtotal'] / $item['qty'])/1.15, array( 'currency' => $order_currency ) ) . '</td>' .
										'<td valign="top" width="9%" align="right">'  .  wc_price( $item['subtotal']/1.15, array( 'currency' => $order_currency ) ) . '</td>' .
										'<td valign="top" width="7%" align="right">'  .  wc_price( $item['subtotal']-$item['subtotal']/1.15, array( 'currency' => $order_currency ) ). '</td>' .
										'<td valign="top" width="10%" align="right">' .  wc_price( ( $item['subtotal'] + $item['total_tax'] ) / $item['qty'], array( 'currency' => $order_currency ) ). '</td>' .
										'<td valign="top" width="10%" align="right">' .  wc_price( $item['subtotal'] + $item['total_tax'], array( 'currency' => $order_currency ) ). '</td>' .
										'</tr>';
							
							$pdflines .= $line;
						}
					}
			
				endif;

				$pdflines .=	'</tbody>';
				$pdflines .=	'</table>';
				
				$pdf 	= apply_filters( 'pdf_template_line_output', $pdflines, $order_id );
				return $pdf;
			}

			/**
			 * Get the Invoice Number
			 * @param  [type] $order_id [description]
			 * @return [type]           [description]
			 */
			function get_woocommerce_pdf_invoice_num( $order_id ) {
				global $woocommerce;
		
				if ( $order_id ) :
					$invnum = esc_html( get_post_meta( $order_id, '_invoice_number_display', true ) );
				else :
					$invnum = ''; 
				endif;

				return $invnum;
			}
	
			/** 
			 * Get the invoice date
			 * @param  [type] $order_id [description]
			 * @param  [type] $usedate  [description]
			 * @return [type]           [description]
			 */
			public static function get_woocommerce_pdf_date( $order_id, $usedate, $sendsomething = false ) {
				global $woocommerce;
				
				if( get_post_meta( $order_id, '_invoice_date', TRUE ) ) {
					return get_post_meta( $order_id, '_invoice_date', TRUE );
				}

				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				$date_format = $woocommerce_pdf_invoice_options['pdf_date_format'];

				// Force a $date_format if one is not set
				if ( !isset( $date_format ) || $date_format == '' ) {
					$date_format = "j F, Y";
				}

				$order 	 = new WC_Order( $order_id );
		
				if ( $usedate == 'completed' ) {
					$date = esc_html( get_post_meta( $order_id, '_invoice_date', TRUE ) );

					// Double check $date is set if the order is completed
					$order_status	= is_callable( array( $order, 'get_status' ) ) ? $order->get_status() : $order->order_status;
					if( $order_status == 'completed' && $date == '' ) {
						$date = WC_send_pdf::get_completed_date( $order_id );
					}

				} else {
					// WooCommerce 3.0 compatibility
					$date = is_callable( array( $order, 'get_date_created' ) ) ? $order->get_date_created() : $order->order_date;
				}

				// In some cases $date will be empty so we might want to send the order date
				if ( $sendsomething && !$date ) {
					// WooCommerce 3.0 compatibility
					$date = is_callable( array( $order, 'get_date_created' ) ) ? $order->get_date_created() : $order->order_date;
				}
				
				if ( $date ) {

					// Make sure the date is formated correctly
					$date_check = DateTime::createFromFormat( get_option( 'date_format' ), $date );

					if( $date_check ) {
						$date = $date_check->format( $date_format );
					}

					if( strtotime( $date ) ) {
						$date = date_i18n( $date_format, strtotime( $date ) );
					}

					// Return a date in the format that matches the PDF Ivoice settings.
					return $date;

				} else {
					return '';
				}
		
			}

			// Get the date the order was completed if _invoice_date was not set at the time the invoice number was created
			public static function get_completed_date( $order_id ) {

				$date = '';

				// Use _date_completed from order meta
				$date = get_post_meta( $order_id, '_completed_date', true );

				// if _date_completed is empty then use this as a backup
				if( !isset( $date ) || $date == '' ) {

					if( get_post_meta($order_id, '_invoice_meta', TRUE) && get_post_meta($order_id, '_invoice_meta', TRUE) != '' ) {

						$invoice_meta = get_post_meta($order_id, '_invoice_meta', TRUE);
						$date 		  = $invoice_meta['invoice_created'];

					} else {
						global $wpdb;

						$invoice_number = get_post_meta( $order_id, '_invoice_number_display', TRUE );

						$invoice = $wpdb->get_row( "SELECT * FROM $wpdb->comments 
													WHERE comment_post_id = $order_id 
													AND comment_content LIKE '% $invoice_number %' 
													AND comment_type = 'order_note'
													LIMIT 1;"
												);
									

						$date  = $invoice->comment_date;
					}

				}

				return $date;
			}
			
			/**
			 * Get the order notes for the template
			 */			
			function get_pdf_order_note( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order 			= new WC_Order( $order_id );
				// WooCommerce 3.0 compatibility 
        		$customer_note  = is_callable( array( $order, 'get_customer_note' ) ) ? $order->get_customer_note() : $order->customer_note;

				$output = '';
				
				if( $customer_note ) {
					$output = '<h3>' . __('Note:', 'woocommerce-pdf-invoice') . '</h3>' . wpautop( wptexturize( $customer_note ) );
					$output = apply_filters( 'pdf_template_order_notes' , $output, $order_id );
				}
				return $output;
					
			}
			
			/**
			 * Get the order subtotal for the template
			 */
			function get_pdf_order_subtotal( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order = new WC_Order( $order_id );
				$output = '';

				$output = 	'<tr>' .
							'<td align="right">' .
							'<strong>' . __('Subtotal', 'woocommerce-pdf-invoice') . '</strong></td>' .
							'<td align="right"><strong>' . $order->get_subtotal_to_display() . '</strong></td>' .
							'</tr>' ;
				$output = apply_filters( 'pdf_template_order_subtotal' , $output, $order_id );
				return $output;
			}
			
			/**
			 * Get the order shipping total for the template
			 */
			function get_pdf_order_shipping( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order = new WC_Order( $order_id );
				$output = '';
				
				$output = 	'<tr>' .
							'<td align="right">' .
							'<strong>' . __('Shipping', 'woocommerce-pdf-invoice') . '</strong></td>' .
							'<td align="right"><strong>' . $order->get_shipping_to_display() . '</strong></td>' .
							'</tr>' ;
				
				$output = apply_filters( 'pdf_template_order_shipping' , $output, $order_id );
				return $output;
			}

			/**
			 * Show coupons used
			 */
			function pdf_coupons_used( $order_id ) {
				global $woocommerce;

				if (!$order_id) return;	
				$order = new WC_Order( $order_id );

				$output = '';

				if( $order->get_used_coupons() ) {
					
					$coupons_count = count( $order->get_used_coupons() );
					
					$i = 1;
					$coupons_list = '';
					foreach( $order->get_used_coupons() as $coupon) {
						
						$coupons_list .= $coupon;
						if( $i < $coupons_count )
							$coupons_list .= ', ';
						
						$i++;
					}

					$output .= '<br /><strong>' . __('Coupons used', 'woocommerce-pdf-invoice') . ' (' . $coupons_count . ') :</strong>' . $coupons_list;
				
				} // endif get_used_coupons

				$output = apply_filters( 'pdf_template_order_coupons' , $output, $order_id );

				return $output;

			}
			
			/**
			 * Get the order discount for the template
			 */
			function get_pdf_order_discount( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order = new WC_Order( $order_id );

				// Check WC version - changes for WC 3.0.0
				$pre_wc_30 		= version_compare( WC_VERSION, '3.0', '<' );
				$order_discount = $pre_wc_30 ? woocommerce_price( $order->order_discount ) : wc_price( $order->get_total_discount() );

				$output = '';

				if ( $order_discount > 0 ) {
					$output .=  '<tr>' .
								'<td align="right" valign="top">' .
								'<strong>' . esc_html__('Discount', 'woocommerce-pdf-invoice') . '</strong>' . $this->pdf_coupons_used( $order_id ) . '</td>' .
								'<td align="right" valign="top"><strong>' . $order_discount . '</strong></td>' .
								'</tr>' ;
				}
				
				$output = apply_filters( 'pdf_template_order_discount' , $output, $order_id );
				return $output;
			}
			
			/**
			 * Get the tax for the template
			 */
			function get_pdf_order_tax( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order = new WC_Order( $order_id );

				// Check WC version - changes for WC 3.0.0
				$pre_wc_30 		= version_compare( WC_VERSION, '3.0', '<' );

				$output = '';

				if ( $order->get_total_tax()>0 ) {

					$tax_items = $order->get_tax_totals();
				
					if ( count( $tax_items ) > 1 ) {

						foreach ( $tax_items as $tax_item ) {
							$tax_item_amount = $pre_wc_30 ? woocommerce_price( $tax_item->amount ) : wc_price( $tax_item->amount );
							$output .=  '<tr>' .
										'<td align="right">' . esc_html( $tax_item->label ) . '</td>' .
										'<td align="right">' . $tax_item_amount . '</td>' .
										'</tr>' ;
						}

						$total_tax = $pre_wc_30 ? woocommerce_price( $order->get_total_tax() ) : wc_price( $order->get_total_tax() );

						$output .=  '<tr>' .
									'<td align="right">' . __('Total Tax', 'woocommerce-pdf-invoice') . '</td>' .
									'<td align="right">' . $total_tax . '</td>' .
									'</tr>' ;

					} else {

						foreach ( $tax_items as $tax_item ) {

							$tax_item_amount = $pre_wc_30 ? woocommerce_price( $tax_item->amount ) : wc_price( $tax_item->amount );
							$output .=  '<tr>' .
										'<td align="right">' . esc_html( $tax_item->label ) . '</td>' .
										'<td align="right">' . $tax_item_amount . '</td>' .
										'</tr>' ;
						}

					}


				}

				$output = apply_filters( 'pdf_template_order_tax' , $output, $order_id );
				return $output;

			}
			
			/**
			 * [get_pdf_order_total description]
			 * @param  [type] $order_id [description]
			 * @return [type]           [description]
			 */
			function get_pdf_order_total( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );

				if (!$order_id) return;	
				$order = new WC_Order( $order_id );

				// Check WC version - changes for WC 3.0.0
				$pre_wc_30 		= version_compare( WC_VERSION, '3.0', '<' );
				$order_total = $pre_wc_30 ? woocommerce_price( $order->order_total ) : wc_price( $order->get_total() );

				$output =  	'<tr>' .
							'<td align="right">' .
							'<strong>' . __('Grand Total', 'woocommerce-pdf-invoice') . '</strong></td>' .
							'<td align="right"><strong>' . $order_total . '</strong></td>' .
							'</tr>' ;
				$output = apply_filters( 'pdf_template_order_total' , $output, $order_id );
				return $output;
			}

			/**
			 * [get_pdf_order_totals description]
			 * New for Version 1.3.0, replaces several functions with one looped function
			 * @param  [type] $order_id [description]
			 * @return [type]           [description]
			 */
			function get_pdf_order_totals( $order_id ) {
				global $woocommerce;

				if (!$order_id) return;	
				$order = new WC_Order( $order_id );

				// Check WC version - changes for WC 3.0.0
				$pre_wc_30 		= version_compare( WC_VERSION, '3.0', '<' );
				$order_currency = $pre_wc_30 ? $order->get_order_currency() : $order->get_currency();

				$order_item_totals = $order->get_order_item_totals();
 
				unset( $order_item_totals['payment_method'] );

				$output = '';

				foreach ( $order_item_totals as $order_item_total ) {

					$output .=  '<tr>' .
								'<td align="right">' .
								'<strong>' . $order_item_total['label'] . '</strong></td>' .
								'<td align="right"><strong>' . $order_item_total['value'] . '</strong></td>' .
								'</tr>' ;

				}

				if( $order->get_total_refunded() > 0 ) {

					$output .=  '<tr>' .
								'<td align="right">' .
								'<strong>Amount Refunded:</strong></td>' .
								'<td align="right"><strong>' . wc_price( $order->get_total_refunded(), array( 'currency' => $order_currency ) ) . '</strong></td>' .
								'</tr>' ;
								
				}

				$output = apply_filters( 'pdf_template_order_totals' , $output, $order_id );
				return $output;

			}
			
			/**
			 * [get_woocommerce_invoice_content description]
			 * @param  [type] $order_id [description]
			 * @return [type]           [description]
			 */
			function get_woocommerce_invoice_content( $order_id ) {
				global $woocommerce;

				// WPML
				do_action( 'before_invoice_content', $order_id );

				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order 			   = new WC_Order( $order_id );


				// Check if the order has an invoice
				$invoice_number_display = get_post_meta( $order_id, '_invoice_number_display', true );

				// Use the stored company info.
				$pdfcompanyname    = get_post_meta( $order_id,'_pdf_company_name',TRUE );
				$pdfcompanydetails = nl2br(get_post_meta( $order_id,'_pdf_company_details',TRUE ));
				$pdfregisteredname = get_post_meta( $order_id,'_pdf_registered_name',TRUE );
				$pdfregaddress	   = get_post_meta( $order_id,'_pdf_registered_address',TRUE );
				$pdfcompanynumber  = get_post_meta( $order_id,'_pdf_company_number',TRUE );
				$pdftaxnumber 	   = get_post_meta( $order_id,'_pdf_tax_number',TRUE );

				if ( !isset( $pdfcompanyname ) || $pdfcompanyname == '' ) {
					$pdfcompanyname    = __( $woocommerce_pdf_invoice_options['pdf_company_name'], 'woocommerce-pdf-invoice' );
				}

				if ( !isset( $pdfcompanydetails ) || $pdfcompanydetails == '' ) {
					$pdfcompanydetails = nl2br( $woocommerce_pdf_invoice_options['pdf_company_details'] );
				}
				if ( !isset( $pdfregisteredname ) || $pdfregisteredname == '' ) {
					$pdfregisteredname = $woocommerce_pdf_invoice_options['pdf_registered_name'];
				}
				if ( !isset( $pdfregaddress ) || $pdfregaddress == '' ) {
					$pdfregaddress	   = $woocommerce_pdf_invoice_options['pdf_registered_address'];
				}
				if ( !isset( $pdfcompanynumber ) || $pdfcompanynumber == '' ) {
					$pdfcompanynumber  = $woocommerce_pdf_invoice_options['pdf_company_number'];
				}
				if ( !isset( $pdftaxnumber ) || $pdftaxnumber == '' ) {
					$pdftaxnumber 	   = $woocommerce_pdf_invoice_options['pdf_tax_number'];
				}

				$pdflogo = $woocommerce_pdf_invoice_options['logo_file'];

				if ( $pdflogo ) :

					// Replace the URL with the file structure
					// Required whn the Remote Logo option is se to "no"
					$pdflogo = str_replace( site_url(), ABSPATH, $pdflogo );


					$logo = '<img src="' . $pdflogo . '" alt="' . get_bloginfo('name') . '" />';				
				else :
					$logo = '<h1>' . get_bloginfo('name') . '</h1>';	
				endif;

				/**
				 * Look for the Sequential Order Numbers Pro / Sequential Order Numbers order number and use it if it's there
				 */
				$output_order_num = $order->get_order_number();


                $subscriptions_ids = wcs_get_subscriptions_for_order( $order_id );

                $output_subscription_no = 0;
                // We get the related subscription for this order
                foreach( $subscriptions_ids as $subscription_id => $subscription_obj ){
                    $output_subscription_no = $subscription_id;
                    if($subscription_obj->order->id == $order_id) break; // Stop the loop
                }

                $subscriptions         = wcs_get_subscriptions_for_order( $order_id, array( 'order_type' => array( 'parent', 'renewal' ) ) );
                foreach ( $subscriptions as $subscription ) {
                    // If we're on a single subscription or renewal order's page, display the parent orders
                    if ( 1 == count( $subscriptions ) && $subscription->get_parent_id() ) {
                        $output_subscription_no = $subscription->get_id();
                        $parent_order_id = $subscription->get_parent_id();

                    }
                    $renewal_orders = $subscription->get_related_orders( 'all', 'renewal'  );

                }

                $parent_order = new WC_Order( $parent_order_id );

                $completed_date_of_parent_order = $parent_order->get_date_completed();

                if($completed_date_of_parent_order)
                    $completed_date_of_parent_order = $completed_date_of_parent_order->date( 'Y-m-d' );

                $parent_order_total = $parent_order->get_total();
                $parent_items_count = $parent_order->get_item_count();

                $str_renewal_order_id = '';
                $arr_orders = [];
                $arr_orders[$parent_order_id] =1;
                foreach($renewal_orders as $renewal_order){
                    //$str_renewal_order_id .= $renewal_order->get_item_count().",";
                    $arr_orders[$renewal_order->get_id()] =$renewal_order->get_item_count();
                }
                ksort($arr_orders);
                $item_total=0;
                $current_items = 0;
                foreach($arr_orders as $key=>$value){
                    $item_total = $item_total+$value;
                    if($key==$order_id){
                        $current_items = $value;
                        break;
                    }
                    $str_renewal_order_id .= $key.",";
                }

                //$completed_date_of_parent_order = '2019-11-29';

                $start_date = date('j F Y', strtotime ("+".($item_total-$current_items)." month", strtotime($completed_date_of_parent_order)));
                $end_date = date('j F Y', strtotime ('-1 day',strtotime ("+".$item_total." month", strtotime($completed_date_of_parent_order))));

                /*$time = mktime(20,20,20,2,1,$year);//取得一个日期的 Unix 时间戳;
                if (date("t",$time)==29){ //格式化时间，并且判断2月是否是29天；
                    echo $year."是闰年";//是29天就输出时闰年；
                }else{
                    echo $year."不是闰年";
                }*/






                $billing_period = $start_date." to ".$end_date;
                if($output_subscription_no==0){
                    $output_subscription_no='';
                    $billing_period = '';
                }
                //$billing_period = $parent_items_count ;
                //$billing_period = $parent_order_id.",".$completed_date_of_parent_order.",".$parent_order_total.",".$parent_items_count;

                /*$items = $order->get_items();
                $product_id = 0;
                foreach ( $items as $item ) {

                    //$product = $item->get_product();
                    $product_id   = $item->get_product_id(); // the Product id
                    break;

                    // Now you have access to (see above)...

                    //$product->get_type();
                    //$product->get_name();
                    // etc.
                    // etc.

                }
                //$billing_period   = WC_Subscriptions_Product::get_period( $product_id );
                $billing_interval = WC_Subscriptions_Product::get_interval( $product_id );*/

                //$parent_id = $order->get_date_created()->date( 'Y-m-d' );

                // The subscription ID: $subscription_id
                // The An instance of the Subscription object: $subscription_obj



				if( !is_admin() ) {
					include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}

				// Sequential Order Numbers
				if ( get_post_meta( $order_id,'_order_number',TRUE ) && class_exists( 'WC_Seq_Order_Number' ) ) :
					$output_order_num = get_post_meta( $order_id,'_order_number',TRUE );
				endif;

				// Sequential Order Numbers Pro
				if ( get_post_meta( $order_id,'_order_number_formatted',TRUE ) && class_exists( 'WC_Seq_Order_Number_Pro' ) ) :
					$output_order_num = get_post_meta( $order_id,'_order_number_formatted',TRUE );
				endif;

				$headers =  '<table class="shop_table orderdetails" width="100%">' . 
							'<thead>' .
							'<tr><th colspan="7" align="left"><h2>' . esc_html__('Order Details', 'woocommerce-pdf-invoice') . '</h2></th></tr>' .
							'<tr>' .
							'<th width="5%" valign="top" align="right">'  . esc_html__( 'Qty', 'woocommerce-pdf-invoice' ) 		. '</th>' .						
							'<th width="50%" valign="top" align="left">'  . esc_html__( 'Product', 'woocommerce-pdf-invoice' ) 	. '</th>' .
							'<th width="9%" valign="top" align="right">'  . esc_html__( 'Price Ex', 'woocommerce-pdf-invoice' ) 	. '</th>' .
							'<th width="9%" valign="top" align="right">'  . esc_html__( 'Total Ex.', 'woocommerce-pdf-invoice' ) 	. '</th>' .
							'<th width="7%" valign="top" align="right">'  . esc_html__( 'Tax', 'woocommerce-pdf-invoice' ) 		. '</th>' .
							'<th width="10%" valign="top" align="right">' . esc_html__( 'Price Inc', 'woocommerce-pdf-invoice' ) 	. '</th>' .
							'<th width="10%" valign="top" align="right">' . esc_html__( 'Total Inc', 'woocommerce-pdf-invoice' ) 	. '</th>' .
							'</tr>' .
							'</thead>' .
							'</table>';

				// Buffer
				ob_start();
				
				// load_template( $pdftemplate, false );
				require( $this->get_pdf_template( 'template.php' ) );

				// Get contents
				$content = ob_get_clean();

				/**
				 * Notify when the PDF is about to be generated
				 *
				 * Added for Currency Switcher for WooCommerce
				 */
				do_action( 'woocommerce_pdf_invoice_before_pdf_content', $order );
		
				// REPLACE ALL TEMPLATE TAGS WITH REAL CONTENT
				$content = str_replace(	'[[PDFLOGO]]', 					$logo, 			 	$content );
				$content = str_replace(	'[[PDFCOMPANYNAME]]', 			$pdfcompanyname, 	$content );
				$content = str_replace(	'[[PDFCOMPANYDETAILS]]', 		$pdfcompanydetails, $content );
				$content = str_replace(	'[[PDFREGISTEREDNAME]]', 		$pdfregisteredname, $content );
				$content = str_replace(	'[[PDFREGISTEREDADDRESS]]', 	$pdfregaddress, 	$content );
				$content = str_replace(	'[[PDFCOMPANYNUMBER]]', 		$pdfcompanynumber, 	$content );
				$content = str_replace(	'[[PDFTAXNUMBER]]', 			$pdftaxnumber, 		$content );
		
				$content = str_replace(	'[[PDFINVOICENUM]]', 			$this->get_woocommerce_pdf_invoice_num( $order_id ),					$content );
				$content = str_replace(	'[[PDFORDERENUM]]', 			$output_order_num, 									  					$content );
				$content = str_replace(	'[[PDFINVOICEDATE]]', 			$this->get_woocommerce_pdf_date( $order_id,'completed', false ), 		$content );
				$content = str_replace(	'[[PDFORDERDATE]]', 			$this->get_woocommerce_pdf_date( $order_id,'ordered', false ), 			$content );
		
				$content = str_replace(	'[[PDFBILLINGADDRESS]]', 		$order->get_formatted_billing_address(),  								$content );
				$content = str_replace(	'[[PDFBILLINGTEL]]', 			get_post_meta( $order_id,'_billing_phone',TRUE ), 	  					$content );
				$content = str_replace(	'[[PDFBILLINGEMAIL]]', 			get_post_meta( $order_id,'_billing_email',TRUE ), 						$content );
				$content = str_replace(	'[[PDFSHIPPINGADDRESS]]', 		$order->get_formatted_shipping_address(), 								$content );
				$content = str_replace(	'[[PDFINVOICEPAYMENTMETHOD]]',	ucwords( get_post_meta( $order_id, '_payment_method_title', true ) ), 	$content );
				$content = str_replace(	'[[PDFSHIPPINGMETHOD]]',		ucwords( $order->get_shipping_method() ), 								$content );

                $content = str_replace(	'[[PDFINVOICE_SubscriptionNo]]',		$output_subscription_no, 								$content );
                $content = str_replace(	'[[BillingPeriod]]',		$billing_period, 								$content );

                $content = str_replace(	'[[ORDERINFOHEADER]]',			apply_filters( 'pdf_template_table_headings', $headers, $order_id ), 	$content );
				$content = str_replace(	'[[ORDERINFO]]', 				$this->get_woocommerce_pdf_order_details( $order_id ), 	  				$content );
			
				$content = str_replace(	'[[PDFORDERNOTES]]', 			$this->get_pdf_order_note( $order_id ), 	  							$content );
				
				// 1.2.16			
				$content = str_replace(	'[[PDFORDERSUBTOTAL]]', 		$this->get_pdf_order_subtotal( $order_id ), 	  						$content );
				$content = str_replace(	'[[PDFORDERSHIPPING]]', 		$this->get_pdf_order_shipping( $order_id ), 	  						$content );
				$content = str_replace(	'[[PDFORDERDISCOUNT]]', 		$this->get_pdf_order_discount( $order_id ), 	  						$content );
				$content = str_replace(	'[[PDFORDERTAX]]', 				$this->get_pdf_order_tax( $order_id ), 	  								$content );
				$content = str_replace(	'[[PDFORDERTOTAL]]', 			$this->get_pdf_order_total( $order_id ), 	  							$content );

				// 1.3.0
				$content = str_replace(	'[[PDFORDERTOTALS]]', 			$this->get_pdf_order_totals( $order_id ), 	  							$content );

				// 4.0.2
				$barcode_text = get_post_meta( $order_id,'_barcode_text',TRUE );
				// 4.2.1
				$show_barcode = apply_filters( 'pdf_template_show_barcode', true );

				if( isset( $barcode_text ) && $barcode_text != '' && $show_barcode ) {
					$generator 	  = new \Picqer\Barcode\BarcodeGeneratorPNG();
					$barcode_type = WC_send_pdf::get_barcode_type();

					$barcode =  '<div class="barcode"><img src="data:image/png;base64,' . base64_encode( $generator->getBarcode( $barcode_text, $barcode_type ) ) . '"/><br />' . $barcode_text . '</div>';
					$content = str_replace(	'[[PDFBARCODES]]', $barcode, $content );
				} else {
					$content = str_replace(	'[[PDFBARCODES]]', '', $content );
				}
				
				// Support for EU VAT Number Extension
				if ( get_post_meta( $order_id,'VAT Number',TRUE ) ) {
					$content = str_replace(	'[[PDFBILLINGVATNUMBER]]', '<br />' . __( 'VAT Number : ', 'woocommerce-pdf-invoice' ) . get_post_meta( $order_id,'VAT Number',TRUE ), $content );
				} elseif ( get_post_meta( $order_id,'vat_number',TRUE ) ) {	
					$content = str_replace(	'[[PDFBILLINGVATNUMBER]]', '<br />' . __( 'VAT Number : ', 'woocommerce-pdf-invoice' ) . get_post_meta( $order_id,'vat_number',TRUE ), $content );
				} else {
					$content = str_replace(	'[[PDFBILLINGVATNUMBER]]', '', $content );	
				}
				
				$content = apply_filters( 'pdf_content_additional_content' , $content , $order_id );

				// WPML
				global $current_language;

				do_action( 'after_invoice_content', $current_language ); 
		
				return mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
			}

			function get_barcode_type() {
				$wc_order_barcodes_type = str_replace( 'code', '', get_option( 'wc_order_barcodes_type', 'code128' ) );

				$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();

				switch ( $wc_order_barcodes_type ) {
				    case '39':
				        return $generator::TYPE_CODE_39;
				        break;
				    case '93':
				        return $generator::TYPE_CODE_93;
				        break;
				    case '128':
				        return $generator::TYPE_CODE_128;
				        break;
				}
				
			}
			
			/**
			 * [get_woocommerce_invoice_terms description]
			 * @param  integer $page_id [description]
			 * @return [type]           [description]
			 */
			function get_woocommerce_invoice_terms( $page_id = 0, $order_id = 0 ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				$pdfregisteredname = $woocommerce_pdf_invoice_options['pdf_registered_name'];
				$pdfregaddress	   = $woocommerce_pdf_invoice_options['pdf_registered_address'];
				$pdfcompanynumber  = $woocommerce_pdf_invoice_options['pdf_company_number'];
				$pdftaxnumber 	   = $woocommerce_pdf_invoice_options['pdf_tax_number'];

				/**
				 * Filter the $page_id for reasons
				 */
				$page_id = apply_filters( 'pdf_invoice_terms_page_id', $page_id, $order_id );
								
				if ( $page_id == 0 ) 
					return;
				
				/** 
				 * Get terms template
				 * 
				 * Put your customized template in 
				 * wp-content/themes/YOUR_THEME/pdf_templates/terms-template.php
				 */
				$termstemplate 	= $this->get_pdf_template( 'terms-template.php' );
				
				// Buffer
				ob_start();
				
				require( $termstemplate );
	
				// Get contents
				$content = ob_get_clean();

				$id		 = $page_id; 
				$post 	 = get_post( $id );  
				$terms 	 = apply_filters( 'the_content', $post->post_content ); 
				
				$content = str_replace(	'[[TERMSTITLE]]', 				$post->post_title,  $content );
				$content = str_replace(	'[[TERMS]]', 					$terms, 			$content );
				$content = str_replace(	'[[PDFREGISTEREDNAME]]', 		$pdfregisteredname, $content );
				$content = str_replace(	'[[PDFREGISTEREDADDRESS]]', 	$pdfregaddress, 	$content );
				$content = str_replace(	'[[PDFCOMPANYNUMBER]]', 		$pdfcompanynumber, 	$content );
				$content = str_replace(	'[[PDFTAXNUMBER]]', 			$pdftaxnumber, 		$content ); 
				
				return $content;	
			}

			/** 
			 * Get pdf template
			 * 
			 * Put your customized template in 
			 * wp-content/themes/YOUR_THEME/pdf_templates/template.php
			 *
			 * Windows hosting fixes
			 */
			function get_pdf_template( $filename ) {

				$plugin_version     = str_replace('/classes/','/templates/',plugin_dir_path(__FILE__) ) . $filename;
				$plugin_version     = str_replace('\classes/','\templates\\',$plugin_version);

                $theme_version_file = get_stylesheet_directory() . '/pdf_templates/' . $filename;

				$pos = strpos( $plugin_version, ":\\" );
				if ( $pos === false ) {

					$pdftemplate 		= file_exists($theme_version_file) ? $theme_version_file : $plugin_version;

				} else {
					$theme_version_file = str_replace('/', '\\', $theme_version_file );
					$plugin_version		= str_replace('/', '\\', $plugin_version );
					$pdftemplate 		= file_exists($theme_version_file) ? $theme_version_file : $plugin_version;
					$pdftemplate		= str_replace('/', '\\', $pdftemplate );
				}

				return $pdftemplate;

			} // get_pdf_template

			/**
			 * Get the tem directory
			 */
		 	Public Static function get_pdf_temp() {

				// Set the temp directory
				$pdftemp = sys_get_temp_dir();

				$upload_dir =  wp_upload_dir();
                if ( file_exists( $upload_dir['basedir'] . '/woocommerce_pdf_invoice/index.html' ) ) {
    				$pdftemp = $upload_dir['basedir'] . '/woocommerce_pdf_invoice';

    				// Windows hosting check
					$pos = strpos( $pdftemp, ":\\" );
					if ( $pos === false ) {

					} else {
    					$pdftemp = str_replace('/', '\\', $pdftemp );
					}

                }

                return $pdftemp;

		 	}

			 /**
			  * Send a test PDF from the PDF Debugging settings
			  */
			public static function send_test_pdf() {
				 
				ob_start();

				include( PDFPLUGINPATH . "templates/pdftest.php" );
					
				$dompdf = new DOMPDF();
				$dompdf->load_html( $messagetext );
				$dompdf->set_paper( 'a4', 'portrait' );
				$dompdf->render();
						
				$attachments = WC_send_pdf::get_pdf_temp() . '/testpdf.pdf';
					
				ob_clean();
				// Write the PDF to the TMP directory		
				file_put_contents( $attachments, $dompdf->output() );
					
				$emailsubject 	= __( 'Test Email with PDF Attachment', 'woocommerce-pdf-invoice' );
				$emailbody 		= __( 'A PDF should be attached to this email to confirm that the PDF is being created and attached correctly', 'woocommerce-pdf-invoice' );
					
				wp_mail( sanitize_email( $_POST['pdfemailtest-emailaddress'] ), $emailsubject , $emailbody , $headers='', $attachments );
				 
			}

        }

    	$GLOBALS['WC_send_pdf'] = new WC_send_pdf();