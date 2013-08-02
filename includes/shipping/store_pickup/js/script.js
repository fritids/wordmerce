jQuery(document).ready(function(){
	
	jQuery('select.store_pickup').change(function(){
	
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

		if(jQuery(this).val() != ''){
				
			var data = {
				action: 'store_pickup',
				wm_location: jQuery(this).val()
			};
		
			jQuery.post(base_options.aja_url, data, function(response) {
				
				simpleCart.shipping(function(){
					return 0;
				});
				
				jQuery('.simpleCart_shipping').html(simpleCart.toCurrency(simpleCart.shipping()));
			
				jQuery('.simpleCart_grandTotal').html(simpleCart.toCurrency(simpleCart.grandTotal()));
				
				stop_spinner('body_spin');
					
			});
		
		}else{
		
			var data = {
				action: 'store_pickup',
				wm_location: ''
			};
		
			jQuery.post(base_options.aja_url, data, function(response) {
				
				calculate_shipping();
				
				jQuery('.simpleCart_shipping').html(simpleCart.toCurrency(simpleCart.shipping()));
			
				jQuery('.simpleCart_grandTotal').html(simpleCart.toCurrency(simpleCart.grandTotal()));

				stop_spinner('body_spin');
					
			});
			
		}
					
		
	});
	
});