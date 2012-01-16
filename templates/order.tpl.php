<?php include ('templates/locale.tpl.php') ?>
<?php 
if (isset($refNo) && !is_null($refNo)) {
?>
		<div> Order status : <?php echo $status ?> </div>
		<div> Order Reference number : #<?php echo $refNo ?> </div>
		<?php echo is_null($msg) ? '<div>' . $msg . '</div>' : '' ; ?>
<?php
}
?>
<form method="post">
	<div style="float:left;min-width:600px">
		<ul style="list-style: none;">
<?php $i = 0;foreach ($cartProducts as $iKey => $prod) { $i++?>
		<li class="prod_details <?php echo $i%2 ? 'even' : 'odd'?>">
			<img src="<?php echo $prod->ProductImage ?>" style="float:left;max-width:80px" />
			<p>Product Name: <strong><?php echo $prod->ProductName ?> <?php if (strlen($prod->ProductVersion)>0) echo 'v.' . $prod->ProductVersion; ?></strong></p>
			<p style="margin-left:20px; font-size: 0.9em">
				<span><?php echo $prod->ShortDescription ?></span><br/>
				<span>Quantity: <?php echo getCartProductQuantity($prod->ProductId);?></span><br/>
				<span>Price: <?php echo getCartProductPrice($prod->ProductId);?> <?php echo $defaultCurrency?></span><br/>
			</p>
<?php include ('templates/product-options.tpl.php'); ?>
		</li>
<?php }?>
		<li>
			<hr/>
			<div>Total: <?php echo getCartPrice();?> <?php echo $defaultCurrency?></div>
		</li>
		</ul>
<?php if (getCartItemsNumber() > 0) { ?>
		<div style="float:right">
			<a href="/cart.php?action=emptycart">Clear Cart</a>
		</div>
<?php } ?>
	</div>
	<div style="float:right; width:340px;margin-top:18px">
	<fieldset id="billing_details" class="details">
		<legend>Billing details:</legend>
		<label>First Name<input type="text" name="first_name" value=""/> </label><br/>
		<label>Last Name<input type="text" name="last_name" value=""/> </label><br/>
		<label>Company<input type="text" name="company" value=""/> </label><br/>
		<label>Email<input type="email" name="email" value=""/> </label><br/>
		<label>Address<input type="text" name="address" value=""/> </label><br/>
		<label>City<input type="text" name="city" value="" /> </label><br/>
		<label>Zip<input type="text" name="postal_code" value="" /> </label><br/>
		<label>State<input type="text" name="state" value="" /> </label><br/>
		<label>Country<input type="text" name="country_code" maxlength="2" size="2" value="<?php echo getCartCountry();?>" /> </label><br/>
	</fieldset>
	<fieldset id="payment_details" class="details">
		<legend>Payment details:</legend><br/>
		<label>Card Number<input type="text" name="card_number" value="4111111111111111" /> </label><br/>
		<label>Card Type 
			<select name="card_type"> 
				<option value="VISA">Visa</option>
				<option value="MC">MasterCard</option>
				<option value="MAESTRO">Maestro</option>
			</select>
		</label><br/>
		<label>CVV2 <input type="text" name="ccid" value="1234" /> </label><br/>
		<label>Expiration Date 
			<span style="float:right" ><select style="display:inline; float:none;" name="date_year">
				<option>2010</option>
				<option>2011</option>
				<option selected="selected">2012</option>
				<option>2013</option>
				<option>2014</option>
				<option>2015</option>
			</select> &mdash;<select style="display:inline; float:none;" name="date_month">
				<option value="01">01 - Jan</option>
				<option value="02">02 - Feb</option>
				<option value="03">03 - Mar</option>
				<option value="04">04 - Apr</option>
				<option value="05">05 - May</option>
				<option value="06">06 - Jun</option>
				<option value="07">07 - Jul</option>
				<option value="08">08 - Aug</option>
				<option value="09">09 - Sep</option>
				<option value="10">10 - Oct</option>
				<option selected="selected" value="11">11 - Nov</option>
				<option value="12">12 - Dec</option>
			</select> </span>
		</label><br/>
		<label>Holder Name<input type="text" name="holder_name" value=""/> </label><br/>
	</fieldset>
	</div>
	<div style="clear:both"> <button>Place order</button> </div>
</form>
