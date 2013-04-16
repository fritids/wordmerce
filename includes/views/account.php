<div class="row-fluid">
	
	<div class="span2">
	
		<img data-src="<?php echo $data_src; ?>" src="<?php echo $img; ?>" />
	
	</div>
	
	<div class="span10">
		
		<h2><?php echo $name; ?></h2>
		
	</div>
	
</div>

<div class="row-fluid">
		
	<div class="span12">
		
		<h4><?php echo $email; ?></h4>
		
	</div>
	
</div>

<div class="row-fluid">
		
	<div class="span12">
		
		<h4>Order History</h4>
		
	</div>
	
	<?php if(is_array($orders)){ ?>
		
		<table class="table table-striped table-hover">
		
			<thead>
			
				<tr>
			
					<td>Product</td>
					<td>Date</td>
					<td>Status</td>
					
				</tr>
			
			</thead>
			
			<tbody>
		
				<?php foreach($orders as $order){ ?>
				
					<tr class="<?php echo $o->get_order_status_class($order->ID); ?>">
					
						<td>
						
							<?php echo $o->get_order_product($order->ID) ?>
						
						</td>
						
						<td>
						
							<?php echo $date = get_the_time('l, F j, Y @ G:i', $order->ID); ?>
						
						</td>
						
						<td>
						
							<?php echo $o->get_order_status($order->ID)
							
							?>
						
						</td>
					
					</tr>
				
				<?php } ?>
		
			</tbody>
		
		</table>
		
	<?php }else{ ?>
	
		<div class="span12 alert">
		
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		
			No orders found. You should buy something!
		
		</div>
	
	<?php } ?>
	
</div>