<?php 
	$country = $c->getCountry();
	$currency = $c->getCurrency();
// 	d ($country, $currency);
	$allCountries = $c->getAvailableCountries();
	$allCurrencies = $c->getAvailableCurrencies();
?>
	<form id="locale_form" action="">
		<select name="country">
<?php foreach ($allCountries as $countryCode => $CountryName) {?>
			<option <?php echo strtolower($country) == strtolower($countryCode) ? 'selected="selected"' : ''; ?> value="<?php echo strtolower($countryCode);?>"><?php echo $CountryName;?></option>
<?php } ?>
		</select>
		<select name="currency">
<?php foreach ($allCurrencies as $currencyCode => $CurrencyName) {?>
			<option <?php echo strtolower($currency) == strtolower($currencyCode) ? 'selected="selected"' : ''; ?> value="<?php echo strtolower($currencyCode);?>"><?php echo $CurrencyName;?></option>
<?php } ?>
		</select>
	</form>
<script type="text/javascript">
$(document).ready (function () {
	$('#locale_form').children('select').change(function (e) {
		var req = {};
		$('#locale_form').children('select').each (function () {
			var varname = $(this).attr('name');
			var value = $(this).val();
			req[varname] = value;
		});
		var status =  $('<img src="/htdocs/images/waiting.gif" />');
		$.ajax({
			url : '/cart.php?&action=set',
			type : 'post',
			dataType : 'json',
			data : req,
			beforeSend: function (data) {
				status.appendTo('locale-select');
//				console.debug (status);
			},
			success: function (data) {
				document.location.reload(true);
			},
			complete : function (data) {
				document.location.reload(true);
//				$('#locale_select').remove(status);
			}
		});
	});
});
</script>