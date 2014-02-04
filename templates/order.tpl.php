<?php 
try {
$countryCode = isset($mBilling) && $mBilling->Country ? $mBilling->Country : $c->getCountry();
$allCountries = $c->getAvailableCountries();
if (isset($status)) {
?>
		<div> Order status : <strong><?php echo $status ?></strong> </div>
		<?php if ($refNo > 0) { ?><div> Order Reference number : <strong>#<?php echo $refNo ?></strong> </div> <?php }?>
		<?php echo !is_null($msg) ? '<div>Payment gateway message: <strong>' . $msg . '</strong></div>' : '' ; ?>
<?php
} else {
	$selfUrl = urlencode('http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strpos ($_SERVER['REQUEST_URI'], '?')));

?>
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
<?php
	if ($step == 2 && (!isset($mPayment->Type) && $mPayment->Type != 'CC')) { ?>
		<form method="post" class="frm" action="<?php echo PAYMENT_URL ?>?finish=true&amp;redir=<?php echo $selfUrl?>">
			<input type="hidden" value="<?php echo $c->getSessionId(); ?>" name="hash" />
			<input type="hidden" value="<?php echo $c->getCurrency(); ?>" name="currency" />
			<input type="hidden" value="<?php echo $c->getCountry(); ?>" name="country" />
<?php } else { ?>
			<form method="post" class="frm" action="?step=<?php echo $step ?><?php if (isset($mPayment->Type)) echo "&amp;pmethod=" . $mPayment->Type;?>">
<?php }?>
	<div class="details" style="margin-right:10px;">
	<fieldset id="billing_details" style="position: relative;overflow: visible">
		<legend>Billing details:</legend>
		
		<label>First Name <input type="text" name="first_name" <?php echo ($step == 2) ? 'disabled="disabled" ' : '';?>value="<?php echo $mBilling->FirstName; ?>"/> </label><br/>
		<label>Last Name <input type="text" name="last_name" <?php echo ($step == 2) ? 'disabled="disabled" ' : '';?>value="<?php echo $mBilling->LastName; ?>"/> </label><br/>
		<!-- <label>Company <input type="text" name="company" value=""/> </label><br/> -->
		<label>Email <input type="email" name="email" <?php echo ($step == 2) ? 'disabled="disabled" ' : '';?>value="<?php echo $mBilling->Email; ?>"/> </label><br/>
		<label>Address <input type="text" name="address" <?php echo ($step == 2) ? 'disabled="disabled" ' : '';?>value="<?php echo $mBilling->Address; ?>"/> </label><br/>
		<label>City <input type="text" name="city" <?php echo ($step == 2) ? 'disabled="disabled" ' : '';?>value="<?php echo $mBilling->City; ?>" /> </label><br/>
		<label>Zip <input type="text" name="postal_code" <?php echo ($step == 2) ? 'disabled="disabled" ' : '';?>value="<?php echo $mBilling->PostalCode; ?>" /> </label><br/>
		<label>State <input type="text" name="state" <?php echo ($step == 2) ? 'disabled="disabled" ' : '';?>value="<?php echo $mBilling->State; ?>" /> </label><br/>
		<label style="padding-top:18px; margin-bottom:19px; clear:both;color:#888">Country 
			<select name="country_code">
<?php if (is_array($allCountries)) foreach ($allCountries as $countryCode) {?>
				<option <?php echo strtolower($country) == strtolower($countryCode) ? 'selected="selected"' : ''; ?> <?php echo ($step == 2) ? 'disabled="disabled" ' : '';?>value="<?php echo strtolower($countryCode);?>"><?php echo $countryCode;?></option>
<?php } ?>
			</select>
		
			<!-- <input type="text" name="country_code" maxlength="2" size="2" value="<?php echo ($countryCode); ?>" /> -->
		</label><br/> 
<?php if ($step == '1') {?>
		<label class="place_order" style="display:block;bottom: 9px;position: absolute;width: 95%"> <button>Next <img src="/images/order-btn.png"/></button> </label>
<?php } else {
?>
	<label class="place_order" style="display:block; bottom: 9px;position: absolute;width: 95%"> <button id="edit" style="padding:0">Edit ...</button> </label>
<?php
} ?>
	</fieldset>
	</div> 
	<div class="details">
	<fieldset id="payment_details" style="position: relative;overflow: visible">
		<legend>Payment details: </legend>
<?php
if (empty($mPayment->Type)) { ?>
	<!--<div id="tabs">

		<ul>
			<li> <a href="#ccform">Credit Card</a></li>
			<li> <a href="#ppform">PayPal</a> </li>
		</ul>
		-->
<?php
}

//	echo '<div id="ccform">';
	include ('templates/ccform.tpl.php');
//	echo '</div>';
//	echo '<div id="ppform">';
//	include ('templates/paypalform.tpl.php');
//	echo '</div>';

if ($step == 2) {?>
		<label class="place_order" style="display:block;bottom: 35px;position: absolute;width: 95%"> <button>Place order <img src="/images/order-btn.png"/></button> </label>
<?php } ?><br/>
<!--	</div>-->
	</fieldset>
	</div>
	</form>
	</div>
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
			<img src="/images/verisign.png" /><br/>
			<img src="/images/mcaffee.png" />
		</div>
		<hr/>
		<div style="text-align:center">
			<p>Order processed by Avangate.</p>
			<p><a href="#">Privacy Policy</a> | <a href="#">Legal notice</a> | <a href="#">Terms and Conditions</a></p>
		</div>
	</div>
<script type="text/javascript" src="/scripts/jquery.clearinputs.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		var defaultCards = <?php echo json_encode ($defaultCards); ?>;
		/**/
		function modifyQuantity (productId, quantity) {
			var action = null;
			$.ajax({
				url : '/cart/?id=' + productId + '&action=modq&quantity=' + quantity,
				type : 'post',
				dataType : 'json',
//				data : req,
				beforeSend: function (data) {
//					$('#price_display').css({color:'#f00', fontWeight: 'bold'}).html('?');
				},
				success: function (data) {
					$("#price_" + productId).val( data.responseText['NetPrice'] );
					$("#quantity_" + productId).val ( quantity);
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
		/**/
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


		/*/$( "#tabs" ).tabs({
			<?php /**/ if ($step == 1) { ?>disabled: true,<?php } /**/?>
			show: function (e, ui) {
				var form = $(ui.panel.parentElement).children('.ui-tabs-panel').not($(ui.panel));
				var formElements = form.find('input').add(form.find('select'));
				formElements.prop({disabled : 'disabled'});

				$(ui.panel).find('input').add($(ui.panel).find('select')).prop({disabled:false});
			},
			show : function (e, ui) {
				console.debug (ui);
			}
		});/**/

		$( ".card_pick input:radio").click(function (e) {
			var type = $(e.target).val();
			$('#payment_details label input').not(':radio').not (':checkbox').css({opacity: 1});
			$("#card_number").val (defaultCards[type].CardNumber);
			$("#ccid").val (defaultCards[type].CCID);
			$("#date_year").val (defaultCards[type].ExpirationYear);
			$("#date_month").val (defaultCards[type].ExpirationMonth);
			$("#holder_name").val (defaultCards[type].HolderName);
		});
	});
	console.debug($('input:hidden[name="method"]').prop('disabled'));
</script>
<?php 
} 
} catch (Exception $e) {
	_e ($e);
}
?>
