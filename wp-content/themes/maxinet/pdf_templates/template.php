<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <style>

   body {
      font-family: "DejaVu Sans", "DejaVu Sans Mono", "DejaVu", sans-serif, monospace;
      font-size:11px;
    }
    @page { 
  		margin: 480px 50px 100px 50px;
  	} 
    #header { 
  		position: fixed; 
  		left: 0px; 
  		top: -460px; 
  		right: 0px; 
  		height: 480px; 
  		text-align: center;
  	} 
    #footer { 
  		position: fixed; 
  		left: 0px; 
  		bottom: -150px; 
  		right: 0px; 
  		height: 100px; 
  		font-size:11px; 
  		text-align: center;
  	} 
  	#content { 
  		font-size:10px;
  	}

    #logo img {
      max-width:340px;
    }

    .barcode {
      text-align:center;
      width: 50%;
    }
  </style> 
</head>
  <body> 
  <div id="header"> 
  <table table width="100%">
	<tr>
    	<td valign="top" width="50%" id="logo">[[PDFLOGO]]</td>
    	<td valign="top" width="50%" id="company-info">[[PDFCOMPANYNAME]]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;www.hotshot.nz<br />[[PDFCOMPANYDETAILS]]<br /></td>
	</tr>
  </table>
  <table table width="100%">
	<tr>
	   	<td width="20%" valign="top"><?php echo apply_filters( 'pdf_template_invoice_number_text', __( 'Invoice No. :', 'woocommerce-pdf-invoice' ) ); ?></td>
	    <td width="28%" valign="top">[[PDFINVOICENUM]]</td>
	   	<td width="17%" valign="top"><?php echo apply_filters( 'pdf_template_order_number_text', __( 'Order No. :', 'woocommerce-pdf-invoice' ) ); ?></td>
	    <td width="35%" valign="top">[[PDFORDERENUM]]</td>
	</tr>
	<tr>
	   	<td valign="top"><?php echo apply_filters( 'pdf_template_invoice_date_text', __( 'Invoice Date :', 'woocommerce-pdf-invoice' ) ); ?></td>
       	<td valign="top">[[PDFINVOICEDATE]]</td>
    	<td valign="top"><?php echo apply_filters( 'pdf_template_order_date_text', __( 'Order Date :', 'woocommerce-pdf-invoice' ) ); ?></td>
       	<td valign="top">[[PDFORDERDATE]]</td>
    </tr>
    
    <tr>
	   	<td valign="top"><?php echo apply_filters( 'pdf_template_payment_method_text', __( 'Subscription No. :', 'woocommerce-pdf-invoice' ) ); ?></td>
       	<td valign="top">[[PDFINVOICE_SubscriptionNo]]</td>
    	<td valign="top"><?php echo apply_filters( 'pdf_template_shipping_method_text', __( 'Billing Period :', 'woocommerce-pdf-invoice' ) ); ?></td>
        <td valign="top">[[BillingPeriod]]</td>
    </tr>
    
    <tr>   
    	<td valign="top" colspan="2">
    	<h3><?php echo apply_filters( 'pdf_template_billing_details_text', __( 'Billing Details', 'woocommerce-pdf-invoice' ) ); ?></h3>
		[[PDFBILLINGADDRESS]]<br />
        [[PDFBILLINGTEL]]<br />
        [[PDFBILLINGEMAIL]]
        [[PDFBILLINGVATNUMBER]]
    	</td>
    	<td valign="top" colspan="2">

    	</td>
    </tr>
  </table>
  </div> 
  <div id="footer"> 

    <div class="copyright"><?php echo apply_filters( 'pdf_template_registered_name_text', __( 'Registered Name : ', 'woocommerce-pdf-invoice' ) ); ?>[[PDFREGISTEREDNAME]]</div>
    <div class="copyright"><?php echo apply_filters( 'pdf_template_vat_number_text', __( '', 'woocommerce-pdf-invoice' ) ); ?>[[PDFTAXNUMBER]]</div>

  </div> 
  <div id="content">
	  [[ORDERINFOHEADER]]
    [[ORDERINFO]]
    [[PDFBARCODES]]
    
	<table table width="100%">
    	<tr>
        	<td width="60%" valign="top">
            [[PDFORDERNOTES]]
        	</td>
        	<td width="40%" valign="top" align="right">
            
            	<table width="100%">
                [[PDFORDERTOTALS]]
            	</table>
            
        	</td>
		</tr>
	</table>

  </div> 
</body> 
</html> 