<?php include ('templates/locale.tpl.php') ?>
		
<?php include ('templates/cart-small.tpl.php') ?>
		
<?php 
$cartProducts = getCartItems();
foreach ($cartProducts as $idProduct => $dataProduct) {
	$prod = $c->getProductById($idProduct);
?>
		<div class="prod_details">
			<img src="<?php echo $prod->ProductImage ?>" style="float:left" />
			<h3 class="prod_name"><?php echo $prod->ProductName ?> <?php echo $prod->ProductVersion; ?></h3>
			<p class="prod_desc"><?php echo $prod->ShortDescription; ?></p>
			<div class="price"> 
				Quantity: <?php echo $dataProduct['QUANTITY'];?><br/>
				Price: <span id="price_display"><?php echo $dataProduct['PRICE']; ?></span> <span id="currency_display"><?php echo getCartCurrency(); ?></span>
<?php include ('templates/product-options.tpl.php'); ?>
			</div>
		</div>
<?php } ?>	
