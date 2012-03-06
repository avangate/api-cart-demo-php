		<input type="hidden" name="method" value="PAYPAL" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>/>
		<label>Email <input type="email" name="email" value="<?php echo $mBilling->Email; ?>" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>/> </label><br/>
		<input type="hidden" name="currency" value="<?php echo $c->getCurrency();?>"/>
		
		<div style="height:1px; line-height:1px; clear:both;margin-top:5em;">&nbsp;</div>
		<address style="font-size:0.8em;color:#999;font-weight:lighter;">
			Please verify that the email address is the same email address as for your PayPal account. Different email addresses will be subject to additional verification and may lead to delays in the processing of your order. To expedite your approval, please make sure the two email addresses are identical.<br/>
			It is also recommended to use a Verified PayPal account.
		</address>