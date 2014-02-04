		<dl class="price_details"> 
<?php /* @var $OptionGroup mPriceOptionGroup */ 
foreach ($prod->PriceOptions as $iKey => $OptionGroup) { ?>
				<dt class="opt_group1">
					 <strong><?php echo htmlentities( $OptionGroup->Name); if ($OptionGroup->Required) { echo '<sup class="mandatory">*</sup>'; } ?></strong>
				</dt> 
				<dd style="padding-left:10px">
<?php
	if ($OptionGroup->Type == 'COMBO') { 
	?>
					<select name="<?php echo htmlentities( $OptionGroup->Name); ?>" <? if ($OptionGroup->Required) { echo 'class="required"'; } ?> >
<?php 
	if (!$OptionGroup->Required) { ?>

						<option value="">none</option>
<?php 
	}
		/* @var $Option mPriceOptionOption */
		foreach ($OptionGroup->Options as $iOptionKey => $Option) { ?>
						<option value="<?php echo htmlentities( $Option->Value); ?>" <?php if ($Option->Default) { echo 'selected="selected"'; } ?> ><?php echo htmlentities( $Option->Name); ?> </option>
<?php		}  ?>
					</select>
		
<?php	} elseif ($OptionGroup->Type == 'INTERVAL') { ?>
					<label><?php echo htmlentities( $OptionGroup->Name); ?> <input <? if ($OptionGroup->Required) { echo 'class="required"'; } ?> type="text" name="<?php echo htmlentities( $OptionGroup->Name); ?>" /></label>
<?php 
	} else {
		if (!$OptionGroup->Required && $OptionGroup->Type == 'RADIO') {
?>
					<label>none <input type="<? echo htmlentities( strtolower($OptionGroup->Type)); ?>" checked='checked' name="<?php echo htmlentities( $OptionGroup->Name); ?>" value="" /></label>
<?php 
		}
		foreach ($OptionGroup->Options as $iOptionKey => $Option) { ?>
					<label><?php echo htmlentities( $Option->Name); ?> <input <? if ($OptionGroup->Required) { echo 'class="required"'; } ?> type="<? echo htmlentities( strtolower($OptionGroup->Type)); ?>" <?php if ( $Option->Default ) { echo 'checked="checked"'; } ?> name="<?php echo htmlentities( $OptionGroup->Name); ?><?php if($OptionGroup->Type=='CHECKBOX') { echo '[]'; }?>" value="<?php echo htmlentities( $Option->Value);?>" /></label>
<?php
		}
	}  ?>
				</dd>
<?php 
} ?>
		</dl>