<?php 
	//$country = $c->getCountry();
	$currency = $c->getCurrency();
	//$allCountries = $c->getAvailableCountries();
	$allCurrencies = $c->getAvailableCurrencies();

	if (/*!is_array($allCountries) ||*/ !is_array($allCurrencies)) {
		return;
	}
?>
	<form id="locale_form" action="">
		<!-- <select name="country">
<?php /*/foreach ($allCountries as $countryCode) {?>
			<option <?php echo strtolower($country) == strtolower($countryCode) ? 'selected="selected"' : ''; ?> value="<?php echo strtolower($countryCode);?>"><?php echo $countryCode;?></option>
<?php } /*/ ?>
		</select> -->
		<select name="currency">
<?php foreach ($allCurrencies as $currencyCode) {?>
			<option <?php echo strtolower($currency) == strtolower($currencyCode) ? 'selected="selected"' : ''; ?> value="<?php echo strtolower($currencyCode);?>"><?php echo strtoupper($currencyCode);?></option>
<?php } ?>
		</select>
	</form>