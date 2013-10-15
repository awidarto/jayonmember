<html>
<head>
    <title>Delivery Details</title>
    <style>

        html{margin:0px;}

        body{margin:5px;
            font-size: 12px;
        }

        #wrapper{
            width:850px;
            margin:5px;
            display:block;
            font-family:'Trebuchet Ms', 'Yanone Kaffeesatz', Lato, Lobster, 'Lobster Two','Droid Sans', Helvetica ;
            font-size:13px;
            text-align:left;
        }

        h2{
            margin:0px;
            padding-top:15px;
        }

        td{
            font-size: 12px;
        }

        .dataTable{
            width:100%;
            font-family:'Trebuchet Ms', 'Yanone Kaffeesatz', Lato, Lobster, 'Lobster Two','Droid Sans', Helvetica ;
            margin-top:8px;

        }

        .dataTable td{
            border-bottom:thin solid #eee;
            text-align:left;
            font-size: 11px;
        }

        .dataTable th{
            text-align:left;
            padding-right:15px;
            font-size:12px;
            font-weight: bold;
            border-top:thin solid #eee;
            border-bottom:thin solid #eee;
            /*border-left:thin solid #eee;*/
        }

        .dataTable tr>th{
            width:20px;

        }

        .dataTable tr>th:last-child{
            width:100px;
            /*border-right:thin solid #eee;*/
        }

        .dataTable td{
            /*border-left:thin solid #eee;*/
            border-bottom:thin solid #eee;
        }

        .dataTable td:last-child{
            /*border-right:thin solid #eee;*/
            text-align:right;
        }

        #jayon_logo{
            vertical-align:top;
            font-family:'Trebuchet Ms', 'Yanone Kaffeesatz', Lato, Lobster, 'Lobster Two','Droid Sans', Helvetica ;
            font-size: 11px;
            text-align:left;
        }

        #jayon_logo img{
            width:170px;
        }

        #order_detail,#merchant_detail{
            vertical-align:top;
            padding-top:0px;

        }

        #order_detail h2{
            text-align: center;
        }

        #merchant_detail{
            margin:0px;
            padding:8px;
        }

        #mainInfo tr>td:first-child, #orderInfo tr>td:first-child{
            width:150px;
        }

        table#main_table{
            width:840px;
            padding:0px;
            margin:0px;
        }

        .row_label{
            width:150px;
        }

        table#signBox{
            font-size: 12px;
            margin-top:15px;
            width:840px;
        }

        #signBox th{
            width:100px;
            vertical-align:top;
            border-top: thin solid #eee;
            border-bottom: thin solid #eee;
            /*border-right:thin solid #eee;*/
            margin:2px;
        }

        #sign_name td{
            border-top: thin solid #eee;
            border-bottom: thin solid #eee;
            /*border-right:thin solid #eee;*/
        }

        tr#sign_name td:first-child{
            /*border-left: thin solid #eee;*/
        }

        #signBox th:first-child{
            /*border-left: thin solid #eee;*/
        }

        #mainInfo tr td:last-child, #orderInfo tr td:last-child{
            border-bottom: thin solid #eee;
            /*border-left:thin solid #eee;*/
        }

        #mainInfo td{
            vertical-align:top;
        }

        h2{
            font-size: 18px;
            display: block;
            text-align: center;
        }

        #order_slot{
            margin-left:20px;
            float:right;
        }

    </style>
    <?php echo $this->ag_asset->load_script('jquery-1.7.1.min.js');?>

    <script>
        $(document).ready(function() {
            $('#set_weight').click(function(){
                $('#weight_option').show();
            });

            $('#save_weight').click(function(){
                $('#loader').show();
                $.post('<?php print site_url('ajax/saveweight');?>',
                { delivery_id: $('#delivery_id').val(),weight_tariff:$('#package_weight').val()},
                function(data) {
                    $('#loader').hide();
                    if(data.status == 'OK'){
                        $('#weight').html(data.weight_range);
                        $('#weight_option').hide();
                        $('#delivery_cost').html(data.delivery_cost);
                        $('#total_charges').html(data.total_charges);
                        alert('Weight info updated.')
                    }else if(data.status == 'ERR'){
                        $('#weight_option').hide();
                        alert('Failed to update weight info.')
                    }
                },'json');
            });

            $('#cancel_weight').click(function(){
                $('#weight_option').hide();
            });

            $('#set_delivery').click(function(){
                $('#delivery_option').show();
            });

            $('#save_delivery').click(function(){
                $('#loader').show();
                $.post('<?php print site_url('ajax/savedeliverytype');?>',
                { delivery_id: $('#delivery_id').val(),delivery_type:$('#delivery_type_select').val()},
                function(data) {
                    $('#loader').hide();
                    if(data.status == 'OK'){
                        $('#delivery_type').html(data.delivery_type);
                        $('#delivery_option').hide();
                        $('#cod_cost').html(data.cod_cost);
                        $('#total_charges').html(data.total_charges);
                        alert('Delivery type updated.')
                    }else if(data.status == 'ERR'){
                        $('#delivery_option').hide();
                        alert('Failed to update delivery type.')
                    }
                },'json');
            });

            $('#cancel_delivery').click(function(){
                $('#delivery_option').hide();
            });


            $('#show_merchant').change(function(){

                var currentsw = $('#show_merchant').is(':checked');
                var id = $('#show_merchant').val();

                if(currentsw == true){
                    nextsw = 'On';
                }else{
                    nextsw = 'Off';
                }

                var answer = confirm("Switch merchant name display " + nextsw + " ?");
                if (answer){
                    $.post('<?php print site_url('ajax/toggle');?>',{'id':id,'switchto':nextsw,'field':'show_merchant'}, function(data) {
                        if(data.result == 'ok'){

                        }
                    },'json');
                }else{
                    alert("Switch cancelled");
                }

            });

            $('#show_shop').change(function(){

                var currentsw = $('#show_shop').is(':checked');
                var id = $('#show_shop').val();

                if(currentsw == true){
                    nextsw = 'On';
                }else{
                    nextsw = 'Off';
                }

                var answer = confirm("Switch store name display " + nextsw + " ?");
                if (answer){
                    $.post('<?php print site_url('ajax/toggle');?>',{'id':id,'switchto':nextsw,'field':'show_shop'}, function(data) {
                        if(data.result == 'ok'){

                        }
                    },'json');
                }else{
                    alert("Switch cancelled");
                }

            });

        });
    </script>

</head>
<body>
<div id="wrapper">
    <table id="main_table">
        <tbody>
            <tr>
                <td id="merchant_detail">
                    <table border="0" cellpadding="4" cellspacing="0" id="mainInfo">
                        <tbody>

                            <tr>
                                <td colspan="2"><?php print $qr;?><br /><strong>Merchant Info</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    Merchant Name:<br />
                                    <span class="fine"><?php print form_checkbox(array('name'=>'show_merchant','id'=>'show_merchant','value'=>$main_info['delivery_id'],'checked'=>$main_info['show_merchant'] ));?> Show in delivery slip</span>
                                </td>
                                <td>
                                    <?php print $main_info['merchant'];?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Online Store:<br />
                                    <span class="fine"><?php print form_checkbox(array('name'=>'show_shop','id'=>'show_shop','value'=>$main_info['delivery_id'],'checked'=>$main_info['show_shop'] ));?> Show in delivery slip</span>
                                </td>
                                <td>
                                    <?php print $main_info['app_name'];?>
                                </td>
                            </tr>
                            <tr>
                                <td>Transaction ID:</td>
                                <td><?php print $main_info['merchant_trans_id'];?></td>
                            </tr>
<?php
/*
    [mc_email] => ganti@bajuresmi.net.com.id
    [mc_street] => 2345678
    [mc_district] => Kebayoran
    [mc_city] => Jakarta Selatan
    [mc_province] => DKI
    [mc_country] => Indonesia
    [mc_zip] => 1234578
    [mc_phone] => 08765432
    [mc_mobile] => 2345678
    [contact_person]
*/

    //print_r($main_info);
$merchant_info = '';
$merchant_info = ($main_info['m_pic']=='')?$main_info['mc_pic'].'<br />':$main_info['m_pic'].'<br />';
$merchant_info .= ($main_info['m_street']=='')?$main_info['mc_street'].'<br />': $main_info['m_street'].'<br />';
$merchant_info .= ($main_info['m_district'] == '')?$main_info['mc_district'].'<br />':$main_info['m_district'].'<br />';
$merchant_info .= ($main_info['m_city'] == '')?$main_info['mc_city'].',':$main_info['m_city'].',';
$merchant_info .= ($main_info['m_zip']=='')?$main_info['mc_zip'].'<br />':$main_info['m_zip'].'<br />';
$merchant_info .= ($main_info['m_country']=='')?$main_info['mc_country'].'<br />':$main_info['m_country'].'<br />';
$merchant_info .= ($main_info['m_phone'] == '')?'Phone : '.$main_info['mc_phone']:'Phone : '.$main_info['m_phone'];


?>
                            <tr>
                                <td>Store Detail:</td>
                                <td><?php print trim($merchant_info);?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td id="order_detail">
                    <table width="100%" cellpadding="4" cellspacing="0" id="orderInfo">
                        <tbody>
                            <tr>
                                <td colspan="2"><strong>Delivery Info</strong></td>
                            </tr>
                            <tr>
                                <td class="row_label">Delivery Number:</td>
                                <td><?php print $main_info['delivery_id'];?>
                                    <input type="hidden" id="delivery_id" value="<?php print $main_info['delivery_id']?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>Delivery Date:</td>
                                <td><?php print $main_info['assignment_date'];?> <span id="order_slot">Order Slot: <?php print $main_info['assignment_timeslot'];?></span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Order Detail</strong></td>
                            </tr>
                            <tr>
                                <td class="row_label">Delivery Type:</td>
                                <td><span id="delivery_type"><?php print $main_info['delivery_type'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span id="set_delivery" style="cursor:pointer;text-decoration: underline;">set delivery type</span>
                                    <div id="delivery_option" style="display:none">
                                        <?php print $typeselect; ?>&nbsp;&nbsp;&nbsp;&nbsp;<span id="save_delivery" style="cursor:pointer;text-decoration: underline;">save</span>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <span id="cancel_delivery" style="cursor:pointer;text-decoration: underline;">cancel</span>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="row_label">Delivered To:</td>
                                <td><?php print ($main_info['recipient_name'] == "")?$main_info['buyer_name']:$main_info['recipient_name'];?></td>
                            </tr>
                            <tr>
                                <td>Shipping Address:</td>
                                <td><?php print $main_info['shipping_address'];?></td>
                            </tr>
                            <tr>
                                <td>Contact Number:</td>
                                <td>
                                    <?php
                                        print $main_info['phone'].'<br />';
                                        print $main_info['mobile1'].'<br />';
                                        print $main_info['mobile2'].'<br />';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Package Detail</strong></td>
                            </tr>

                            <tr>
                                <td class="row_label">Dimension:</td>
                                <td><?php print $main_info['width'].' cm x '.$main_info['height'].' cm x '.$main_info['length'].' cm';?></td>
                            </tr>

                            <tr>
                                <td class="row_label">Weight:</td>
                                <td><?php print ($main_info['weight'] == 0)?'<span id="weight">Unspecified</span>':'<span id="weight">'.get_weight_range($main_info['weight']).'</span>';?>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span id="set_weight" style="cursor:pointer;text-decoration: underline;">set weight</span>
                                    <div id="weight_option" style="display:none">
                                        <?php print $weightselect; ?>&nbsp;&nbsp;&nbsp;&nbsp;<span id="save_weight" style="cursor:pointer;text-decoration: underline;">save</span>&nbsp;&nbsp;&nbsp;&nbsp;<span id="cancel_weight" style="cursor:pointer;text-decoration: underline;">cancel</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <?php //echo $this->table->generate(); ?>
                    <?php print $detail_table->generate(); ?>
                </td>
            </tr>
        </tbody>
    </table>

<!--
    <table border="0" cellpadding="4" cellspacing="0" id="signBox">
        <thead>
            <tr>
                <th>Created By</th>
                <th>Online Store</th>
                <th>Goods Received By</th>
                <th>Cash Received By</th>
                <th>Reporting</th>
                <th>Staff Dispatch Admin</th>
                <th>Finance</th>
                <th>Courier</th>
            </tr>
        </thead>
        <tbody>
            <tr style="height:40px;">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr id="sign_name">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
-->
</div>
</body>
</html>
