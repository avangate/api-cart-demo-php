<?php /* @var $prod mProduct */

// d ($prod);
?>	
	<div class="prod_details">
	<form method="post" action="/cart/?action=add" class="frm" id="frm">
		<div class="prod_description">
			<img src="<?php echo !is_null($prod->ProductImage) ? str_replace('http://MYWEB_FULL_HOST', 'https://secure.avangate.com', $prod->ProductImage) : '/images/defaultprod.png';?>" style="float:left;width:120px" />
			<h4><?php echo $prod->ProductName ?> <span><?php echo $prod->ProductVersion; ?></span></h4>
			<button type="submit" style="font-size:110%;">Add to cart <span style="margin-left:4px">&raquo;</span> </button><br/><br/>
			<div class="description">
				<?php if (strlen($prod->LongDescription) && strlen($prod->ShortDescription)) { echo 'No description available'; } else { echo $prod->LongDescription ? $prod->LongDescription : $prod->ShortDescription; }?>
			</div>
			<br/>
<?php if (!empty($prod->Platforms)) {
	$OSNames = array(); 
	foreach ($prod->Platforms as $OSName => $OSDetails) {
		$OSNames[] = $OSName;
	}
?>
			<div class="description">Operating System<?php echo count($OSNames) > 1 ? 's' : '';?>: <?php echo implode (', ', $OSNames)?></div> 
<?php } ?>
			<br/>
			<input type="hidden" id="prod_id" name="id" value="<?php echo $prod->ProductId ?>" /> 
			<input type="hidden" id="price" name="price" value="<?php echo number_format($prod->Price,2) ?>" /> 
			<input type="hidden" id="currency" name="currency" value="<?php echo ($c->getCurrency() ? $c->getCurrency() : $prod->Currency) ?>" />
			<div style="overflow:auto;clear:both">
			<div style="float:left;margin-left:130px;width:260px">
				Quantity: <input type="text" name="quantity" id="quantity" maxlength="4" value="<?php echo ($c->getItemQuantity($prod->ProductId) > 0 ? $c->getItemQuantity($prod->ProductId) : '1')?>"/>
			</div>
			<div style="float:right;width:240px">
				Price: <span id="price_display"><?php echo number_format($prod->Price,2) ?></span> <span id="currency_display"><?php echo strtoupper(($prod->Currency ? $prod->Currency : $prod->DefaultCurrency )); ?></span>
			</div>
			</div>
		</div>
<?php

if (!isset($bReadonly) || !$bReadonly) { 
	include ('templates/product-options.tpl.php');
} else {
	include ('templates/product-options-readonly.tpl.php');
} 
?>
	</form>
	</div>
	<address>VAT might apply for EU Orders. The total price inclusive all applicable taxes will be displayed before the order is transmitted.</address>
<pre>
<?php var_dump ($prod); ?>
</pre> 