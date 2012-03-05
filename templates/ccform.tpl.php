		<input type="hidden" name="method" value="CCVISAMC" />
		<div class="card_pick">
			<label> 
				<img src="/images/visa.png" /><br/>
				<input <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>type="radio" name="card_type" value="VISA" checked="checked"/>
			</label>
			<label> 
				<img src="/images/mastercard.png" /><br/>
				<input <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>type="radio" name="card_type" value="MC"/>
			</label>
			<label>
				<img src="/images/discovery.png" /><br/>
				<input <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>type="radio" name="card_type" value="DISCOVERY"/>
			</label>
			<label> 
				<img src="/images/jcb.png" /><br/>
				<input <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>type="radio" name="card_type" value="JCB"/>
			</label>
		</div>
		
		<label>Card Number <input type="text" name="card_number" value="<?php if ($step == '2') {?>4111111111111111<?php } ?>" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>/> </label><br/>
		
		<label>CVV2 <input type="text" name="ccid" value="<?php if ($step == '2') {?>1234<?php } ?>" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>/> </label><br/>
		
		<label style="padding-top:20px; clear:both; color:#888">Expiration
			<span style="float:right" >
				<select style="display:inline; float:none;" name="date_year" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>>
					<option>2010</option>
					<option>2011</option>
					<option selected="selected">2012</option>
					<option>2013</option>
					<option>2014</option>
					<option>2015</option>
				</select> &ndash;
				<select style="display:inline; float:none;" name="date_month" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?>>
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
		<label>Holder Name <input type="text" name="holder_name" <?php echo ($step == '1') ? 'disabled="disabled" ' : '';?> value=""/> </label><br/>
		
		<div style="height:1px; line-height:1px; clear:both;margin-top:5em;">&nbsp;</div>
		<address style="font-size:0.8em;color:#999;font-weight:lighter;">Please note that the payment information is dispatched through <a href="http://avangate.com">Avangate BV.</a> &ndash; which is a PCI compliant payment processor.</address>