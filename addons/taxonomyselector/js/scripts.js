jQuery(document).ready(function(){

	var original_html = jQuery('#wm_products_container').html();

	var fooXHR, fooCounter=0;
	
	jQuery('.tax_item a').click(function(){
	
		if(jQuery('.allow_ajax_selection').length > 0){
	
			var opts = {
			  lines: 20, // The number of lines to draw
			  length: 200, // The length of each line
			  width: 40, // The line thickness
			  radius: 3, // The radius of the inner circle
			  corners: 1, // Corner roundness (0..1)
			  rotate: 0, // The rotation offset
			  direction: 1, // 1: clockwise, -1: counterclockwise
			  color: '#fff', // #rgb or #rrggbb
			  speed: 1, // Rounds per second
			  trail: 0, // Afterglow percentage
			  shadow: true, // Whether to render a shadow
			  hwaccel: false, // Whether to use hardware acceleration
			  className: 'spinner', // The CSS class to assign to the spinner
			  zIndex: 2e9, // The z-index (defaults to 2000000000)
			  top: '10', // Top position relative to parent in px
			  left: 'auto' // Left position relative to parent in px
			};
	
			show_spinner('body_spin', opts);
		
			if (fooXHR) fooXHR.abort();
			
			var token = ++fooCounter;
		
			jQuery(this).toggleClass('active');
			
			var num_selected = jQuery(this).parent().parent().parent().find('.active').length;
			
			var num_selected_element = jQuery(this).parent().parent().parent().find('.number_selected');
			
			num_selected_element.html(num_selected);
			
			if(num_selected > 0){
				
				num_selected_element.css('display', 'inline-block');
				
			}else{
				
				num_selected_element.css('display', 'none');
				
			}
		
			var taxonomies = new Object();
			
			var i = 0;
			
			jQuery('.tax_item a.active').each(function(){
			
				var parent_tax = jQuery(this).attr('rel');
				
				if(taxonomies[parent_tax] instanceof Object == false){
					
					taxonomies[parent_tax] = new Object();
									
				}
				
				taxonomies[parent_tax][i] = jQuery(this).attr('alt');
				
				i++;
										
			});
			
			//var myJSONText = JSON.stringify(taxonomies);
		
			var data = {
				action: 'search_products',
				nonce: tpc_ts_variables.nonce,
				taxonomies: taxonomies
			};
			
			console.log(data);
			
			fooXHR = jQuery.ajax({
				type: "POST",
				url: options.ajaxurl,
				data: data
			}).done(function( response ) {
			
				if(response != ''){
			  	
			  		jQuery('#wm_products_container').html(response);
			  	
			  	}else{
				  	
				  	jQuery('#wm_products_container').html(original_html);
				  	
			  	}
			  	stop_spinner('body_spin');
			
			});
			
			
			return false;
			
		}
		
	});	
	
})