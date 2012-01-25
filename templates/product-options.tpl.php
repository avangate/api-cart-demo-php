		<dl class="price_details"> 
<?php /* @var $OptionGroup mPriceOptionGroup */ 
foreach ($prod->PriceOptions as $iKey => $OptionGroup) { ?>
				<dt class="opt_group1">
					 <strong><?php echo $OptionGroup->Name ?><?php if ($OptionGroup->Required) { echo '<sup class="mandatory">*</sup>'; } ?></strong>
				</dt> 
				<dd style="padding-left:10px">
<?php
	if ($OptionGroup->Type == 'COMBO') { ?>
					<select name="<?php echo $OptionGroup->Name ?>" <? echo ($OptionGroup->Required ? 'class="required"' : '') ?> > 
<?php 
	if (!$OptionGroup->Required) { ?> 
						<option value="">none</option> 
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
				</dd>
<?php 
} ?>
		</dl>