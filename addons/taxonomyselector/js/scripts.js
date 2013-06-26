jQuery(document).ready(function(){

	var fooXHR, fooCounter=0;
	
	jQuery('.tax_item a').click(function(){
	
		if (fooXHR) fooXHR.abort();
		
		var token = ++fooCounter;
	
		jQuery(this).toggleClass('active');
	
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
		  	
		  	jQuery('#wm_products_container').html(response);
		
		});
		
		
		return false;
		
	});	
	
})