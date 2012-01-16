<?php /* ?><pre><?php var_dump ($_SESSION); ?></pre><?php */ ?>
<?php include ('templates/locale.tpl.php') ?>
		
<?php include ('templates/cart-small.tpl.php') ?>
		
<?php
if (isset($a) && count($a) >= 1) {  
?>
<ul style="list-style: none">
<?php 
/* @var $prod mBasicProduct */
foreach ($a as $iKey => $prod) { 
	$cls = $iKey % 2 ? 'even' : 'odd';
	
	$Currency = getCartCurrency();
	$Currency = isset($Currency) ? $Currency : $prod->Currency;
?>
	<li style="clear:both; overflow:auto" class="<?php echo $cls?>"> 
		<a href="product-details.php?id=<?php echo$prod->ProductId?>"><img src="<?php echo$prod->ProductImage?>" style="width:80px; float:left" /></a>
		<div style="float:left; clear:right">
			<div class="prod_name"> 
				<a href="product-details.php?id=<?php echo$prod->ProductId?>"><?php echo$prod->ProductName?></a>  <span style="margin-left:12px"> <?php echo$prod->ProductVersion?></span>
			</div>
			<div class="prod_desc">
				<p > <?php echo$prod->ShortDescription?></p>
			</div>
			<div class="prod_price">
				<a href="/cart.php?action=add&amp;id=<?php echo$prod->ProductId?>">Add to cart</a> : <?php echo number_format($prod->Price, 2)?> <?php echo$Currency?> 
			</div>
		</div>
	</li>
<?php 
} 
?>
</ul>
<?php 
} else { ?>
		No products available.
	</div>	
<?php
} 
?>