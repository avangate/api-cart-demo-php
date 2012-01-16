<?php 
	$country = getCartCountry();
	$currency = getCartCurrency();
?>
<?php if (count($errors) > 0) {?><div><?php echo implode ('<br/>', $errors); ?></div><?php } ?>
<div id="locale_select">
	<form id="locale_form" action="">
		<select name="country">
			<option <?php echo $country == 'gb' ? 'selected="selected"' : ''; ?> value="gb">United Kingdom</option>
			<option <?php echo $country == 'de' ? 'selected="selected"' : ''; ?> value="de">Germany</option>
			<option <?php echo $country == 'ro' ? 'selected="selected"' : ''; ?> value="ro">Romania</option>
			<option <?php echo $country == 'us' ? 'selected="selected"' : ''; ?> value="us">United States of America</option>
		</select>
		<select name="currency">
			<option <?php echo $currency == 'EUR' ? 'selected="selected"' : ''; ?> value="EUR">EUR</option>
			<option <?php echo $currency == 'GBP' ? 'selected="selected"' : ''; ?> value="GBP">GBP</option>
			<option <?php echo $currency == 'USD' ? 'selected="selected"' : ''; ?> value="USD">USD</option>
			<option <?php echo $currency == 'RON' ? 'selected="selected"' : ''; ?> value="RON">RON</option>
		</select>
		</select>
	</form>
</div>
<script type="text/javascript">
$(document).ready (function () {
	$('#locale_form').children('select').change(function (e) {
		var req = {};
		$('#locale_form').children('select').each (function () {
			var varname = $(this).attr('name');
			var value = $(this).val();
			req[varname] = value;
		});
		var status =  $('<img src="/templates/1-0.gif" />');
		$.ajax({
			url : '/cart.php?&action=set',
			type : 'post',
			dataType : 'json',
			data : req,
			beforeSend: function (data) {
				status.appendTo('locale-select');
				console.debug (status);
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