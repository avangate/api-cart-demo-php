	<form method="post" class="frm" >
	<div class="products">
	<h3>Products:</h3>
		<table class="prod_details" cellpadding="0" cellspacing="0">
			<colgroup>
				<col class="c_description"/>
				<col span="3" class="c_details"/>
				<col class="c_actions"/>
			</colgroup>
<?php 
if (count ($cartProducts) > 0) {
?>
			<thead style="font-size:80%;">
				<th style=" padding:6px 0px;">&nbsp;</th>
				<th>Quantity</th>
				<th>Unit Price</th>
				<th>Value</th>
				<th>&nbsp;</th>
			</thead>
			<tbody>
<?php
$i = 0;
$bReadonly = true;
	foreach ($cartProducts as $iKey => $prod) {
		$i++;
		include ('templates/prod-order-details.tpl.php');
	}
?>
			</tbody>
			<tfoot>
				<tr>
				<td colspan="5" id="totals" style="text-align:right; font-size:90%; padding:4px;">
				<?php if (count($c->getItems()) > 0) { ?>
						<div style="float:left">
						<a href="/cart/?action=emptycart">Empty Cart</a>
						</div>
<?php } ?>
					<!-- <div>Regular price: <?php echo 120 ?></div>
					<div>Total VAT: <?php echo 11.8 ?></div> -->
					<div class="total_price"> Total: <?php echo $c->getTotalPrice();?> <?php echo $c->getCurrency(); ?></div>
				</td>
				</tr>
			</tfoot>
<?php 
} else {
?>
			<tr>
				<td colspan="5" class="empty">No products in cart.</td>
			</tr>
<?php 
}
?>
		</table>
		<br/>
		<address>VAT might apply for EU Orders. The total price inclusive all applicable taxes will be displayed before the order is transmitted.</address>
	</div>
	<div class="order_details">
	<div class="details" style="margin-right:10px;">
	<fieldset id="billing_details">
		<legend>Billing details:</legend>
		
		<label>First Name <input type="text" name="first_name" value=""/> </label><br/>
		<label>Last Name <input type="text" name="last_name" value=""/> </label><br/>
		<!-- <label>Company <input type="text" name="company" value=""/> </label><br/> -->
		<label>Email <input type="email" name="email" value=""/> </label><br/>
		<label>Address <input type="text" name="address" value=""/> </label><br/>
		<label>City <input type="text" name="city" value="" /> </label><br/>
		<label>Zip <input type="text" name="postal_code" value="" /> </label><br/>
		<label>State <input type="text" name="state" value="" /> </label><br/>
		<label>Country <input type="text" name="country_code" maxlength="2" size="2" value="<?php echo $c->getCountry(); ?>" /> </label><br/>
	</fieldset>
	</div>
	<div class="details">
	<fieldset id="payment_details">
		<legend>Payment details: </legend>
		<div class="label" style="margin:4px 0">Choose your payment option:</div>
		<div class="card_pick">
			<label> 
				<img src="/images/visa.png" /><br/>
				<input type="radio" name="card_type" value="VISA" selected="selected"/>
			</label>
			<label> 
				<img src="/images/mastercard.png" /><br/>
				<input type="radio" name="card_type" value="MC"/>
			</label>
			<label>
				<img src="/images/discovery.png" /><br/>
				<input type="radio" name="card_type" value="DISCOVERY"/>
			</label>
			<label> 
				<img src="/images/jcb.png" /><br/>
				<input type="radio" name="card_type" value="JCB"/>
			</label>
		</div>
		
		<label>Card Number <input type="text" name="card_number" value="4111111111111111" /> </label><br/>
		
		<label>CVV2 <input type="text" name="ccid" value="1234" /> </label><br/>
		
		<label style="padding-top:20px; clear:both">Expiration Date 
			<span style="float:right" >
				<select style="display:inline; float:none;" name="date_year">
					<option>2010</option>
					<option>2011</option>
					<option selected="selected">2012</option>
					<option>2013</option>
					<option>2014</option>
					<option>2015</option>
				</select> &ndash;
				<select style="display:inline; float:none;" name="date_month">
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
				</select> 
			</span>
		</label><br/>
		<label>Holder Name <input type="text" name="holder_name" value=""/> </label><br/>
		<div style="height:1px; line-height:1px; clear:both">&nbsp;</div>
		<label class="place_order" style="display:block;"> <button>Place order <img src="htdocs/images/order-btn.png"/></button> </label>
	</fieldset>
	</div>
	</div>
	</form>