<?php include ('templates/locale.tpl.php') ?>

<?php include ('templates/cart-small.tpl.php') ?>

<?php include ('templates/prod-details.tpl.php') ?>
		
		<!-- <pre> <?php /* var_dump ($prod); */ ?> </pre> -->
<script type="text/javascript" >
$(document).ready (function () {
	var radios = $('input:radio');
	var checkboxes = $('input:checkbox');
	var rc = radios.add(checkboxes);
	var selects = $('select');
	var texts = $('input:text');

	var inputs = rc.add(selects).add(texts);
	
//	$('input:radio').not('.required').click (function (e) {
//		var that = $(this);
//		if (that.prop('checked')) {
//			$('[name=' + $(this).prop('name') + ']').prop('checked', false);
//		} 
//		console.debug (that.attr('checked'));
//	});
	
	inputs.change(function (e) {
		var req = $('form#frm').serializeArray();
		var origPrice = <?php echo $prod->Price; ?>;
		
		$.ajax({
			url : '/cart.php?id=' + $('input:hidden#prod_id').val() + '&action=getprice',
			type : 'post',
			dataType : 'json',
			data : req,
			beforeSend: function (data) {
				$('#price_display').css({color:'#f00', fontWeight: 'bold'}).html('?');
			},
			success: function (data) {
				$('#price').val(data.NetPrice);
				$('#price_display').html(data.NetPrice).css({color: '#000', fontWeight: 'normal'});
			},
			error : function (data) {
				$('#price').val(origPrice);
				$('#price_display').html(origPrice).css({fontWeight: 'normal'});;
			},
			complete : function (data) {}
		});
	});
});
</script>
