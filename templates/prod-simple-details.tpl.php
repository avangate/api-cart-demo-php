<?php /* @var $prod mProduct */
if ($prod->Price instanceof stdClass) { 
	$price = (array)$prod->Price;
	$prod->Price = array_shift($price);
}
?>	
	<div class="prod_details" style="; overflow:visible;">
		<div class="prod_description">
			<div style="float:left; width:500px">
				<a href="product-details/?id=<?php echo $prod->ProductId?>"><img src="<?php echo !is_null($prod->ProductImage) ? str_replace('http://MYWEB_FULL_HOST', 'https://secure.avangate.com', $prod->ProductImage) : '/images/defaultprod.png';?>" style="width:80px; float:left" /></a>
				<h4><a href="/product-details/?id=<?php echo$prod->ProductId?>"><?php echo $prod->ProductName ?></a> <?php if ($prod->ProductVersion) {?><span>(v <?php echo $prod->ProductVersion; ?>)</span><?php }?></h4>
				<div class="description"><?php echo strlen ($prod->ShortDescription) ? $prod->ShortDescription : 'No description available'?></div>
			</div>
			<div style="float:right; width:160px; clear:right; font-size:80%">
			<form action="/cart/?action=add" method="post" class="frm">
				<input type="hidden" name="id" value="<?php echo $prod->ProductId; ?>" />
				Quantity: <select name="quantity" style="min-width:7ex;">
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
				</select><br/>
				Price: <span id="price_display"><?php echo number_format((int)$prod->Price,2) ?></span> <span id="currency_display"><?php echo strtoupper($prod->DefaultCurrency ? $prod->Currency : $prod->DefaultCurrency ) ?></span><br/>
				<button type="submit" style="font-size:90%; padding:1px 5px; height:1.8em">Add to cart &raquo;</button>
			</form>
			</div>
			<div style="clear:both;height:1px; line-height:1px">&nbsp;</div>
		</div>
	</div>

<!-- <pre>
<?php var_dump ($prod); ?>
</pre> -->