<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<?php echo $this->ag_asset->load_script('gmap3.min.js');?>
<script>

	$(document).ready(function() {

		$('#map').gmap3({
			action:'init',
			options:{
				  center:[-6.17742,106.828308],
				  zoom: 12
				}
		});

		function refreshMap(){
			var currtime = new Date();
			//console.log(currtime.getTime());

			$.post('<?php print site_url('ajaxpos/getmapmarker');?>/' + currtime.getTime() ,
				{
					'device_identifier':$('#search_device').val(),
					'timestamp':$('#search_deliverytime').val()
				}, 
				function(data) {
					if(data.result == 'ok'){
						$('#map').gmap3({
							action:'clear'
						});

						$.each(data.paths,function(){
							$('#map').gmap3({
								action:'addPolyline',
								options:{
									strokeColor: this.color,
									strokeOpacity: 1.0,
									strokeWeight: 2
								},
								path: this.poly
							});

						});

						$.each(data.locations,function(){
							if(this.data.status == 'loc_update'){
								icon =  null;
							}else{
								icon = new google.maps.MarkerImage('http://maps.gstatic.com/mapfiles/icon_green.png');								
							}
							$('#map').gmap3({
								action:'addMarker',
								latLng:[this.data.lat, this.data.lng],
								marker: {
									options: {
										//icon:icon
										//icon: new google.maps.MarkerImage('http://maps.gstatic.com/mapfiles/icon_green.png')
									},
									data:{identifier:this.data.identifier,timestamp:this.data.timestamp,status:this.data.status},
									events:{
										mouseover: function(marker,event,data){
											//console.log(data);
											$(this).gmap3(
												{action:'clear',name:'overlay'},
												{action:'addOverlay',
													latLng:marker.getPosition(),
													content:
														'<div style="background-color:white;padding:3px;border:thin solid #aaa;width:150px;">' +
															'<div class="bg"></div>' +
															'<div class="text">' + data.identifier + '<br />' + data.timestamp + '<br />' + data.status + '</div>' +
														'</div>',
													offset: {
														x:-46,
														y:-73
													}
												}
											);
										},
										mouseout: function(){
											$(this).gmap3({action:'clear', name:'overlay'});
										}
									}
								}								
							});

						});
					}
				},'json');

		}

		function refresh(){
			refreshMap();
			setTimeout(refresh, <?php print get_option('map_refresh_rate');?> * 1000);
		}

		refresh();		



	});

</script>
<style>
.stat_box{
	height:300px;
	width:100%;
	border:0px solid #ccc;
	margin-bottom:10px;
}

td {
	vertical-align:top;
}
</style>


<div id="tracker" >
	<table style="padding:0px;margin:0px;">
		<tr>
			<td>
				<h3>Positions</h3>
				<div id="map" style="width:600px;height:950px;display:block;"></div>
			</td>
			<td style="width:100%;height:100%;vertical-align:top;">
				<h3>Statistics</h3>
				<div id="statistics"  style="width:100%;height:100%;">
					<span>Total Incoming <?php print $period;?></span>
					<div id="incoming_monthly" class="stat_box">
						<img src="<?php print base_url();?>admin/graphs/monthlystackedgraph" alt="monthly_all" />
					</div>
					<span>Delivered <?php print $period;?></span>
					<div id="delivered_monthly" class="stat_box">
						<img src="<?php print base_url();?>admin/graphs/monthlystackedgraph/delivered" alt="monthly_all" />
					</div>
					<span>No Show <?php print $period;?></span>
					<div id="noshow_monthly" class="stat_box">
						<img src="<?php print base_url();?>admin/graphs/monthlystackedgraph/noshow" alt="monthly_all" />
					</div>
					<span>Rescheduled <?php print $period;?></span>
					<div id="rescheduled_monthly" class="stat_box">
						<img src="<?php print base_url();?>admin/graphs/monthlystackedgraph/rescheduled" alt="monthly_all" />
					</div>
					<span>Revoked <?php print $period;?></span>
					<div id="revoked_monthly" class="stat_box">
						<img src="<?php print base_url();?>admin/graphs/monthlystackedgraph/revoked" alt="monthly_all" />
					</div>
					<span>Archived <?php print $period;?></span>
					<div id="noshow_monthly" class="stat_box">
						<img src="<?php print base_url();?>admin/graphs/monthlystackedgraph/archived" alt="monthly_all" />
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>