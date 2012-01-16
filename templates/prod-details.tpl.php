<?php /* @var $prod mProduct */

// d ($prod);
?>	
	<div class="prod_details">
			<img src="<?php echo $prod->ProductImage ?>" style="float:left" />
			<h3 class="prod_name"><?php echo $prod->ProductName ?> <?php echo $prod->ProductVersion; ?></h3>
			<p class="prod_desc"><?php echo $prod->LongDescription ? $prod->LongDescription : $prod->ShortDescription ?></p>
			<div class="price"> 
			<form method="post" action="/cart.php?action=add" id="frm">
				<input type="hidden" id="prod_id" name="id" value="<?php echo $prod->ProductId ?>" /> 
				<input type="hidden" id="price" name="price" value="<?php echo number_format($prod->Price,2) ?>" /> 
				<input type="hidden" id="currency" name="currency" value="<?php echo (getCartCurrency() ? getCartCurrency() : $prod->Currency) ?>" /> 
				Quantity: <input type="text" name="quantity" id="quantity" value="1" /><br/>
				Price: <span id="price_display"><?php echo number_format($prod->Price,2) ?></span> <span id="currency_display"><?php echo ($prod->Currency ? $prod->Currency : $prod->DefaultCurrency ) ?></span> <button type="submit">Add to cart</button><br/>
		
<?php /* @var $OptionGroup mPriceOptionGroup */ 
foreach ($prod->PriceOptions as $iKey => $OptionGroup) { ?>
				<div class="opt_group"><strong><?php echo $OptionGroup->Name ?><?php if ($OptionGroup->Required) { echo '<span class="error">*</span>'; } ?></strong><br/>
<?php
	if ($OptionGroup->Type == 'COMBO') { ?>
					<select name="<?php echo $OptionGroup->Name ?>" <? echo ($OptionGroup->Required ? 'class="required"' : '') ?>> 
<?php 
	if (!$OptionGroup->Required) { ?> 
						<option></option> 
<?php 
	}  ?>
<?php		/* @var $Option mPriceOptionOption */
		foreach ($OptionGroup->Options as $iOptionKey => $Option) { ?>
						<option value="<?php echo $Option->Value ?>" <?php echo $Option->Default ? "selected='selected'" : '' ?>><?php echo $Option->Name ?> </option>
<?php		}  ?>
					</select>
		
<?php	} elseif ($OptionGroup->Type == 'INTERVAL') { ?>
					<label><?php echo $OptionGroup->Name ?> <input <? echo ($OptionGroup->Required ? 'class="required"' : '') ?> type="text" name="<?php echo $OptionGroup->Name ?>" /></label>
<?php 
	} else {
		foreach ($OptionGroup->Options as $iOptionKey => $Option) { ?>
					<label><?php echo $Option->Name ?> <input <? echo ($OptionGroup->Required ? 'class="required"' : '') ?> type="<? echo strtolower($OptionGroup->Type) ?>" <?php echo $Option->Default ? "checked='checked'" : '' ?> name="<?php echo $OptionGroup->Name ?><?php echo ($OptionGroup->Type=='CHECKBOX' ? '[]' : '');?>" value="<?php echo $Option->Value?>" /></label>
<?php		
		}
	}  ?>
				</div>
<?php 
} ?>
			</form>
			</div>
		</div>
<pre>
<?php var_dump ($prod);?>
</pre>