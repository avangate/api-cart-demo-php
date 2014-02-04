		<input type="hidden" name="method" value="CC" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>/>
		<div class="card_pick">
			<label> 
				<img src="/images/visa.png" /><br/>
				<input <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>type="radio" name="card_type" value="VISA" checked="checked"/>
			</label>
			<label> 
				<img src="/images/mastercard.png" /><br/>
				<input <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>type="radio" name="card_type" value="MAESTRO"/>
			</label>
			<label>
				<img src="/images/discovery.png" /><br/>
				<input <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>type="radio" name="card_type" value="DISCOVERY"/>
			</label>
			<label>
				<img src="/images/amex.png" /><br/>
				<input <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>type="radio" name="card_type" value="AMEX"/>
			</label>
		</div>
		
		<label style="height:20px !important;">Card Number <input type="text" id="card_number" name="card_number" value="<?php if ($step == '2') {?>4111111111111111<?php } ?>" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>/> </label><br/>
		
		<label style="height:20px !important;">CVV2 <input type="text" id="ccid" name="ccid" value="<?php if ($step == '2') {?>1234<?php } ?>" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>/> </label><br/>
		
		<label style="padding-top:20px; clear:both; color:#888">Expiration
			<span style="float:right" >
				<select style="display:inline; float:none;" id="date_year" name="date_year" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>>
					<option>2014</option>
					<option selected="selected">2015</option>
					<option>2016</option>
					<option>2017</option>
					<option>2018</option>
					<option>2019</option>
				</select> &ndash;
				<select style="display:inline; float:none;" id="date_month" name="date_month" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>>
					<option value="01">01 - Jan</option>
					<option value="02">02 - Feb</option>
					<option value="03">03 - Mar</option>
					<option value="04">04 - Apr</option>
					<option value="05">05 - May</option>
					<option value="06">06 - Jun</option>
					<option value="07">07 - Jul</option>
					<option value="08">08 - Aug</option>
					<option value="09">09 - Sep</option>
					<option value="10">10 - Oct</option>
					<option selected="selected" value="11">11 - Nov</option>
					<option value="12">12 - Dec</option>
				</select> 
			</span>
		</label><br/>
		<label style="height:20px !important;">Holder Name <input type="text" id="holder_name" name="holder_name" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?> value=""/> </label><br/>
		
		<address style="font-size:0.8em;color:#999;font-weight:lighter;bottom: 9px;position: absolute">Please note that the payment information is dispatched through <a href="http://avangate.com">Avangate BV.</a> &ndash; which is a PCI compliant payment processor.</address>