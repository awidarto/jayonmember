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
	<?php echo validation_errors(); ?>
	<div class="form_box">
			<form method="post">

			<?php print form_fieldset('Main Merchant Information');?>

			Merchant Name:<br />
			<input type="text" name="merchantname" size="50" class="form" value="<?php echo set_value('merchantname',$user['merchantname']); ?>" /><?php echo form_error('merchantname'); ?><br /><br />

			Official Email:<br />
			<input type="text" name="mc_email" size="50" class="form" value="<?php echo set_value('mc_email',$user['mc_email']); ?>" /><?php echo form_error('mc_email'); ?><br /><br />

			<?php print form_fieldset_close();?>
		
			<?php print form_fieldset('Bank Account');?>

			Bank:<br />
			<input type="text" name="bank" size="50" class="form" value="<?php echo set_value('bank',$user['bank']); ?>" /><?php echo form_error('bank'); ?><br /><br />

			Account Name:<br />
			<input type="text" name="account_name" size="50" class="form" value="<?php echo set_value('account_name',$user['account_name']); ?>" /><?php echo form_error('account_name'); ?><br /><br />

			Account Number:<br />
			<input type="text" name="account_number" size="50" class="form" value="<?php echo set_value('account_number',$user['account_number']); ?>" /><?php echo form_error('account_number'); ?><br /><br />

			<?php print form_fieldset_close();?>

			<?php print form_fieldset('Merchant Main Address');?>	

			<?php echo form_checkbox('same_as_personal_address', '1', $user['same_as_personal_address']);?> Same as personal address<br /><br />

			Street:<br />
			<input type="text" name="mc_street" size="50" class="form" value="<?php echo set_value('mc_mobile',$user['mc_street']); ?>" /><?php echo form_error('mc_street'); ?><br /><br />

			District:<br />
			<input type="text" name="mc_district" size="50" class="form" value="<?php echo set_value('mc_district',$user['mc_district']); ?>" /><?php echo form_error('mc_district'); ?><br /><br />

			City:<br />
			<input type="text" name="mc_city" size="50" class="form" value="<?php echo set_value('mc_city',$user['mc_city']); ?>" /><?php echo form_error('mc_city'); ?><br /><br />

			Province:<br />
			<input type="text" name="mc_province" size="50" class="form" value="<?php echo set_value('mc_province',$user['mc_province']); ?>" /><?php echo form_error('mc_province'); ?><br /><br />

			Country:<br />
			<input type="text" name="mc_country" size="50" class="form" value="<?php echo set_value('mc_country',$user['mc_country']); ?>" /><?php echo form_error('mc_country'); ?><br /><br />

			ZIP:<br />
			<input type="text" name="mc_zip" size="50" class="form" value="<?php echo set_value('mc_zip',$user['mc_zip']); ?>" /><?php echo form_error('mc_zip'); ?><br /><br />

			Phone Number:<br />
			<input type="text" name="mc_phone" size="50" class="form" value="<?php echo set_value('mc_phone',$user['mc_phone']); ?>" /><?php echo form_error('mc_phone'); ?><br /><br />

			Mobile Number:<br />
			<input type="text" name="mc_mobile" size="50" class="form" value="<?php echo set_value('mc_mobile',$user['mc_mobile']); ?>" /><?php echo form_error('mc_mobile'); ?><br /><br />

			<?php print form_fieldset_close(); ?>

			<input type="submit" value="Register" name="register" />
			</form>
	</div>
</div>