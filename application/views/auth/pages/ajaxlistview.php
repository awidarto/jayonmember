<script>
	var asInitVals = new Array();

	$(document).ready(function() {

	    var oTable = $('.dataTable').dataTable(
			{
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "<?php print site_url($ajaxurl);?>",
				"oLanguage": { "sSearch": "Search "},
                "sPaginationType": "full_numbers",
                "sDom": 'T<"clear">lfrtip',
			<?php if($this->config->item('infinite_scroll')):?>
				"bScrollInfinite": true,
			    "bScrollCollapse": true,
			    "sScrollY": "500px",
			<?php endif; ?>
			<?php if(isset($sortdisable)):?>
				"aoColumnDefs": [
				    { "bSortable": false, "aTargets": [ <?php print $sortdisable; ?> ] }
				 ],
			<?php endif;?>
			    "fnServerData": function ( sSource, aoData, fnCallback ) {
                    aoData.push({
                        'name':'dateFrom', 'value': $('#date_from').val()
                    });
                    aoData.push({
                        'name':'dateTo', 'value': $('#date_to').val()
                    });
		            $.ajax( {
		                "dataType": 'json',
		                "type": "POST",
		                "url": sSource,
		                "data": aoData,
		                "success": fnCallback
		            } );
		        }
			}
		);

		$('tfoot input').keyup( function () {
			/* Filter on the column (the index) of this element */
			oTable.fnFilter( this.value, $('tfoot input').index(this) );
		} );

		/*
		 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
		 * the footer
		 */
		$('tfoot input').each( function (i) {
			asInitVals[i] = this.value;
		} );

		$('tfoot input').focus( function () {
			if ( this.className == 'search_init' )
			{
				this.className = '';
				this.value = '';
			}
		} );

		$('tfoot input').blur( function (i) {
			if ( this.value == '' )
			{
				this.className = 'search_init';
				this.value = asInitVals[$('tfoot input').index(this)];
			}
		} );

		$('table.dataTable').click(function(e){
			if($(e.target).is('.thumb')){
				var delivery_id = e.target.alt;
				var currentTime = new Date();
				$.fancybox.open([
			        {
			            href : '<?php print $this->config->item('admin_url');?>public/receiver/' + delivery_id + '.jpg?' + currentTime.getTime(),
			            title : delivery_id
			        }
			    ]);

			}

            if($(e.target).is('.thumb_multi')){
                var delivery_id = e.target.alt;
                var currentTime = new Date();

                var images = [];

                $('.gal_' + delivery_id).each(function(el){

                    var pic_url = $(this).val() + '?' + currentTime.getTime();

                    if($(this).val().indexOf('http:') == -1){
                        pic_url = '<?php print $this->config->item('admin_url');?>public/receiver/' + $(this).val() + '?' + currentTime.getTime();
                    }

                    images.push(
                        {
                            href : pic_url,
                            title : delivery_id
                        }
                    );
                });

                $.fancybox.open(images);

            }

			if ($(e.target).is('.cancel_link')) {
				var delivery_id = e.target.id;
				var answer = confirm("Are you sure you want to archive this order ?");
				if (answer){
					$.post('<?php print site_url('admin/delivery/ajaxarchive');?>',{'delivery_id':delivery_id}, function(data) {
						if(data.result == 'ok'){
							//redraw table
							oTable.fnDraw();
							alert(delivery_id + " archived");
						}
					},'json');
				}else{
					alert(delivery_id + " not archived");
				}
		   	}

            if ($(e.target).is('.printslip')) {
                var delivery_id = e.target.id;
                $('#print_id').val(delivery_id);
                var src = '<?php print base_url() ?>admin/prints/deliveryslip/' + delivery_id;

                $('#print_frame').attr('src',src);
                $('#print_dialog').dialog('open');
            }

			if ($(e.target).is('.view_detail')) {
				var delivery_id = e.target.id;
				var src = '<?php print base_url() ?>admin/prints/deliveryview/' + delivery_id;

				$('#view_frame').attr('src',src);
				$('#view_dialog').dialog('open');
			}

            if ($(e.target).is('.view_log')) {
                var delivery_id = e.target.id;
                var src = '<?php print base_url() ?>admin/log/deliverylog/' + delivery_id;

                $('#view_dialog').attr('title','Delivery Log : ' + delivery_id);
                $('#ui-dialog-title-view_dialog').html('Delivery Log : ' + delivery_id);
                $('#view_frame').attr('src',src);
                $('#view_dialog').dialog('open');
            }

		});

        $('#date_from').datepicker({ dateFormat: 'yy-mm-dd' });
        $('#date_to').datepicker({ dateFormat: 'yy-mm-dd' });

        $('#date_from').on('change',function(){
            oTable.fnDraw();
        })

        $('#date_to').on('change',function(){
            oTable.fnDraw();
        })

		$('#search_timestamp').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#search_reporttime').datepicker({ dateFormat: 'yy-mm-dd' });

		$('#search_timestamp').change(function(){
			oTable.fnFilter( this.value, $('tfoot input').index(this) );
		});

		$('#search_reporttime').change(function(){
			oTable.fnFilter( this.value, $('tfoot input').index(this) );
		});

		$('#doArchive').click(function(){
			var assigns = '';
			var count = 0;
			$('.assign_check:checked').each(function(){

				var deliverydate = $('#dt_'+this.value).html();
				var status = $('#st_'+this.value).html();
				assigns += '<li style="padding:5px;border-bottom:thin solid grey;margin-left:0px;"><strong>'+this.value + '</strong><br />' + deliverydate +' '+ status+'</li>';
				count++;
			});

			if(count > 0){
				$('#archive_list').html(assigns);
				$('#archive_dialog').dialog('open');
			}else{
				alert('Please select one or more delivery orders');
			}
		});

        $('#download-csv').on('click',function(){
            var flt = $('tfoot td input, tfoot td select');
            var dlfilter = [];

            flt.each(function(){
                var name = this.name;
                var val = this.value;
                dlfilter.push({ name : name, value : val });
            });

            dlfilter.push({
                'name':'dateFrom', 'value': $('#date_from').val()
            });
            dlfilter.push({
                'name':'dateTo', 'value': $('#date_to').val()
            });

            console.log(dlfilter);

            var sort = oTable.fnSettings().aaSorting;
            console.log(sort);

            $('#download-csv').html('Processing...')

            $.post('<?php print base_url() ?>admin/dl/delivered',
                {
                    datafilter : dlfilter,
                    sort : sort[0],
                    sortdir : sort[1]
                },
                function(data) {
                    $('#download-csv').html('Download Excel');
                    if(data.status == 'OK'){
                        console.log(data.data.urlcsv);
                        window.location.href = data.data.urlcsv;
                    }


                },'json');

            //return false;
            event.preventDefault();
        });


		$('#archive_dialog').dialog({
			autoOpen: false,
			height: 300,
			width: 400,
			modal: true,
			buttons: {
				"Archive Delivery Orders": function() {
					var delivery_ids = [];
					var laststatus = [];
					i = 0;
					$('.assign_check:checked').each(function(){
						delivery_ids[i] = $(this).val();
						laststatus[i] = $(this).attr('title');
						i++;
					});
					$.post('<?php print site_url('admin/delivery/ajaxarchive');?>',{ assignment_date: $('#assign_deliverytime').val(),'delivery_id[]':delivery_ids,'laststatus[]':laststatus}, function(data) {
						if(data.result == 'ok'){
							//redraw table
							oTable.fnDraw();
							$('#archive_dialog').dialog( "close" );
						}
					},'json');
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				//allFields.val( "" ).removeClass( "ui-state-error" );
				$('#archive_list').html('');
			}
		});

        $('#print_dialog').dialog({
            autoOpen: false,
            height: 600,
            width: 1050,
            modal: true,
            buttons: {
                /*
                Print: function(){
                    var pframe = document.getElementById('print_frame');
                    var pframeWindow = pframe.contentWindow;
                    pframeWindow.print();
                },
                "Download PDF": function(){
                    var print_id = $('#print_id').val();
                    var src = '<?php print base_url() ?>admin/prints/deliveryslip/' + print_id + '/pdf';
                    window.location = src;
                    //alert(src);
                },
                */
                Close: function() {
                    $( this ).dialog( "close" );
                }
            },
            close: function() {

            }
        });

		$('#view_dialog').dialog({
			autoOpen: false,
			height: 600,
			width: 900,
			modal: true,
			buttons: {
				Print: function(){
					var pframe = document.getElementById('view_frame');
					var pframeWindow = pframe.contentWindow;
					pframeWindow.print();
				},
				Close: function() {
					oTable.fnDraw();
					$( this ).dialog( "close" );
				}
			},
			close: function() {

			}
		});

	});
</script>
<?php if(isset($add_button)):?>
	<div class="button_nav">
		<?php echo anchor($add_button['link'],$add_button['label'],'class="button add"')?>
	</div>
<?php endif;?>
    <div class="button_nav">
        <label for="from_date">From</label>
        <input type="text" id="date_from" name="from_date" value="<?php print date('Y-m-d',time()) ?>" />
        <label for="from_date">To</label>
        <input type="text" id="date_to" value="<?php print date('Y-m-d',time()) ?>" />
        <span id="download-csv" class="button" style="cursor:pointer">
            Download Excel
        </span>
    </div>
<br />
<?php echo $this->table->generate(); ?>

<div id="archive_dialog" title="Archive Delivery Orders">
	<table style="width:100%;border:0;margin:0;">
		<tr>
			<td style="width:250px;vertical-align:top">
				Delivery Orders :
			</td>
		</tr>
		<tr>
			<td style="overflow:auto;width:250px;vertical-align:top">
				<ul id="archive_list" style="border-top:thin solid grey;list-style-type:none;padding-left:0px;"></ul>
			</td>
		</tr>
	</table>
</div>

<div id="print_dialog" title="Print" style="overflow:hidden;padding:8px;">
    <input type="hidden" value="" id="print_id" />
    <iframe id="print_frame" name="print_frame" width="100%" height="100%"
    marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto"
    title="Dialog Title">Your browser does not suppr</iframe>
</div>

<div id="view_dialog" title="Order Detail" style="overflow:hidden;padding:8px;">
	<input type="hidden" value="" id="print_id" />
	<iframe id="view_frame" name="print_frame" width="100%" height="100%"
    marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto"
    title="Dialog Title">Your browser does not suppr</iframe>
</div>
