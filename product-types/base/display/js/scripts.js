jQuery(document).ready(function(){

	var isClosed = true;
	jQuery('a.cart').click(function(){
	if(isClosed)
	{
		jQuery(this).addClass("active");
		jQuery(this).removeClass("hover");
		jQuery('form#myForm').slideDown('slow', function(){
			
			if(jQuery(this).parent().hasClass('float-bottom-left') || jQuery(this).parent().hasClass('float-bottom-right')){
				jQuery(this).animate({'top': '-'+jQuery('form#myForm').css('height')});
			}
			
		});
		
		isClosed = false;
	}
	else
	{
		jQuery(this).removeClass("active");
		jQuery('form#myForm').slideUp('slow');
		isClosed = true;
	}
	});
	
	
	jQuery(".pane .delete").click(function(){
		jQuery(this).parents(".pane").animate({ opacity: 'hide' }, "slow");
	});

	simpleCart({
	    checkout: { 
	        type: "SendForm" , 
	        url: "http://example.com/your/custom/checkout/url" 
	    },
	    currency: "GBP",
	    cartStyle: "table",
	    shippingFlatRate: 0,
	    cartColumns: [
	        { attr: "name" , label: "Name" } ,
	        { attr: "price" , label: "Price", view: 'currency' } ,
	        { view: "decrement" , label: false , text: "-" } ,
	        { attr: "quantity" , label: "Qty" } ,
	        { view: "increment" , label: false , text: "+" } ,
	        { attr: "total" , label: "SubTotal", view: 'currency' } ,
	        { view: "remove" , text: "Remove" , label: false }
	    ]
	});
	
	simpleCart.bind( "afterAdd" , function( item ){
	
		var id = new Date().getTime();
	
		var message = '<div id="'+id+'" class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+item.get("name") + ' has been added.</div>';
	
		jQuery('.item_add').parent().parent().append(message).delay(2000).queue(function(){
	
			jQuery('#'+id).fadeOut();
	
		});
	
	});
	
	simpleCart.bind('beforeAdd',function( item ){
		
		jQuery('input:checkbox.add_to_product').each(function () {
			
			if(this.checked){
			
				var newprice = parseInt(item.get('price')) + parseInt(jQuery(this).attr('data-price'));
			
				item.price(newprice);
				
				var newname = item.get('name') + ' + ' + jQuery(this).attr('data-name');
			
				item.set('name', newname);
			
			}

		});
		
		jQuery('select.add_to_product').each(function () {
						
			if(this.options[this.selectedIndex].getAttribute("data-price") != ''){
				
				var newprice = parseInt(item.get('price')) + parseInt(this.options[this.selectedIndex].getAttribute("data-price"));

				item.price(newprice); 
				
			}
			
			if(this.options[this.selectedIndex].getAttribute("data-name") != ''){
				
				var newname = item.get('name') + ' + ' + this.options[this.selectedIndex].getAttribute("data-name");
			
				item.set('name', newname); console.log(newname);
				
			}
						
		});
		
	});
	
	simpleCart.bind( 'ready' , function(){
	
		update_cart();

	});
	
	simpleCart.bind( 'afterSave' , function(){
	
		update_cart();

	});

	jQuery('.tooltipthis').tooltip();

	jQuery(document).on('add_order_error', function(e, eventInfo) { 
	
		var data = {
			action: 'is_logged_in'
		};
	
		jQuery.post(base_options.aja_url, data, function(response) {
			
			if(response == '1'){
					
				alert('logged in');
					
			}else{
			
				jQuery(document).trigger('log_in');
				
			}
				
		});
	 
	});
	
	jQuery(document).on('log_out', function(e){
	
		login_loading();
	
		var data = {
			action: 'log_out'
		};
	
		jQuery.post(base_options.aja_url, data, function(response) {
			
			jQuery(document).trigger('refresh_window');
				
		});
	
	});
	
	jQuery(document).on('log_in', function(e, eventInfo) { 
					
		var data = {
			action: 'WM_log_in',
			id: 1
		};

		jQuery.post(base_options.aja_url, data, function(response) {
				
			jQuery('body').append(response);
			
			jQuery('#log_in_modal').addClass('modal').modal('show');
		
		});
	 
	});
	
	jQuery(document).on('signin_login', function(e, user) { 
	
		login_loading();
				
		if(user.username == ''){
			
			user.username = user.name;
			
		}
		
		if((user.username == '' || user.password == '') || (user.addthis_signature == '')){
			
			show_error('Please fill in the fields');
			
			login_reset();
			
			return false;
			
		}
	
		var data = {
			action: 'signin_login',
			user: user,
			wordmerce_nonce : base_options.wordmerce_nonce
		};
	
		jQuery.post(base_options.aja_url, data, function(response) {
				
			login_reset();
			
			if(response.error){
			
				if(response.error.trigger){
					
					jQuery(document).trigger(response.error.trigger, response.error.trigger_args);
					
				}
				
				show_error(response.error, '.login_alert');
											
			}else{
				
				jQuery('#log_in_modal').remove();
				jQuery('.modal-backdrop').remove();
				
				check_for_next();
		
			}
		
		}, "json");
	
	});
	
	jQuery(document).on('refresh_window', function(e) { 
	
		window.location = window.location;
	
	});
	
	jQuery(document).on('ask_for_email', function(e, user){
	
		window.user = user;
			
		body = '<p>Please enter your email address: <input type="text" id="email_address_add" /></p><p><input type="button" class="btn btn-primary login_button" id="submit_email_address" value="Continue" /></p>';
	
		show_modal_mini(body);
	
	});
	
	jQuery('#logout_link').click(function(){
		
		jQuery(document).trigger('log_out');
		
		return false;
		
	});
	
	jQuery('#checkout_progress li a').click(function(){
		
		jQuery('#checkout_progress li.active').removeClass('active');
		
		jQuery(this).parent().addClass('active');
		
		var collapse_this_one = jQuery(this).attr('data-targets');

		jQuery('.collapses').each(function(){
				
			jQuery(this).slideUp();
		
		});
		
		jQuery(collapse_this_one).slideDown();
		
		return false;
		
	});
	
	jQuery('#customer_address input[type="text"], #customer_address select').change(function(){
	
		if(jQuery('#user_id').val() != ''){
		
			var data = {
				action: 'customer_add_data_ajax',
				key: jQuery(this).attr('id'),
				value: jQuery(this).val(),
				id: jQuery('#user_id').val(),
				wordmerce_nonce : base_options.wordmerce_nonce
			};
			
			var input = jQuery(this);
	
			jQuery.post(base_options.aja_url, data, function(response) {
					
				if(response == 'yup'){
				
					input.prev().remove();
					
					input.before('<i class="icon-ok-sign pull-left"></i>');
					
				}else{
					
					input.prev().remove();
					
					input.before('<i class="icon-remove-sign pull-left"></i>');
					
				}
			
			});
		
		}
		
	});
	
	jQuery('#customer_address input[type="text"], #customer_address select').change(function(){
		
		var shipping_ok = true;
		
		if(!jQuery(this).prev().hasClass('icon-ok-sign')){
			
			shipping_ok = false;
		}

		if(shipping_ok){
			
			jQuery('a[data-targets="#checkout_shipping"] i').removeClass('icon-remove').addClass('icon-ok');
			
			check_checkout();
			
		}else{
			
			jQuery('a[data-targets="#checkout_shipping"] i').removeClass('icon-ok').addClass('icon-remove');
			
		}
		
	});
	
	jQuery('#checkout_now_button').click(function(){
		
		if(!jQuery(this).hasClass('disabled')){
		
			Hook.call( 'payment_process', [] );
		
		}else{
			
			return false;
		
		}
		
	});
	
	jQuery('.item_Quantity').keyup(function(){
	
		disable_cart_stuff();
    
	    var input=parseInt(jQuery(this).val());
	    
	    var max = jQuery(this).attr('data-max');
	    
	    if(parseInt(input) !== input || input<1 || input>max){
		
		    show_error('Please enter a quantity between 1 and '+max);
		    
		    console.log('error');
		
		}else{
			
			enable_cart_stuff();
			
		}
		
		return;
	
	});
	
});

jQuery('.collapses').each(function(){
		
	jQuery(this).slideUp();

});

jQuery('#checkout_basket').slideDown();

jQuery('#checkout_login').live('click', function(){

	jQuery(document).trigger('log_in');
	
	return false;

});

jQuery('#login_register').live('click', function(){

	var user = {
		username: jQuery('#inputUser').val(),
		password: jQuery('#inputPassword').val(),
		thumbnailURL: '',
		service: 'native',
		id: '',
		new_user: jQuery('#new_user').attr('checked')
	};

	jQuery(document).trigger('signin_login', user);
	
	return false;

});

jQuery('#submit_email_address').live('click', function(){
		
	var user_updated = window.user;
	
	user_updated.email = jQuery('#email_address_add').val();
	
	jQuery(document).trigger('signin_login', user_updated);
	
	return false;
	
});

function is_logged_in(){

	if(jQuery.cookie('WM_LOGGED_IN') != undefined){
		return jQuery.cookie('WM_LOGGED_IN');
	}else{
		return false;
	}
	
}

function login_loading(){
	
	jQuery('.login_button').button('loading')
	
}

function login_reset(){
	
	jQuery('.login_button').button('reset')
	
}

function show_modal(title, body, close){

	var modal = '<div class="modal hide" id="custom_modal"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">x</button><h3>'+title+'</h3></div><div class="modal-body">'+body+'</div></div>';
	
	jQuery('body').append(modal);
	
	jQuery('.modal').modal('hide');
	
	jQuery('#custom_modal').modal('show');
	
}

function show_modal_mini(body){

	var id = randomString(10);

	var modal = '<div class="modal hide" id="'+id+'"><div class="modal-body">'+body+'</div><div class="modal-footer"></div></div>';
	
	jQuery('body').append(modal);
	
	jQuery('.modal').modal('hide');
	
	jQuery('#'+id+'').modal('show');
	
}

function check_for_next(){
	
	if(last_function != ''){
		
		jQuery(document).trigger(last_function, last_functions_args);
		
		last_function = '';
		
		last_functions_args = '';
		
	}	
	
}

function show_error(error, ele){
	
	if(!ele){
		ele = '.login_alert';
	}
	
	jQuery(ele + ' #error_message').html(error);
	
	jQuery(ele).show().delay(6000).slideUp();
		
}

function randomString(len, charSet) {
    charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    var randomString = '';
    for (var i = 0; i < len; i++) {
    	var randomPoz = Math.floor(Math.random() * charSet.length);
    	randomString += charSet.substring(randomPoz,randomPoz+1);
    }
    return randomString;
}

function show_spinner(target){

	jQuery('#'+target).stop().find('.spinner').remove();

	var opts = {
	  lines: 10, // The number of lines to draw
	  length: 8, // The length of each line
	  width: 2, // The line thickness
	  radius: 3, // The radius of the inner circle
	  corners: 1, // Corner roundness (0..1)
	  rotate: 0, // The rotation offset
	  direction: 1, // 1: clockwise, -1: counterclockwise
	  color: '#000', // #rgb or #rrggbb
	  speed: 1, // Rounds per second
	  trail: 60, // Afterglow percentage
	  shadow: false, // Whether to render a shadow
	  hwaccel: false, // Whether to use hardware acceleration
	  className: 'spinner', // The CSS class to assign to the spinner
	  zIndex: 2e9, // The z-index (defaults to 2000000000)
	  top: 'auto', // Top position relative to parent in px
	  left: 'auto' // Left position relative to parent in px
	};
	var target = document.getElementById(target);
	var spinner = new Spinner(opts).spin(target);
	
}

function stop_spinner(target){
	
	jQuery('#'+target).stop().find('.spinner').remove();
	
}

function check_checkout(){
	
	jQuery('#checkout_progress li a').each(function(){
		
		if(jQuery(this).find('i').hasClass('icon-remove')){
			
			var checkout_ok = false;
		}else{
			
			var checkout_ok = true;
			
		}

		if(checkout_ok){
			
			jQuery('#checkout_now_button').removeClass('disabled');
			
		}else{
			
			jQuery('#checkout_now_button').addClass('disabled');
			
		}
		
	});
	
}

var fooXHR, fooCounter=0;

function update_cart(){

	disable_cart_stuff();
	
	if (fooXHR) fooXHR.abort();
	
	var token = ++fooCounter;

	var items = {};
	
	var title = '';

	simpleCart.each(function(item){ 
						
		items[item.get('name')] = {
			"quantity": item.quantity(), 
			"price": simpleCart.toCurrency( item.price()),
			"total": simpleCart.toCurrency( item.total()),
			"id": item.get('baseid')
		};
					
		title += item.get('name') + '(' + item.quantity() + ')' + ' ';
	
	});

	var data = {
		action: 'wm_update_order',
		products: items,
		title: title,
		status: 1,
		total: simpleCart.toCurrency( simpleCart.grandTotal() ),
		wordmerce_nonce : base_options.wordmerce_nonce,
		basket_id: base_options.basket_id,
		xhrFields: {
			withCredentials: true
		}
		
	};
	
	if(title != ''){
		
		fooXHR = jQuery.post(base_options.aja_url, data, function(response) {
			
				console.log(response);
		
			if(response == 'refresh'){
				
				window.location = window.location;
				
			}else if(response.search("over_stock") >= 0){
			
				var n = response.split('||');
								
				var remove_this = simpleCart.find( n[1] );
				
				remove_this.remove();
				
				show_error('There is not enough stock to add that to your basket. Please refresh the page and try again.');
				
			}
			
			enable_cart_stuff();
		});
	
	}else{
		
		enable_cart_stuff();
		
	}
		
}

function disable_cart_stuff(){
	
	jQuery('.simpleCart_decrement, .simpleCart_increment, .simpleCart_empty, .item_add').hide();
	
}

function enable_cart_stuff(){
	
	jQuery('.simpleCart_decrement, .simpleCart_increment, .simpleCart_empty, .item_add').show();
	
}

function isNumber(value) {
    if ((undefined === value) || (null === value)) {
        return false;
    }
    if (typeof value == 'number') {
        return true;
    }
    return !isNaN(value - 0);
}