<?php /* @var $prod mProduct */
$quantity = (int)$c->getItemQuantity($prod->ProductId);
// d ($prod);
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
		</td>
		<td style="text-align:right;"> 
			<div class="modify_quantity"><a class="up" data-id="<?php echo $prod->ProductId?>" id="qinc_<?php echo $prod->ProductId?>" href="#">&#8963;</a> <a class="down" data-id="<?php echo $prod->ProductId?>" id="qdec_<?php echo $prod->ProductId?>" href="#">&#8964;</a></div>
			<input type="text" name="quantity" id="quantity_<?php echo $prod->ProductId?>" style="width:40px; text-align:right" max-length="3" value="<?php echo $quantity;?>"/> 
		</td>
		<td style="text-align:right">
			<span id="quantity_display"><?php echo number_format($prod->Price,2) ?></span> <span class="currency_display"><?php echo strtoupper($prod->Currency ? $prod->Currency : $prod->DefaultCurrency ) ?></span>
		</td>
		<td style="text-align:right">
			<span id="price_display"><?php echo number_format($prod->Price * $quantity,2) ?></span> <span class="currency_display"><?php echo strtoupper($prod->Currency ? $prod->Currency : $prod->DefaultCurrency ) ?></span>
		</td>
		<td style="text-align:center;border-right:0px; padding:0"><a href="/cart/?action=del&amp;id=<?php echo $prod->ProductId;?>"><img class="action" src="htdocs/images/delete.png" style="margin:0"/></a></td>
	</tr>
	<!-- <tr class="prod_description ">
		<td colspan="6">
<?php include ('templates/product-options-readonly.tpl.php'); ?>
		</td>
	</tr> -->

<!-- <pre>
<?php var_dump ($prod); ?>
</pre> -->
