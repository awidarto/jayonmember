<script>
	$(document).ready(function() {
		if($('#merchant_request').is(':checked')){
			$('#merchant_box').show('slow');
		}else{
			$('#merchant_box').hide('slow');
		}
	} );
	
	function showMerchant(){
		if($('#merchant_request').is(':checked')){
			$('#merchant_box').show('slow');
		}else{
			$('#merchant_box').hide('slow');
		}
	}
	
</script>


<div id="form">
	
	<div class="form_box">
			<form method="post">
			<?php print form_fieldset('Main Information');?>

				Merchant Name:<br />
				<input type="text" name="merchantname" size="50" class="form" value="<?php echo set_value('merchantname'); ?>" /><?php echo form_error('merchantname'); ?><br /><br />

				Official Email:<br />
				<input type="text" name="email" size="50" class="form" value="<?php echo set_value('email'); ?>" /><?php echo form_error('email'); ?><br /><br />

			<?php print form_fieldset_close();?>
			<br />
		
			<?php print form_fieldset('Bank Account');?>
				Bank:<br />
				<input type="text" name="bank" size="50" class="form" value="<?php echo set_value('bank'); ?>" /><?php echo form_error('bank'); ?><br /><br />

				Account Name:<br />
				<input type="text" name="account_name" size="50" class="form" value="<?php echo set_value('account_name'); ?>" /><?php echo form_error('account_name'); ?><br /><br />

				Account Number:<br />
				<input type="text" name="account_number" size="50" class="form" value="<?php echo set_value('account_number'); ?>" /><?php echo form_error('account_number'); ?><br /><br />
			<?php print form_fieldset_close();?>
			<br />

			<?php print form_fieldset('Merchant Address');?>			
				Street:<br />
				<input type="text" name="street" size="50" class="form" value="<?php echo set_value('mobile'); ?>" /><?php echo form_error('mobile'); ?><br /><br />

				District:<br />
				<input type="text" name="district" size="50" class="form" value="<?php echo set_value('district'); ?>" /><?php echo form_error('district'); ?><br /><br />

				City:<br />
				<input type="text" name="city" size="50" class="form" value="<?php echo set_value('city'); ?>" /><?php echo form_error('city'); ?><br /><br />

				Province:<br />
				<input type="text" name="province" size="50" class="form" value="<?php echo set_value('province'); ?>" /><?php echo form_error('province'); ?><br /><br />

				Country:<br />
				<input type="text" name="country" size="50" class="form" value="<?php echo set_value('country'); ?>" /><?php echo form_error('country'); ?><br /><br />

				ZIP:<br />
				<input type="text" name="zip" size="50" class="form" value="<?php echo set_value('zip'); ?>" /><?php echo form_error('zip'); ?><br /><br />

				Phone Number:<br />
				<input type="text" name="phone" size="50" class="form" value="<?php echo set_value('phone'); ?>" /><?php echo form_error('phone'); ?><br /><br />

				Mobile Number:<br />
				<input type="text" name="mobile" size="50" class="form" value="<?php echo set_value('mobile'); ?>" /><?php echo form_error('mobile'); ?><br /><br />
			<?php print form_fieldset_close();?>
			<br />
			<input type="submit" value="Register" name="register" />
			</form>
	</div>
</div>