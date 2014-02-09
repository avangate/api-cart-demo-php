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
					<select name="<?php echo htmlentities( $OptionGroup->Name); ?>" <?php if ($OptionGroup->Required) { echo 'class="required"'; } ?> >
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

<?php	} elseif ($OptionGroup->Type == 'INTERVAL') {
		$maxOption = array_pop($OptionGroup->Options);
		$minOption = array_shift($OptionGroup->Options);
		if (is_null($minOption)) $minOption = $maxOption;
?>
					<label>
						<?php echo htmlentities( $OptionGroup->Name); ?> (Min: <?php echo $minOption->MinValue;?>, Max: <?php echo $maxOption->MaxValue;?>):
						<input id="inp_<?php echo $OptionGroup->Id;?>" class="slider_inp<?php if ($OptionGroup->Required) { echo ' required'; } ?>" type="text" name="<?php echo htmlentities( $OptionGroup->Name); ?>" value="<?php echo $minOption->MinValue?>"/>
					</label>
					<div class="slider" data-min="<?php echo $minOption->MinValue; ?>" data-max="<?php echo $maxOption->MaxValue; ?>" id="div_<?php echo $OptionGroup->Id;?>"></div>
	<?php
	} else {
		if (!$OptionGroup->Required && $OptionGroup->Type == 'RADIO') {
?>
					<label>none <input type="<?php echo htmlentities( strtolower($OptionGroup->Type)); ?>" checked='checked' name="<?php echo htmlentities( $OptionGroup->Name); ?>" value="" /></label>
<?php
		}
		foreach ($OptionGroup->Options as $iOptionKey => $Option) { ?>
					<label><?php echo htmlentities( $Option->Name); ?> <input <?php if ($OptionGroup->Required) { echo 'class="required"'; } ?> type="<?php echo htmlentities( strtolower($OptionGroup->Type)); ?>" <?php if ( $Option->Default ) { echo 'checked="checked"'; } ?> name="<?php echo htmlentities( $OptionGroup->Name); ?><?php if($OptionGroup->Type=='CHECKBOX') { echo '[]'; }?>" value="<?php echo htmlentities( $Option->Value);?>" /></label>
<?php
		}
	}  ?>
				</dd>
<?php
} ?>
		</dl>

<script>
	$(document).ready(function () {
		$( ".slider" ).each(function() {
			var sli = $(this);

			var oid = this.id.substr(4);
			var val = $("#inp_" + oid).val();
			var maximum = $(this).data("max");
			var minimum = $(this).data("min");

			sli.slider({
				range: "min",
				min: minimum,
				max: maximum,
				value: val,
				slide: function (e, ui) {
					var oid = this.id.substr(4);
					$("#inp_" + oid).val(ui.value);
				},
				stop: function (e, ui) {
					var oid = this.id.substr(4);

					$("#inp_" + oid).change();
				}
			});
		});
	});
</script>
