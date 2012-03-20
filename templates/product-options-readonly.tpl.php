<div>Price options:
<ul style="list-style:none">
<?php /* @var $OptionGroup mPriceOptionGroup */
if (!isset($setOptionGroups)) { 
	$setOptionGroups = $c->getItemPriceOptions($prod->ProductId);
}

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
?>
</ul>
</div>