<div id="login">
	<table>
		<tr>
			<td style="width:300px;vertical-align:top;">
				<h2>Register</h2>
				<div class="form_box">
						<form method="POST"  action="<?php echo site_url('buy/register/'.$data['api_key'].'/'.$data['trx_id'])?>" >
						Username:<br />
						<input type="text" name="username" value="<?php echo set_value('username'); ?>" size="50" class="form" /><?php echo form_error('username'); ?><br /><br />
						Password:<br />
						<input type="password" name="password" value="<?php echo set_value('password'); ?>" size="50" class="form" /><?php echo form_error('password'); ?><br /><br />
						Password Confirmation:<br />
						<input type="password_conf" name="password_conf" value="" size="50" class="form" /><br /><br />
						Full Name:<br />
						<input type="text" name="fullname" value="<?php echo set_value('fullname'); ?>" size="50" class="form" /><?php echo form_error('fullname'); ?><br /><br />
						Email:<br />
						<input type="text" name="email" value="<?php echo set_value('email'); ?>" size="50" class="form" /><?php echo form_error('email'); ?><br /><br />
						<input type="submit" value="Register" name="register" />
						</form>
				</div>
			</td>
			<td style="width:100%;text-align:center;">
				- OR -
			</td>
			<td style="width:300px;vertical-align:top;">
				<h2>Login</h2>
				<div class="form_box">
						<form method="POST" action="<?php echo site_url('buy/login/'.$data['api_key'].'/'.$data['trx_id'])?>" >
						Username/Email:<br />
						<input type="text" name="username" value="<?php echo set_value('username'); ?>" size="50" class="form" /><?php echo form_error('username'); ?><br /><br />
						Password:<br />
						<input type="password" name="password" value="<?php echo set_value('password'); ?>" size="50" class="form" /><?php echo form_error('password'); ?><br /><br />
						<input type="submit" value="Login" name="login" />
						</form>
				</div>
			</td>
		</tr>
	</table>
</div>