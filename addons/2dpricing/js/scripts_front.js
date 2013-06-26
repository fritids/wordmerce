jQuery(document).ready(function(){

	var fooXHR, fooCounter=0;
	
	jQuery('.item_add').attr('disabled', 'disabled');

	jQuery('#wm_width, #wm_height').on('keyup', function(){
	
		if (fooXHR) fooXHR.abort();
		
		var token = ++fooCounter;
	
		if(jQuery('#wm_height').val() != '' && jQuery('#wm_width').val() != ''){

			show_spinner('2dspin');
			
			var data = {
				action: 'wm_twod_price',
				id: jQuery(this).attr('alt'),
				width: jQuery('#wm_width').val(),
				height: jQuery('#wm_height').val()
			};
		
			fooXHR = jQuery.post(options.ajaxurl, data, function(response) {
				if (token != fooCounter) return;
				if(response != '0'){
					jQuery('.updated_price span').html('&pound;'+response);
					jQuery('.item_add').removeAttr('disabled');
					jQuery('.item_name.hide .name_text_addon').text(' ' + jQuery('#wm_width').val() + ' x ' + jQuery('#wm_height').val());
				}else{
					jQuery('.updated_price span').html('Item not available in this size');
					jQuery('.item_add').attr('disabled', 'disabled');
				}
				stop_spinner('2dspin');
			});
		
		}
		
	});
		
});