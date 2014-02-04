<?php /* ?><pre><?php var_dump ($_SESSION); ?></pre><?php */ ?>

<?php
if (isset($a) && count($a) >= 1) {  
?>
<ul style="list-style: none;margin-top:0px" class="products">
	<li><h3>Products:</h3></li>
<?php
/* @var $prod mBasicProduct */
foreach ($a as $iKey => $prod) { 
	$cls = $iKey % 2 ? 'even' : 'odd';
	
	$Currency = $c->getCurrency();
	$Currency = isset($Currency) ? $Currency : $prod->Currency;
?>
	<li style="clear:both; overflow:auto;padding:0;margin:0">
<?php include ('templates/prod-simple-details.tpl.php');?>
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