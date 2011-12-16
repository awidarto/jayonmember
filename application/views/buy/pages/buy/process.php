<?php
	//print_r($trx_detail);
?>
<script>
	$(document).ready(function() {
		$( '#buyerdeliverytime' ).datetimepicker({
			dateFormat:'yy-mm-dd',
			showSecond: false,
			timeFormat: 'hh:mm:ss',
			stepHour: 2,
			stepMinute: 30
		});
		
		$( '#buyerdeliveryzone' ).autocomplete({
			source: '<?php print site_url('buy/getzone')?>',
			method: 'post',
			minLength: 2
		});
	} );
</script>
<?php print form_open('buy/confirm');?>

<h2>Delivery Order</h2>

Please specify your intended delivery zone and delivery time, and maybe modify your shipping address as well.

<table border="0" cellpadding="4" cellspacing="0" id="mainInfo">
<tbody>
<tr>
	<td>Delivery Number:</td>
	<td><?php print $delivery_id;?></td>
</tr>
<tr>
	<td>Delivery Zone:</td>
	<td>
		<input type="hidden" name="delivery_id" id="delivery_id" value="<?php echo $delivery_id; ?>" size="50" class="form" /><?php echo form_error('buyerdeliveryzone'); ?><br /><br />
		<input type="text" name="buyerdeliveryzone" id="buyerdeliveryzone" value="<?php echo set_value('buyerdeliveryzone'); ?>" size="50" class="form" /><?php echo form_error('buyerdeliveryzone'); ?><br /><br />
	</td>
</tr>
<tr>
	<td>Delivery Date & Time:</td>
	<td>
		<input type="text" name="buyerdeliverytime" id="buyerdeliverytime" value="<?php echo set_value('buyerdeliverytime'); ?>" size="50" class="form" /><?php echo form_error('buyerdeliverytime'); ?><br /><br />
	</td>
</tr>
<tr>
	<td>Delivery Address:</td>
	<td>
		<textarea name="shipping_address" cols="60" rows="10"><?php echo set_value('shipping_address',$trx_detail['shipping_address']); ?></textarea>	
	</td>
</tr>
<tr>
	<td>Contact Number:</td>
	<td>
		<input type="text" name="phone" id="phone" value="<?php echo $trx_detail['phone']; ?>" size="50" class="form" /><br /><br />
	</td>
</tr>
</tbody>
</table>
<br /><br />


<?php echo $this->table->generate(); ?>

	
	<input type="submit" value="Confirm Order" name="confirm" />
	<input type="submit" value="Cancel Order" name="cancel" />

<?php print form_close()?>
