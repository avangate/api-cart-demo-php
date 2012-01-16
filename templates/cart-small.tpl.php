		<div id="cart_small">
			Total price: <?php echo getCartPrice(); ?> <?php echo getCartCurrency();?><br/>
			Items: <?php echo getCartQuantities (); ?> <br/>
<?php if (getCartItemsNumber() > 0) { ?>
			<div style="float:right">
				<a href="/order.php">Place Order</a> &mdash; 
				<a href="/cart.php?action=emptycart">Clear Cart</a>
			</div>
<?php } ?>
		</div>