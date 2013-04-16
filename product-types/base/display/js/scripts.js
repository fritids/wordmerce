

jQuery(document).ready(function(){

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
	
	jQuery(ele).show();
	
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