jQuery('#payment-form input').keyup(function(){

	var v = jQuery(this).attr('data-validate');
	
	if(v == 'validateCardNumber'){
		
		var check = paymill.validateCardNumber(jQuery(this).val());
		
	}else if(v == 'validateCvc'){

		var check = paymill.validateCvc(jQuery('.card-cvc').val(), jQuery('.card-number').val());
		
	}else if(v == 'validateExpiry'){
		
		var check = paymill.validateExpiry(jQuery('.card-expiry-month').val(), jQuery('.card-expiry-year').val());
		
	}else if(v =='none'){
		
		var check = true;
		
	}else{
		
		var check = false;
		
	}
	
	if(!check){
		
		jQuery(this).parent().removeClass('success').addClass('error');
		
	}else{
		
		jQuery(this).parent().removeClass('error').addClass('success');
		
	}
	
	jQuery('#payment-form input').each(function(){
		
		var payment_ok = true;
		
		if(!jQuery(this).parent().hasClass('success')){
			
			payment_ok = false;
		}

		if(payment_ok){
			
			jQuery('a[data-targets="#checkout_payment"] i').removeClass('icon-remove').addClass('icon-ok');
			
			check_checkout();
			
		}else{
			
			jQuery('a[data-targets="#checkout_payment"] i').removeClass('icon-ok').addClass('icon-remove');
			
			check_checkout();
			
		}
		
	});

})

jQuery(document).ready(function(){
	
	Hook.register(
	  'payment_process',
	  function ( args ) {
	    
	    process_cc();
	    
	  }
	);

});

function process_cc(){

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
	
	jQuery('#checkout_now_button').addClass('disabled');

    paymill.createToken({
      number: jQuery('.card-number').val(),
      exp_month: jQuery('.card-expiry-month').val(),
      exp_year: jQuery('.card-expiry-year').val(),
      cvc: jQuery('.card-cvc').val(), 
      amount_int: parseInt(simpleCart.grandTotal()*100),
      currency: jQuery('.card-currency').val(),
      cardholder: jQuery('.card-holdername').val()
    }, PaymillResponseHandler); 

    return false;
	
}

function PaymillResponseHandler(error, result) {
  
  if (error) {

    show_error(error.apierror);

    jQuery(".submit-button").removeClass("disabled");
    
    stop_spinner('body_spin');

  }else{
    
    var data = {
			action: 'process_payment',
			token: result.token,
			amount: parseInt(simpleCart.grandTotal()*100),
			currency: jQuery('.card-currency').val(),
			description: jQuery(document).attr('title'),
			wordmerce_nonce : base_options.wordmerce_nonce
		};
	
		jQuery.post(base_options.aja_url, data, function(response) {
			
			console.log(response);
			
			simpleCart.empty();
				
			window.location = settings.confirm+'/'+response
				
		});
    
  }
  
}