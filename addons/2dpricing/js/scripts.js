jQuery(document).ready(function(){
	
	jQuery('#wm_undo_last_upload').click(function(){
		
		var data = {
			action: 'wm_undo_last_upload',
			post_id: acf.post_id,
			update_id: jQuery(this).attr('href')
		};
	
		jQuery.post(ajaxurl, data, function(response) {
			update_update_id_and_save(response);
		});
		
		return false;
		
	});
	
	jQuery('.acf-file-value').on('change', function(){
	
		wm_process_csv();
		
	})
	
});

function wm_process_csv(){
	
	var data = {
		action: 'wm_process_csv',
		post_id: acf.post_id,
		csv: jQuery('input[name="fields[csv]"]').val()
	};
	
	jQuery.post(ajaxurl, data, function(response) {
		update_update_id_and_save(response);
	});
	
}

function update_update_id_and_save(response){
	
	jQuery('#wm_twod_update_id').val(response);
	jQuery('#publish').trigger('click');
	
}