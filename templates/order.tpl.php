<?php 
if (isset($refNo) && !is_null($refNo)) {
?>
		<div> Order status : <?php echo $status ?> </div>
		<div> Order Reference number : #<?php echo $refNo ?> </div>
		<?php echo is_null($msg) ? '<div>' . $msg . '</div>' : '' ; ?>
<?php
} else {
	
	include ('templates/np-order.tpl.php');
?>
	<div class="disclaimer">
		<h3>Disclaimer</h3>
		<p>Check out our <a href="#">Customer Support</a> for more information on online payment related issues, order status and transactions.</p>
		<p>
			For a prompt service, please state the order number from your confirmation email as a reference. We are happy to answer any questions you might have on the ordering process.<br/>
			Avangate is the authorized vendor of the products you have added to your shopping cart and your contractual partner.
		</p>

		<div class="hotline">
			<h5>Hotline:</h5>
			<dl>
				<dt>+40 21 3032062<dt> <dd>(Romania) </dd>
				<dt>+31 88 0000008</dt> <dd> (International) </dd>
				<dt>+1 (650) 963-5701</dt><dd> (USA/Canada)</dd>
				
				<!-- <dd>(24/7 English phone support for online payment related issues.)</dd> -->
			</dl>
		</div>
		<div class="secure">
			<img src="/images/verisign.png" /><br/>
			<img src="/images/mcaffee.png" />
		</div>
		<hr/>
		<div style="text-align:center">
			<p>Order processed by Avangate.</p>
			<p><a href="#">Privacy Policy</a> | <a href="#">Legal notice</a> | <a href="#">Terms and Conditions</a></p>
		</div>
	</div>
<script type="text/javascript" src="/scripts/jquery.clearinputs.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		function modifyQuantity (productId, quantity) {
			var action = null;
			$.ajax({
				url : '/cart/?id=' + productId + '&action=modq&quantity=' + quantity,
				type : 'post',
				dataType : 'json',
//				data : req,
				beforeSend: function (data) {
//					$('#price_display').css({color:'#f00', fontWeight: 'bold'}).html('?');
				},
				success: function (data) {
					$('#price_' + productId) = data.responseText['NetPrice'];
					$('#quantity_' + productId) = quantity;
//					$('#price').val(data.NetPrice);
//					$('#price_display').html(data.NetPrice).css({color: '#000', fontWeight: 'normal'});
				},
				error : function (data) {
//					$('#price').val(origPrice);
//					$('#price_display').html(origPrice).css({fontWeight: 'normal'});;
				},
				complete : function (data) {
					console.debug (data);
				}
			});
		}
		
		$('.modify_quantity a').click (function (e) {
			var that = $(this);
			var productId = $(this).data('id');
			var quantity = parseInt($('#quantity_'+productId).val());
			
			if (that.prop('class') == 'down') {
				quantity -= 1;
			} else {
				quantity += 1;
			}
			modifyQuantity(productId, quantity);
			e.preventDefault();
			e.stopPropagation();
		});

		$('#payment_details label input').not(':radio').not (':checkbox').add().clearInputs();
		$('#billing_details label input').not(':radio').not (':checkbox').add().clearInputs();
	});
</script>
<?php } ?>