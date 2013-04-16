<?php

class ajax{

	function ajax($inscript, $function){
	
		$this->__construct($inscript, $function);
	
	}
	
	function __construct($inscript, $function){
	
		global $paths;
	
		if(!$inscript){ ?>
		
			<script>
			
		<?php } ?>
				
		$.ajaxSetup ({  
			cache: false  
		});  
		
		$.post(
			"<?php echo $paths['fcw_path']; ?>/modules/ajax/recieve.php", 
			    
			{ ajax_args: ajax_args, function: 'ajax_callback' },
    
			function(data){
				<?php echo $function; ?>(data);
        	}, 
    
        	"json"
        );
        
        <?php if(!$inscript){ ?>
		
			</script>
			
		<?php }
		
			
	}
	
}