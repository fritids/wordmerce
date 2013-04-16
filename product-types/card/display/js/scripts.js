jQuery(document).ready(function($) {

	var text_present = false;

	jQuery('a.edit_button').click(function(){
	
		if(!text_present){
		
			jQuery('#the_image').append('<div id="the_text"><div>'+options.initial_text+'</div></div>');
			
			jQuery( "#the_text" ).draggable({ containment: "#the_image", scroll: false });
		
			text_present = true;
		
		}
		
		jQuery('#confirmation').slideUp();
		
		jQuery('#customisation_options').slideToggle();
		
		jQuery('a.buy_now').fadeIn();
		
		return false;
		
	});		
	
	jQuery('#your_text').keyup(function(){
		jQuery( "#the_text" ).html(jQuery(this).val().replace(/\n/g, '<br>'));
	});
	
	jQuery("#font_size_slider").slider({
      value:20,
      min: 0,
      max: 100,
      step: 1,
      slide: function( event, ui ) {
        jQuery( "#the_text" ).css( 'font-size', ui.value ).css( 'line-height', (ui.value*1.5)+'px' );
      }
    }); 

	jQuery('.my-color-field').iris({
		hide: false,
	    change: function(event, ui) {
	        // event = standard jQuery event, produced by whichever control was changed.
	        // ui = standard jQuery UI object, with a color member containing a Color.js object
	
	        // change the headline color
	        jQuery(this).css( 'color', ui.color.toString());
	        jQuery( "#the_text" ).css( 'color', ui.color.toString() )
	    }
	});
	
	jQuery('#preview_link').click(function(){
	
		jQuery('#loading_preview').fadeIn();
	
		var image = jQuery('#preview_image');
		
		var text = jQuery('#the_text');
		
		var font_size = text.css('font-size').replace('px', '') * 72 / 96;
		
		var color = text.css('color');
		
		var rgb = color.match(/\d+/g);
		
		var text_top = text.css('top');
		
		if(text_top == 'auto'){
			
			text_top = 0;
			
		}
		
		var lines = jQuery("#your_text").val().split("\n");  
		
		var no_lines = lines.length;
			
		var data = {
			action: 'add_text_to_image',
			id: image.attr('rel'),
			text: jQuery('#your_text').val(),
			//top: parseInt(text_top)+(parseInt(text.css('line-height'))/no_lines),
			top: parseInt(text_top)+((parseInt(text.css('line-height'))-parseInt(text.css('font-size')))*2),
			left: text.css('left'),
			r: rgb[0],
			g: rgb[1],
			b: rgb[2],
			font_size: font_size,
			angle: '0',
			center: 'no',
			width: 200,
			height: 200,
			font: jQuery('#fontSelect > span').html(),
			//color: text.css('color')
		};
	
		jQuery.post(options.aja_url, data, function(response) {
			jQuery('#preview_rendered_image').html('<img src="' + response + '" />');
			jQuery('#loading_preview').fadeOut();
		});
		
		return false;
		
	});
	
	jQuery('a.buy_now').click(function(){
	
		jQuery('#final_preview').attr('src', options.loading_img).css('width', 'auto');
		
		jQuery('#loading_status').html('Generating final preview...');
	
		jQuery('#customisation_options').slideUp();
		
		jQuery('#confirmation').slideDown();
	
		var image = jQuery('#preview_image');
		
		var text = jQuery('#the_text');
		
		var font_size = text.css('font-size').replace('px', '') * 72 / 96;
		
		var color = text.css('color');
		
		var rgb = color.match(/\d+/g);
		
		var text_top = text.css('top');
		
		if(text_top == 'auto'){
			
			text_top = 0;
			
		}
		
		var lines = jQuery("#your_text").val().split("\n");  
		
		var no_lines = lines.length;
			
		var data = {
			action: 'add_text_to_image',
			id: image.attr('rel'),
			text: jQuery('#your_text').val(),
			sec_text: options.sample_text,
			//top: parseInt(text_top)+(parseInt(text.css('line-height'))/no_lines),
			top: parseInt(text_top)+((parseInt(text.css('line-height'))-parseInt(text.css('font-size')))*2),
			left: text.css('left'),
			r: rgb[0],
			g: rgb[1],
			b: rgb[2],
			font_size: font_size,
			angle: '0',
			center: 'no',
			//width: 200,
			//height: 200,
			font: jQuery('#fontSelect > span').html()
			//color: text.css('color')
		};
	
		jQuery.post(options.aja_url, data, function(response) {
			jQuery('#final_preview').attr('src', response).css('width', '100%');
			jQuery('#loading_status').html('');
		});
		
		return false;
		
	});
	
	jQuery(document).on('confirm_order', function(e, eventInfo) { 
	
		if(is_valid_phone_number(jQuery('#send_to').val())) {
							
			if(is_logged_in()){

				jQuery('#final_preview').attr('src', options.loading_img).css('width', 'auto');
				
				jQuery('#loading_status').html('Processing your order. Please wait...');
			
				jQuery('#customisation_options').slideUp();
				
				jQuery('#confirmation').slideDown();
			
				var image = jQuery('#preview_image');
				
				var text = jQuery('#the_text');
				
				var font_size = text.css('font-size').replace('px', '') * 72 / 96;
				
				var color = text.css('color');
				
				var rgb = color.match(/\d+/g);
				
				var text_top = text.css('top');
				
				if(text_top == 'auto'){
					
					text_top = 0;
					
				}
				
				var lines = jQuery("#your_text").val().split("\n");  
				
				var no_lines = lines.length;
				
				var data = {
					action: 'buy_card',
					id: image.attr('rel'),
					text: jQuery('#your_text').val(),
					top: parseInt(text_top)+(parseInt(text.css('line-height'))/no_lines),
					left: text.css('left'),
					r: rgb[0],
					g: rgb[1],
					b: rgb[2],
					font_size: font_size,
					angle: '0',
					center: 'no',
					//width: 200,
					//height: 200,
					font: jQuery('#fontSelect > span').html(),
					card_id: jQuery('.confirm').attr('href'),
					user: is_logged_in(),
					number: jQuery('#send_to').val()
					//color: text.css('color')
				};
			
				jQuery.post(options.aja_url, data, function(response) {
					
					if(response != 'error'){
						jQuery('#loading_status').html('');
						window.location = response;
					}else{
						jQuery(document).trigger('add_order_error');
						jQuery('#loading_status').html('');
					}
				});
			
			}else{
			
				last_function = 'confirm_order';
				
				last_function_args = '';
				
				jQuery(document).trigger('log_in');
				
			}
			
		};
		
		return false;
		
	});
	
	jQuery('.confirm').click(function(){
	
		jQuery(document).trigger('confirm_order');
		
		return false;
	
	});
		
});

function is_valid_phone_number(myTelNo){
	
	if (!checkUKTelephone (myTelNo)) {
		alert (telNumberErrors[telNumberErrorNo]);
		return false;
	}else {
		return true;
	}
	
}