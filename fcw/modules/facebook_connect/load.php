<?php

class facebook_connect{
	
	private $args;
	
	private $secret;
		
	public $appId;
	
	private $functions;
	
	public $user;
	
	public $return_val;
	
	private $facebook;
	
	public $user_profile;

	function facebook_connect($args, $functions){
	
		$this->__construct($args, $functions);
	
	}
	
	function __construct($args, $functions){
	
		global $paths;
	
		$this->args = $args;
						
		extract($this->args);
		
		$this->appId = $appId;
		
		$this->secret = $secret;
		
		$this->functions = $functions;
		
		$this->fb_connect();
			
	}
	
	private function fb_connect(){
	
		global $paths;
		
		require $paths['fcw_dir'] . '/modules/facebook_connect/sdk/src/facebook.php';
		
		$this->facebook = new Facebook(array(
			'appId'  => $this->appId,
			'secret' => $this->secret,
		));
		
		$this->user = $this->facebook->getUser();
		
		if ($this->user) {
		  try {
		  
		    if(array_key_exists('login', $this->functions)){
		
				$this->return_val = call_user_func($this->functions['login'], $this);
		
			}
			
			$this->user_profile = $this->facebook->api('/me');
			
		  } catch (FacebookApiException $e) {
		    error_log($e);
		    $this->user = null;
		  }
		}
		
		extract($this->args);
		
		if(array_key_exists('login', $this->functions)){ ?>
		
			<style type="text/css">
			
				#loading img{
					margin: 200px auto 0;
					display: block;
				}
				
				#loading{
					width: 100%;
					height: 100%;
					position: absolute;
					top: 0px;
					left: 0px;
					background: rgba(0, 0, 0, 0.7);
				}
				
				#loading p{
					margin: 50px auto 0;
					display: block;
					text-align: center;
					color: #fff;
					font-size: 20px;
				}
			
			</style>
			
		<?php } ?>
		
		<div id="loading">
    		<img src="<?php echo $paths['url'] .  $paths['fcw_path']; ?>/img/loading.gif" />
    		<p>Authenticating you with Facebook...</p>
    	</div>
		
		<div id="fb-root"></div>
		<script>
		  window.fbAsyncInit = function() {
		    // init the FB JS SDK
		    FB.init({
		      appId      : '<?php echo $appId; ?>',
		      channelUrl : '<?php echo $paths['url'] .  $paths['fcw_path']; ?>/modules/facebook/lib/channel.php',
		      status     : <?php echo $status; ?>,
		      cookie     : <?php echo $cookie; ?>,
		      xfbml      : <?php echo $xfbml; ?>,
		      frictionlessRequests	: <?php echo $frictionlessRequests; ?>
		    });
		
		    <?php if(array_key_exists('login', $this->functions)){ ?>
			    
			    FB.getLoginStatus(function(response) {
					if (response.status === 'connected') {
						jQuery('#loading').remove();
					} else if (response.status === 'not_authorized') {
						login();
					} else {
						login();
					}
				});
		    
		    <?php } ?>
		
		  };
		  
		  function login() {
			FB.login(function(response) {
				if (response.authResponse) {
					window.location.reload();
				} else {
					top.location.href="https://www.facebook.com/dialog/oauth/?client_id=<?php echo $appId; ?>&redirect_uri="+window.location.href+"&scope=email,publish_stream&redirect_uri=https://apps.facebook.com/<?php echo $appId; ?>/";
				}
			}, {scope:'<?php echo $scope; ?>'});
		   }
		  
		  (function(d, debug){
		     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement('script'); js.id = id; js.async = true;
		     js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
		     ref.parentNode.insertBefore(js, ref);
		   }(document, /*debug*/ false));
		</script>
		
		<?php
		
	}
	
}