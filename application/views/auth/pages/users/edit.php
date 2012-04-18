<div id="form">	
	<div class="form_box">
			<form method="post" action="<?php echo site_url('admin/users/edit')?>">
			Username:<br />
			<input type="text" name="username" size="50" class="form" value="<?php echo set_value('username', $user['username']); ?>" /><br /><?php echo form_error('username'); ?><br />
						
			Email:<br />
			<input type="text" name="email" size="50" class="form" value="<?php echo set_value('email',$user['email']); ?>" /><?php echo form_error('email'); ?><br /><br />

			Full Name:<br />
			<input type="text" name="fullname" size="50" class="form" value="<?php echo set_value('fullname', $user['fullname']); ?>" /><?php echo form_error('fullname'); ?><br /><br />

			Street:<br />
			<input type="text" name="street" size="50" class="form" value="<?php echo set_value('street', $user['street']); ?>" /><?php echo form_error('mobile'); ?><br /><br />

			District:<br />
			<input type="text" name="district" size="50" class="form" value="<?php echo set_value('district', $user['district']); ?>" /><?php echo form_error('district'); ?><br /><br />

			City:<br />
			<input type="text" name="city" size="50" class="form" value="<?php echo set_value('city', $user['city']); ?>" /><?php echo form_error('city'); ?><br /><br />

			Province:<br />
			<input type="text" name="province" size="50" class="form" value="<?php echo set_value('province', $user['province']); ?>" /><?php echo form_error('province'); ?><br /><br />

			Country:<br />
			<input type="text" name="country" size="50" class="form" value="<?php echo set_value('country', $user['country']); ?>" /><?php echo form_error('country'); ?><br /><br />

			ZIP:<br />
			<input type="text" name="zip" size="50" class="form" value="<?php echo set_value('zip', $user['zip']); ?>" /><?php echo form_error('zip'); ?><br /><br />

			Phone Number:<br />
			<input type="text" name="phone" size="50" class="form" value="<?php echo set_value('phone', $user['phone']); ?>" /><?php echo form_error('phone'); ?><br /><br />

			Mobile Number:<br />
			<input type="text" name="mobile" size="50" class="form" value="<?php echo set_value('mobile', $user['mobile']); ?>" /><?php echo form_error('mobile'); ?><br /><br />

			<input type="submit" value="Update" name="update" />
			<?php
				print anchor('admin/dashboard','Cancel');
			?>
			</form>
	</div>
</div>