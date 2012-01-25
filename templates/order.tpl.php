<?php 
if (isset($refNo) && !is_null($refNo)) {
?>
		<div> Order status : <?php echo $status ?> </div>
		<div> Order Reference number : #<?php echo $refNo ?> </div>
		<?php echo is_null($msg) ? '<div>' . $msg . '</div>' : '' ; ?>
<?php
} else {
?>
	<form method="post" class="frm">
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
				<?php  if (count($c->getItems()) > 0) { ?>
						<div style="float:left">
						<a href="/cart.php?action=emptycart">Empty Cart</a>
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
				<img src="/htdocs/images/visa.png" /><br/>
				<input type="radio" name="card_type" value="VISA" selected="selected"/>
			</label>
			<label> 
				<img src="/htdocs/images/mastercard.png" /><br/>
				<input type="radio" name="card_type" value="MC"/>
			</label>
			<label>
				<img src="/htdocs/images/discovery.png" /><br/>
				<input type="radio" name="card_type" value="DISCOVERY"/>
			</label>
			<label> 
				<img src="/htdocs/images/jcb.png" /><br/>
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
	<div class="disclaimer">
		<h3>Disclaimer</h3>
		<p>Check out our <a href="#">Customer Support</a> for more information on online payment related issues, order status and transactions.</p>
		<p>
			For a prompt service, please state the order number from your confirmation email as a reference. We are happy to answer any questions you might have on the ordering process.<br/>
			Avangate is the authorized vendor of the products you have added to your shopping cart and your contractual partner.
		</p>

		<div class="hotline">
			<h5>Hotline:</h5>
			<dl>
				<dt>+40 21 3032062<dt> <dd>(Romania) </dd>
				<dt>+31 88 0000008</dt> <dd> (International) </dd>
				<dt>+1 (650) 963-5701</dt><dd> (USA/Canada)</dd>
				
				<!-- <dd>(24/7 English phone support for online payment related issues.)</dd> -->
			</dl>
		</div>
		<div class="secure">
			<img src="/htdocs/images/verisign.png" /><br/>
			<img src="/htdocs/images/mcaffee.png" />
		</div>
		<hr/>
		<div style="text-align:center">
			<p>Order processed by Avangate.</p>
			<p><a href="#">Privacy Policy</a> | <a href="#">Legal notice</a> | <a href="#">Terms and Conditions</a></p>
		</div>
	</div>
<script type="text/javascript" src="/htdocs/scripts/jquery.clearinputs.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		function modifyQuantity (productId, quantity) {
			var action = null;
			$.ajax({
				url : '/cart.php?id=' + productId + '&action=modq&quantity=' + quantity,
				type : 'post',
				dataType : 'json',
//				data : req,
				beforeSend: function (data) {
//					$('#price_display').css({color:'#f00', fontWeight: 'bold'}).html('?');
				},
				success: function (data) {
					$('#price_' + productId) = data.responseText['NetPrice'];
					$('#quantity_' + productId) = quantity;
//					$('#price').val(data.NetPrice);
//					$('#price_display').html(data.NetPrice).css({color: '#000', fontWeight: 'normal'});
				},
				error : function (data) {
//					$('#price').val(origPrice);
//					$('#price_display').html(origPrice).css({fontWeight: 'normal'});;
				},
				complete : function (data) {
					console.debug (data);
				}
			});
		}
		
		$('.modify_quantity a').click (function (e) {
			var that = $(this);
			var productId = $(this).data('id');
			var quantity = parseInt($('#quantity_'+productId).val());
			
			if (that.prop('class') == 'down') {
				quantity -= 1;
			} else {
				quantity += 1;
			}
			modifyQuantity(productId, quantity);
			e.preventDefault();
			e.stopPropagation();
		});

		$('#payment_details label input').not(':radio').not (':checkbox').add().clearInputs();
		$('#billing_details label input').not(':radio').not (':checkbox').add().clearInputs();
	});
</script>
<?php } ?>