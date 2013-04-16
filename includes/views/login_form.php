<div class="<?php echo ($modal ? 'modal hide': ''); ?>" id="log_in_modal">
  <div class="modal-header">
  	<?php if($modal){ ?>
    	<button type="button" class="close" data-dismiss="modal">x</button>
    <?php } ?>
    <h3>Please log in or register to continue</h3>
  </div>
  <div class="modal-body">
  
  	<div class="login_alert alert alert-error hide">
	  	<button type="button" class="close" data-dismiss="alert">&times;</button>
	  	<span id="error_message"></span>
  </div>
    
	<form class="form-horizontal">
	  <div class="control-group">
	    <label class="control-label" for="inputUser">Username</label>
	    <div class="controls">
	      <input type="text" id="inputUser" placeholder="Username">
	    </div>
	  </div>
	  <div class="control-group">
	    <label class="control-label" for="inputPassword">Password</label>
	    <div class="controls">
	      <input type="password" id="inputPassword" placeholder="Password">
	    </div>
	  </div>
	  <div class="control-group">
	    <div class="controls">
	      <label class="checkbox">
	        <input type="checkbox" name="optionsRadios" value="new" id="new_user">Register a new account with these credentials
	      </label>
	    </div>
	  </div>
	  <div class="control-group">
	    <div class="controls">
	      <button type="submit" id="login_register" data-loading-text="Loading..." class="btn login_button">Submit</button>
	    </div>
	  </div>
	</form>
    
  </div>
  <div class="modal-footer">
  	<div class="addthis_toolbox">
	<a class="addthis_login_facebook"></a>
	<a class="addthis_login_twitter"></a>
	<a class="addthis_login_google"></a>
	</div>
  </div>
</div>