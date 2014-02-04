<div class="products">
	<h3>Products:</h3>
		<table class="prod_details" cellpadding="0" cellspacing="0">
			<colgroup>
				<col class="c_description"/>
				<col span="3" class="c_details"/>
				<col class="c_actions"/>
			</colgroup>
<?php 

if (count ($contents->Products) > 0) {
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
	/* @var $cartItem mCartItem */
	$totalPrice = 0;
	$totalPriceVAT = 0;
	foreach ($contents->Products as $iKey => $cartItem) {
		$prod = $cartItem->Product;
		$setOptionGroups = $cartItem->PriceOptions;
		$i++;
?>
	<tr class="prod_description ">
		<td style="border-left:0px;">
			<input type="hidden" id="id_<?php echo $prod->ProductId?>" name="id" value="<?php echo $prod->ProductId ?>" /> 
			<input type="hidden" id="price_<?php echo $prod->ProductId?>" name="price" value="<?php echo number_format($prod->Price,2) ?>" /> 
			<input type="hidden" id="currency_<?php echo $prod->ProductId?>" name="currency" value="<?php echo ($c->getCurrency() ? $c->getCurrency() : $prod->Currency) ?>" />
			<img src="<?php echo !is_null($prod->ProductImage) ? $prod->ProductImage : '/images/defaultprod.png';?>" style="float:left; width:100px" />
			<h4><?php echo $prod->ProductName ?> <span><?php echo $prod->ProductVersion; ?></span></h4>
			<div class="description">
				<?php echo $prod->ShortDescription ? $prod->ShortDescription : 'No description available' ?>
			</div>
			<?php include ('product-options-readonly.tpl.php'); ?>
		</td>
		<td style="text-align:right;"> 
			<div class="modify_quantity"><a class="up" data-id="<?php echo $prod->ProductId?>" id="qinc_<?php echo $prod->ProductId?>" href="#">&#8963;</a> <a class="down" data-id="<?php echo $prod->ProductId?>" id="qdec_<?php echo $prod->ProductId?>" href="#">&#8964;</a></div>
			<input type="text" name="quantity" id="quantity_<?php echo $prod->ProductId?>" style="width:40px; text-align:right" max-length="3" value="<?php echo $cartItem->Quantity;?>"/> 
		</td>
		<td style="text-align:right">
			<span id="quantity_display"><?php echo number_format($cartItem->Price->NetPrice / $cartItem->Quantity, 2) ?></span> <span class="currency_display"><?php echo strtoupper($cartItem->Price->NetCurrency) ?></span>
		</td>
		<td style="text-align:right">
			<span id="price_display"><?php echo number_format($cartItem->Price->NetPrice, 2) ?></span> <span class="currency_display"><?php echo strtoupper($cartItem->Price->NetCurrency) ?></span>
		</td>
		<td style="text-align:center;border-right:0px; padding:0"><a href="/cart/?action=del&amp;id=<?php echo $prod->ProductId;?>"><img class="action" src="/images/delete.png" style="margin:0"/></a></td>
	</tr>
<?php 
		$totalPrice += $cartItem->Price->NetPrice;
		$totalPriceVAT += $cartItem->Price->FinalPrice;
	}
?>
			</tbody>
			<tfoot>
				<tr>
					<td style="padding:0; margin:0;border:none" colspan="2">
						<form class="details" style="width:100%" action="/order/">
							<label class="place_order" style="display:block;margin:0;width:100%;text-align: left"> <button id="checkout">Go to checkout... <img src="/images/order-btn.png"/></button> </label>
						</form>
					</td>
				<td id="totals" style="text-align:right; font-size:90%; padding:4px;" colspan="3">
				<?php /* if (getCartItemsNumber() > 0) { ?>
						<div style="float:left">
						<a href="/cart/?action=emptycart">Empty Cart</a>
						</div>
<?php } */ ?>
					<div>Regular price: <?php echo number_format($contents->NetPrice, 2) ?></div>
					<div>Total VAT: <?php echo number_format($contents->FinalPrice, 2) ?> [<?php echo number_format(($contents->FinalPrice - $contents->NetPrice) * 100/$contents->NetPrice,2)?>%]</div>
					<div class="total_price"> Total: <?php echo number_format($contents->FinalPrice, 2);?> <?php echo $contents->FinalCurrency;?></div>
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
<script type="text/javascript">
	$(document).ready(function () {
//		$('#checkout').click(
//		);
	});
	console.debug($('input:hidden[name="method"]').prop('disabled'));
</script>