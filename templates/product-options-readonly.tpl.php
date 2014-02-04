<div style="font-size:0.8em">
<ul style="list-style:none">
	<li style="display: inline">Selected price options:</li>
<?php /* @var $OptionGroup mPriceOptionGroup */
if (!isset($setOptionGroups)) { 
	$setOptionGroups = $c->getItemPriceOptions($prod->ProductId);
}
if (!empty($prod->PriceOptions) && !empty($setOptionGroups)) {
foreach ($prod->PriceOptions as $iKey => $OptionGroup) {
	$GroupName = null;
	$Options = array();
	
	$groupKey = str_replace(' ', '_', $OptionGroup->Name);
	if (isset($setOptionGroups[$groupKey])) {
		foreach ($OptionGroup->Options as $iOptionKey => $Option) { 
			$setOptions = explode(',', $setOptionGroups [$groupKey]);
			$optionKey = $Option->Value;
			if (in_array($optionKey, $setOptions)) {
				$GroupName = $OptionGroup->Name;
				$Options[] = $Option->Name;
			}
		}
	}
	if  (!is_null($GroupName)) {
?>
				<li class="opt_group"><strong><?php echo $GroupName ?>: </strong> <span><?php echo implode (', ', $Options) ?> </span></li>
<?php } 
}
} else {
?>
	<li class="opt_group"> <em style="color:darkgrey">none</em> </li>
<?php
}
?>
</ul>
</div>