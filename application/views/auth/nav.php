<?php
    function set_hilite($urlpattern){
        $hilite = preg_match('/'.$urlpattern.'/',current_url());
        return ($hilite)?'nav_current':'';
    }

?>
	<script> 
		$(document).ready(function() {
			$('#nav li').hover(
			        function () {
			            //show its submenu
			            $('ul', this).slideDown(100);

			        }, 
			        function () {
			            //hide its submenu
			            $('ul', this).slideUp(100);         
			        }
			    );
		});
	</script>
	<?php // print_r($this->session->userdata); ?>
	<ul id="nav">
	<?php if(logged_in()):?>
		<li class="<?php print set_hilite('admin\/dashboard')?>" ><?php echo anchor('admin/dashboard', 'Dashboard'); ?></li>
		<li class="<?php print set_hilite('admin\/buyer')?>" ><?php if(user_group('buyer') || user_group('merchant')) { echo anchor('admin/buyer/orders', 'My Orders'); } ?>
			<ul>
				<?php if(user_group('buyer') || user_group('merchant')):?><li class="<?php print set_hilite('admin\/buyer\/orders')?>" ><?php echo anchor('admin/buyer/orders', 'In-Process Orders'); ?></li><?php endif;?>
				<?php if(user_group('buyer') || user_group('merchant')):?><li class="<?php print set_hilite('admin\/buyer\/dispatched')?>" ><?php echo anchor('admin/buyer/dispatched', 'Dispatched Orders'); ?></li><?php endif;?>
			</ul>
		</li>

		<?php if(!user_group('merchant')):?>
			<li class="<?php print set_hilite('admin\/merchant\/request')?>" ><?php echo anchor('admin/merchant/request', 'Become A Merchant'); ?></li>
		<?php else:?>
			<li class="<?php print set_hilite('admin\/delivery')?>" ><?php if(user_group('merchant')) { echo anchor('admin/delivery/incoming', 'Orders'); } ?>
				<ul>
					<?php if(user_group('buyer') || user_group('merchant')):?><li class="<?php print set_hilite('admin\/delivery\/incoming')?>" ><?php echo anchor('admin/delivery/incoming', 'Incoming Orders');?></li><?php endif;?>
					<?php if(user_group('buyer') || user_group('merchant')):?><li class="<?php print set_hilite('admin\/delivery\/dispatched')?>" ><?php echo anchor('admin/delivery/dispatched', 'In Progress Orders'); ?></li><?php endif;?>
					<?php if(user_group('buyer') || user_group('merchant')):?><li class="<?php print set_hilite('admin\/delivery\/delivered')?>" ><?php echo anchor('admin/delivery/delivered', 'Delivered Orders');?></li><?php endif;?>
					<?php if(user_group('buyer') || user_group('merchant')):?><li class="<?php print set_hilite('admin\/delivery\/revoked')?>" ><?php echo anchor('admin/delivery/revoked', 'Revoked Orders');?></li><?php endif;?>
					<?php if(user_group('buyer') || user_group('merchant')):?><li class="<?php print set_hilite('admin\/delivery\/rescheduled')?>" ><?php echo anchor('admin/delivery/rescheduled', 'Rescheduled Orders');?></li><?php endif;?>
				</ul>
			</li>
			<li class="<?php print set_hilite('admin\/location\/log')?>" ><?php if(user_group('admin')) { echo anchor('admin/location/log', 'Locations'); } ?>
				<ul>
					<li class="<?php print set_hilite('admin\/location\/log')?>" ><?php if(user_group('admin')) { echo anchor('admin/location/log', 'Location Log'); } ?></li>
					<li class="<?php print set_hilite('admin\/location\/tracker')?>" ><?php if(user_group('admin')) { echo anchor('admin/location/tracker', 'Location Tracker'); } ?></li>
				</ul>
			</li>
			<li class="<?php print set_hilite('admin\/apps')?>" ><?php if(user_group('merchant')) { echo anchor('admin/apps/manage', 'Merchant Options'); } ?>
				<ul>
					<li class="<?php print set_hilite('admin\/apps')?>" ><?php if(user_group('merchant')) { echo anchor('admin/apps/manage', 'Application Keys'); } ?></li>
				</ul>
			</li>
		<?php endif;?>
	<?php else:?>
		<li class="<?php print set_hilite('login')?>"><?php echo anchor('login', 'Login'); ?></li>
	<?php endif;?>
	</ul>
	<div class="clear"></div>
