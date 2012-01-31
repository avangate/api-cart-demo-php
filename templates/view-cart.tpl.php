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
				<?php /* if (getCartItemsNumber() > 0) { ?>
						<div style="float:left">
						<a href="/cart/?action=emptycart">Empty Cart</a>
						</div>
<?php } */ ?>
					<!-- <div>Regular price: <?php echo 120 ?></div>
					<div>Total VAT: <?php echo 11.8 ?></div> -->
					<div class="total_price"> Total: <?php echo $c->getTotalPrice();?> <?php echo $c->getCurrency();?></div>
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