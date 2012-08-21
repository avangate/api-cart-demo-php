
<div class="products">
	<h3>Products:</h3>
<?php include ('templates/prod-details.tpl.php') ?>
</div>
		<!-- <pre> <?php /* var_dump ($prod); */ ?> </pre> -->
<script type="text/javascript" >
$(document).ready (function () {
	var radios = $('input:radio');
	var checkboxes = $('input:checkbox');
	var rc = radios.add(checkboxes);
	var selects = $('#frm select').not();
	var texts = $('input:text');

	var inputs = rc.add(selects).add(texts);
	
	inputs.change(function (e) {
		var req = $('form#frm').serializeArray();
		var origPrice = <?php echo $prod->Price; ?>+0;

		var waiting = $('<img src="/images/waiting.gif" />').css ({
			marginLeft : 4,
			marginRight: 4,
			marginBottom: -1
		});
		
		$.ajax({
			url : '/cart/?id=' + $('input:hidden#prod_id').val() + '&action=getprice',
			type : 'post',
			dataType : 'json',
			data : req,
			beforeSend: function (data) {
				$('#price_display').html(waiting);
			},
			success: function (data) {
				$('#price').val(data.NetPrice);
				$('#price_display').html(data.FinalPrice).css({color: '#000', fontWeight: 'normal'});
			},
			error : function (data) {
				$('#price').val(origPrice);
				$('#price_display').html(origPrice).css({fontWeight: 'normal', color: '#f99'});
			},
			complete : function (data) {}
		});
	});
});
</script>
