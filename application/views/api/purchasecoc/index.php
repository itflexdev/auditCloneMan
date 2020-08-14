<form class="form" method="post">
	<input id="merchant_id" name="merchant_id" value="<?php echo $this->config->item('paymentid'); ?>" type="hidden">
	<input id="merchant_key" name="merchant_key" value="<?php echo $this->config->item('paymentkey'); ?>" type="hidden">
	<input id="return_url" name="return_url" value="<?php echo base_url().'webservice/services/purchasecocreturn'; ?>" type="hidden">
	<input id="cancel_url" name="cancel_url" value="<?php echo base_url().'webservice/services/purchasecoccancel'; ?>" type="hidden">
	<input id="notify_url" name="notify_url" value="<?php echo base_url().'webservice/services/purchasecocnotify'; ?>" type="hidden">
	
	<input id="name_first" name="name_first" value="<?php echo $plumber['name']; ?>" type="hidden">
	<input id="name_last" name="name_last" value="<?php echo $plumber['surname']; ?>" type="hidden">
	<input id="email_address" name="email_address" value="<?php echo $plumber['email']; ?>" type="hidden">
	
	<input name="amount" value="<?php echo $post['amount']; ?>" type="hidden">
	<input name="item_name" value="Coc Purchase" type="hidden">
	<input name="item_description" value="coc" type="hidden">
	<input name="payment_method" value="cc" type="hidden">
	
	<input type="hidden" name="custom_str1" name="<?php echo json_encode($post['customdata']); ?>">
</form>
