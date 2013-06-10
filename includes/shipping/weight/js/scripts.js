jQuery(document).ready(function() {
	
	jQuery('.del_shipping').live('click', function(){
		jQuery(this).parent().parent().fadeOut('slow', function(){
			jQuery(this).remove();
		});
	});

	jQuery('#add_uk_price_point').click(function(){
		if(jQuery('#uk_weight_new').val() != '' && jQuery('#uk_price_new').val() != ''){
			jQuery('tr#uk_head').after('<tr class=""><td id="">'+jQuery('#uk_weight_new').val()+tpc_variables.weight_unit+'</td><td id="">'+tpc_variables.currency_symbol+'<input name="shipping_uk['+jQuery('#uk_weight_new').val()+']" value="'+jQuery('#uk_price_new').val()+'" type="text" /></td><td><input type="checkbox" class="del_shipping" name="checkbox[]" value="'+jQuery('#uk_weight_new').val()+'"/></td></tr>');
		}else{
			alert('Please enter a weight and a corresponding price');
		}
		return false;
	});

	jQuery('#add_eur_price_point').click(function(){
		if(jQuery('#eur_weight_new').val() != '' && jQuery('#eur_price_new').val() != ''){
			jQuery('tr#eur_head').after('<tr class=""><td id="">'+jQuery('#eur_weight_new').val()+tpc_variables.weight_unit+'</td><td id="">'+tpc_variables.currency_symbol+'<input name="shipping_eur['+jQuery('#eur_weight_new').val()+']" value="'+jQuery('#eur_price_new').val()+'" type="text" /></td><td><input type="checkbox" class="del_shipping" name="checkbox[]" value="'+jQuery('#eur_weight_new').val()+'"/></td></tr>');
		}else{
			alert('Please enter a weight and a corresponding price');
		}
		return false;
	});

	jQuery('#add_row_price_point').click(function(){
		if(jQuery('#row_weight_new').val() != '' && jQuery('#row_price_new').val() != ''){
			jQuery('tr#row_head').after('<tr class=""><td id="">'+jQuery('#row_weight_new').val()+tpc_variables.weight_unit+'</td><td id="">'+tpc_variables.currency_symbol+'<input name="shipping_row['+jQuery('#row_weight_new').val()+']" value="'+jQuery('#row_price_new').val()+'" type="text" /></td><td><input type="checkbox" class="del_shipping" name="checkbox[]" value="'+jQuery('#row_weight_new').val()+'"/></td></tr>');
		}else{
			alert('Please enter a weight and a corresponding price');
		}
		return false;
	});
	
	jQuery('#inputCountry').live('change', function(){

		calculate_shipping();
		
		check_checkout();

	});
	
	simpleCart.ready(function(){
	
		//calculate_shipping();
		
		//check_checkout();
	
	});
	
	simpleCart.bind( 'update' , function(){
	
		calculate_shipping();
			
	});
	
});

function calculate_shipping(){

	show_spinner('countries_spin');

	if(jQuery('details_country').length === 0){
		//return false;
	}
	
	var items = {};
	
	var i = 0;
	
	simpleCart.each(function(item){ 
			
		items[i] = {
				"quantity": item.quantity(), 
				"price": simpleCart.toCurrency( item.price()),
				"total": simpleCart.toCurrency( item.total()),
				"id": item.get('baseid')
			};
	
			i++;
	
	});
	
	if(i > 0){

		jQuery.ajax({
	      type:'POST',
	      data:{
	      	action:'calculate_shipping',
	      	cc: jQuery('#inputCountry').val(),
	      	ids: items,
	      	nonce: tpc_variables.nonce
	      },
	      url: tpc_variables.admin,
	      success: function(value) {
	      
	      	if(value != ''){
	
		      simpleCart.shipping(function(){
			  	return value;
			  });
	        
			  //simpleCart.update();
			  
			  jQuery('.simpleCart_shipping').html(simpleCart.toCurrency(value));
			  
			  jQuery('.simpleCart_grandTotal').html(simpleCart.toCurrency(simpleCart.grandTotal()));
			  
			  stop_spinner('countries_spin');
	
			  }else{
				  
				  //show_error('<p>There was a problem calculating your shipping.</p><p>Please ensure you have either logged in or have a country selected in the checkout.</p>');
				  
			  }
	
	      }
	    });

	 }
	   
}