		<div id="cart_small">
			<a href="/view-cart.php"><img src="/htdocs/images/cart-small.png" alt="View Cart" title="View Cart"/></a>
			<div style="float:right">
			Total price: <strong><?php echo $c->getTotalPrice(); ?> <?php echo $c->getCurrency();?></strong><br/>
			Quantity: <strong><?php echo $c->getItemsQuantity(); ?></strong> <br/>
			Items: <strong><?php echo count($c->getItems()); ?></strong> <br/>
			</div>
<?php if (count($c->getItems()) > 0) { ?>
			<div style="float:right">
				<a href="/order.php">Place Order</a> &mdash; <a style="margin-left:10px" href="/cart.php?action=emptycart">Clear Cart</a>
			</div>
<?php } ?>
		</div>