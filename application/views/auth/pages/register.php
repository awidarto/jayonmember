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

	<h2><?php print $page_title;?></h2>
	
	<div class="form_box">
			<form method="post">
			Username:<br />
			<input type="text" name="username" size="50" class="form" value="<?php echo set_value('username'); ?>" /><br /><?php echo form_error('username'); ?><br />
			
			Password:<br />
			<input type="password" name="password" size="50" class="form" value="<?php echo set_value('password'); ?>" /><?php echo form_error('password'); ?><br /><br />
			
			Password confirmation:<br />
			<input type="password" name="password_conf" size="50" class="form" value="<?php echo set_value('conf_password'); ?>" /><?php echo form_error('conf_password'); ?><br /><br />
			
			Email:<br />
			<input type="text" name="email" size="50" class="form" value="<?php echo set_value('email'); ?>" /><?php echo form_error('email'); ?><br /><br />

			Full Name:<br />
			<input type="text" name="fullname" size="50" class="form" value="<?php echo set_value('fullname'); ?>" /><?php echo form_error('fullname'); ?><br /><br />

			<input type="hidden" name="group_id" size="50" class="form" value="<?php echo group_id('buyer'); ?>" /><br /><br />
			<input type="checkbox" name="merchant_request" id="merchant_request" value="1" <?php echo set_checkbox('merchant_request', '1',false); ?> onClick="javascript:showMerchant();" /> Request merchant account<br /><br />

		<div id="merchant_box" style="display:none;background-color:#eaeaea;width:300px;padding:5px;" >
		
			Merchant Name:<br />
			<input type="text" name="merchantname" size="50" class="form" value="<?php echo set_value('merchantname'); ?>" /><?php echo form_error('merchantname'); ?><br /><br />
		
			Bank:<br />
			<input type="text" name="bank" size="50" class="form" value="<?php echo set_value('bank'); ?>" /><?php echo form_error('bank'); ?><br /><br />

			Account Name:<br />
			<input type="text" name="account_name" size="50" class="form" value="<?php echo set_value('account_name'); ?>" /><?php echo form_error('account_name'); ?><br /><br />

			Account Number:<br />
			<input type="text" name="account_number" size="50" class="form" value="<?php echo set_value('account_number'); ?>" /><?php echo form_error('account_number'); ?><br /><br />
		
		</div>	
		
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

			<input type="submit" value="Register" name="register" />
			<?php echo anchor(base_url(), 'Home','style="padding:8px;padding-left:150px;"'); ?>
			</form>
	</div>
</div>