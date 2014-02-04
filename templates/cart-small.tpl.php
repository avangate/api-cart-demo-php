		<div id="cart_small">

<?php include ('locale.tpl.php');?>

			<a href="/order/" style="float:left" ><img src="/images/cart-small.png" alt="View Cart" title="View Cart"/></a>
			<div style="float:left; margin-left:10px">
			Total price: <strong><?php echo $c->getTotalPrice(); ?> <?php echo $c->getCurrency();?></strong><br/>
			Quantity: <strong><?php echo $c->getItemsQuantity(); ?></strong> <br/>
			Items: <strong><?php echo count($c->getItems()); ?></strong> <br/>
			</div>
			<?php /*/ ?>
			<div style="float:right;clear:both">
				<a style="margin-left:10px" href="/cart/?action=emptycart">Clear Cart</a>
				<?php if (count($c->getItems()) > 0) { ?>
				 &mdash; <a href="/order/">Place Order</a><?php } ?>
			</div>
			<?php /*/ ?>
		</div>