jQuery(document).ready(function(){
	
	jQuery('#resend_email_button').click(function(){ 
					
		var data = {
			action: 'send_purchase_receipt',
			order_id: jQuery(this).attr('data-id')
		};

		jQuery.post(ajaxurl, data, function(response) {
				
			jQuery('#resend_email_button').parent().append(response);
		
		});
	 
	});
	
	return false;
	
});