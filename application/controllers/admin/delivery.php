<?php

class Delivery extends Application
{

	public function __construct()
	{
		parent::__construct();
		$this->ag_auth->restrict('admin'); // restrict this controller to admins only
		$this->table_tpl = array(
			'table_open' => '<table border="0" cellpadding="4" cellspacing="0" class="dataTable">'
		);
		$this->table->set_template($this->table_tpl);

		$this->breadcrumb->add_crumb('Home','admin/dashboard');

	}

	public function ajaxincoming(){

		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		$sort_col = ($this->input->post('iSortCol_0') == '')?0:$this->input->post('iSortCol_0');
		$sort_dir = ($this->input->post('sSortDir_0') == '')?'desc':$this->input->post('sSortDir_0');

		$columns = array(
			'buyerdeliverytime',
			'buyerdeliveryzone',
			'buyerdeliverycity',
			'delivery_id',
			'merchant_trans_id',
			'app_name',
			'merchant',
			'buyer',
			'shipping_address',
			'delivery_type',
			'delivery_cost',
			'cod_cost',
			'weight',
			'phone',
			'status'
			);

		$this->db->select('*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name');
		$this->db->join('members as b',$this->config->item('incoming_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('incoming_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('incoming_delivery_table').'.application_id=b.id','left');

		//search column
        $search = false;
                //search column
        if($this->input->post('sSearch') != ''){
            $srch = $this->input->post('sSearch');
            //$this->db->like('buyerdeliveryzone',$srch);
            $this->db->or_like('buyerdeliverytime',$srch);
            $this->db->or_like('delivery_id',$srch);
            $search = true;
        }


        if($this->input->post('sSearch_0') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.ordertime',$this->input->post('sSearch_0'));
            $search = true;
        }

        if($this->input->post('sSearch_1') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.buyerdeliverytime',$this->input->post('sSearch_1'));
            $search = true;
        }


        if($this->input->post('sSearch_2') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.buyerdeliveryslot',$this->input->post('sSearch_2'));
            $search = true;
        }

        if($this->input->post('sSearch_3') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.buyerdeliveryzone',$this->input->post('sSearch_3'));
            $search = true;
        }

        if($this->input->post('sSearch_4') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.buyerdeliverycity',$this->input->post('sSearch_4'));
            $search = true;
        }

        if($this->input->post('sSearch_5') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.shipping_address',$this->input->post('sSearch_5'));
            $search = true;
        }

        if($this->input->post('sSearch_6') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.merchant_trans_id',$this->input->post('sSearch_6'));
            $search = true;
        }

        if($this->input->post('sSearch_7') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.delivery_type',$this->input->post('sSearch_7'));
            $search = true;
        }

        if($this->input->post('sSearch_8') != ''){
            if($search == true){
                $this->db->and_();
            }
            $this->db->group_start();
            $this->db->like('a.application_name',$this->input->post('sSearch_8'));
            $this->db->or_like('m.merchantname',$this->input->post('sSearch_8'));
            $this->db->group_end();
            $search = true;
        }

        if($this->input->post('sSearch_9') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.delivery_id',$this->input->post('sSearch_9'));
            $search = true;
        }

        if($this->input->post('sSearch_10') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.status',$this->input->post('sSearch_10'));
            $search = true;
        }

        if($this->input->post('sSearch_11') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.directions',$this->input->post('sSearch_11'));
            $search = true;
        }

        if($this->input->post('sSearch_12') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.delivery_cost',$this->input->post('sSearch_12'));
            $search = true;
        }

        if($this->input->post('sSearch_13') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.cod_cost',$this->input->post('sSearch_13'));
            $search = true;
        }

        if($this->input->post('sSearch_14') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.chargeable_amount',$this->input->post('sSearch_14'));
            $search = true;
        }

        if($this->input->post('sSearch_15') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.buyer_name',$this->input->post('sSearch_15'));
            $search = true;
        }

        if($this->input->post('sSearch_16') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.shipping_zip',$this->input->post('sSearch_16'));
            $search = true;
        }


        if($this->input->post('sSearch_17') != ''){
            if($search == true){
                $this->db->and_();
            }
            $this->db->group_start();
            $this->db->like($this->config->item('incoming_delivery_table').'.phone',$this->input->post('sSearch_17'));
            $this->db->or_like($this->config->item('incoming_delivery_table').'.mobile1',$this->input->post('sSearch_17'));
            $this->db->or_like($this->config->item('incoming_delivery_table').'.mobile2',$this->input->post('sSearch_17'));
            $this->db->group_end();

            $search = true;
        }



		if($search){
			//$this->db->and_();
		}

		$this->db
			->where($this->config->item('incoming_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->and_()->group_start()
			->where($this->config->item('incoming_delivery_table').'.status',$this->config->item('trans_status_new'))
			->or_where($this->config->item('incoming_delivery_table').'.status',$this->config->item('trans_status_confirmed'))
			->or_where($this->config->item('incoming_delivery_table').'.status',$this->config->item('trans_status_canceled'))
			->not_like($this->config->item('incoming_delivery_table').'.status','assigned','before')
			->group_end();

        $dbca = clone $this->db;

		$this->db->order_by($this->config->item('incoming_delivery_table').'.id','desc')
			->order_by($this->config->item('incoming_delivery_table').'.created','desc')
			->order_by('buyerdeliverytime','desc')
			->order_by($columns[$sort_col],$sort_dir);

        $dbcr = clone $this->db;

        $this->db->limit($limit_count, $limit_offset);

        $data = $this->db->get($this->config->item('incoming_delivery_table'));

		$last_query = $this->db->last_query();

		$result = $data->result_array();

        $count_all = $dbca->count_all_results($this->config->item('incoming_delivery_table'));
        $count_display_all = $dbcr->count_all_results($this->config->item('incoming_delivery_table'));

		$aadata = array();

        $num = $limit_offset;

		foreach($result as $value => $key)
		{

            $num++;

			$delete = anchor("admin/delivery/delete/".$key['delivery_id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links
			$assign = anchor("admin/delivery/assign/".$key['delivery_id']."/", "Assign"); // Build actions links
			$cancel = '<span class="cancel_link" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">Cancel</span>';
			$reschedule = '<span class="reschedule_link" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">Reschedule</span>';
			$revoke = '<span class="revoke_link" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">Revoke</span>';
			$purge = '<span class="purge_link" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">Purge</span>';
            $printslip = '<span class="printslip" id="'.$key['delivery_id'].'" style="cursor:pointer;text-decoration:underline;" >Preview Slip</span>';
            $printlabel = '<span class="printlabel" id="'.$key['delivery_id'].'" style="cursor:pointer;text-decoration:underline;" >Print Label</span>';

			$app = $this->get_app_info($key['application_key']);

			$lessday = ((strtotime($key['buyerdeliverytime']) - time()) < (get_option('auto_lock_hours')*60*60))?true:false;
			$lessday = ($key['buyerdeliverytime'] === '0000-00-00 00:00:00')?false:$lessday;

			if($lessday){
				$reqdate = '<span class="red">'.$key['buyerdeliverytime'].'</span>';
			}else{
				$reqdate = $key['buyerdeliverytime'];
			}

			$reference = '';

			if($key['reschedule_ref'] != ''){
				$reference = $key['reschedule_ref'];
			}
			if($key['revoke_ref'] != ''){
				$reference = $key['revoke_ref'];
			}

            $orderno = explode('-',$key['delivery_id']);
            $orderno = array_pop($orderno);


			$deliveryidfield = ($key['status'] == $this->config->item('trans_status_canceled'))?$key['delivery_id']:form_checkbox('assign[]',$key['delivery_id'],FALSE,'class="assign_check"').'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$orderno.'</span>';

			$deliverytypefield = '<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$key['delivery_type'].'</span>';

			$weightfield = ($key['weight'] == 0)?'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">unspecified</span>':get_weight_range($key['weight']);
            //from ext
            //if($key['status'] == $this->config->item('trans_status_canceled')){
            //    $delivery_check = '<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$key['delivery_id'].'</span>';
            //}else{
                $delivery_check = form_checkbox('assign[]',$key['delivery_id'],FALSE,'class="assign_check"').'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$key['delivery_id'].'</span>';
            //}

            $volume = (double)$key['width']*(double)$key['height']*(double)$key['length'];


            $lat = ($key['latitude'] == 0)? 'Set Loc':$key['latitude'];
            $lon = ($key['longitude'] == 0)? '':$key['longitude'];

            $style = 'style="cursor:pointer;padding:2px;display:block;"';
            $class = ($lat == 'Set Loc')?' red':'';

            $direction = $key['directions'];//.'<br />';
                //.'<span id="'.$key['id'].'" '.$style.' class="locpick'.$class.'">'.$lat.' '.$lon.'</span>';

            if(file_exists(FCPATH.'public/pickup/'.$key['merchant_trans_id'].'_address.jpg')){
                $picture = ($key['pic_address'] = '')?'':'<img src="'.base_url().'public/pickup/'.$key['merchant_trans_id'].'_address.jpg" style="width:100px;height:auto">';
            }else{
                $picture = '';
            }

            $app_name = (isset($app['application_name']))?$app['application_name']:'-';

            if($key['toscan'] == 1){
                $markscan = '<img src="'.base_url().'admin/prints/barcode/'.$key['merchant_trans_id'].'" style="width:100px;height:auto">';
                //$markscan = '<img src="'.base_url().'assets/images/barcode-icon.png" style="width:25px;height:auto">';
                $pick_stat = colorizestatus($key['pickup_status']);
            }else{
                $markscan = '';
                $pick_stat = '';
            }

            $key['status'] = ($key['status'] == 'pending')?$this->config->item('trans_status_tobeconfirmed'):$key['status'];

			$aadata[] = array(

                $num,
                $key['ordertime'],
                $picture,
                $key['pickup_person'],
                $key['pickup_dev_id'],
                '<span id="'.$key['delivery_id'].'"><input type="hidden" value="'.$key['buyerdeliverytime'].'" id="cd_'.$key['delivery_id'].'">'.$reqdate.'</span>',
                get_slot_range($key['buyerdeliveryslot']),
                $key['buyerdeliveryzone'],
                $key['buyerdeliverycity'],
                $key['shipping_address'],
                $this->hide_trx($key['merchant_trans_id']).$markscan,
                colorizetype($key['delivery_type']),
                '<b>'.$key['merchant'].'</b><br />'.$app_name,
                ($key['status'] == 'canceled')?$printslip.'<br /><br />'.$printlabel:$printslip.'<br /><br />'.$printlabel.'<br /><br />'.$reschedule,
                //$printslip.'<br /><br />'.$reschedule.'<br />'.$changestatus,
                $delivery_check,
                colorizestatus($key['status']).'<br />'.$pick_stat,
                $direction,
                $key['width'].' x '.$key['height'].' x '.$key['length'].' = '.$volume,
                //(double)$key['width']*(double)$key['height']*(double)$key['length'],
                get_weight_range($key['weight'],$key['application_id']),
                $key['delivery_cost'],
                ($key['delivery_type'] == 'COD')?$key['cod_cost']:'',
                ($key['delivery_type'] == 'COD')?(double)$key['chargeable_amount']:'',
                //$key['merchant'],
                //$app['domain'],
                $key['buyer_name'],
                $key['shipping_zip'],
                $key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
                $reference

                /*
                $num,
                $key['ordertime'],
                $picture,
                $key['pickup_person'],
                $key['pickup_dev_id'],
                '<span id="'.$key['delivery_id'].'"><input type="hidden" value="'.$key['buyerdeliverytime'].'" id="cd_'.$key['delivery_id'].'">'.$reqdate.'</span>',
                get_slot_range($key['buyerdeliveryslot']),
                $key['buyerdeliveryzone'],
                $key['buyerdeliverycity'],
                $key['shipping_zip'],
                $delivery_check,
                $this->hide_trx($key['merchant_trans_id']).$markscan,
                colorizetype($key['delivery_type']),
                '<b>'.$key['merchant'].'</b><br />'.$app_name,
                $key['width'].' x '.$key['height'].' x '.$key['length'].' = '.$volume,
                //(double)$key['width']*(double)$key['height']*(double)$key['length'],
                get_weight_range($key['weight'],$key['application_id']),
                $key['delivery_cost'],
                ($key['delivery_type'] == 'COD')?$key['cod_cost']:'',
                ($key['delivery_type'] == 'COD')?(double)$key['chargeable_amount']:'',
                //$key['merchant'],
                //$app['domain'],
                $key['buyer_name'],
                $key['shipping_address'],
                $direction,
                $key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
                colorizestatus($key['status']),//.'<br />'.$pick_stat,
                $reference,


				//date('Y-m-d H:i:s',$key['created']),
				/*
                $key['ordertime'],
				'<span id="'.$key['delivery_id'].'"><input type="hidden" value="'.$key['buyerdeliverytime'].'" id="cd_'.$key['delivery_id'].'">'.$reqdate.'</span>',
				get_slot_range($key['buyerdeliveryslot']),
				$key['buyerdeliveryzone'],
				$key['buyerdeliverycity'],
				$deliveryidfield,
				$this->hide_trx($key['merchant_trans_id']),
				$app['application_name'],
				$key['merchant'],
				//$app['domain'],
				$key['buyer_name'],
				$key['shipping_address'],
				$deliverytypefield,
				$key['delivery_cost'],
				$key['cod_cost'],
				$weightfield,
				//$key['phone'],
				colorizestatus($key['status']),
				$reference,
				($key['status'] == 'canceled')?$printslip:$printslip.'<br /><br />'.$reschedule
				//$key['reschedule_ref'],
				//$key['revoke_ref'],
				//($key['status'] === 'confirm')?$assign:''.' '.$edit.' '.$delete
                */

			);
		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata,
			'lastquery'=>$last_query

		);

		print json_encode($result);
	}

	public function incoming()
	{
		$this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
		$this->breadcrumb->add_crumb('Incoming Orders','admin/delivery/incoming');

		$this->table->set_heading(

            '#',
            'Timestamp',
            'Pick Up Picture',
            'Pick Up Person',
            'Pick Up Device',
            'Requested Delivery Date',
            'Requested Time Slot',
            'Zone',
            'City',
            'Shipping Address',
            'No Kode Penjualan Toko',
            'Type',
            'Merchant / App Name',
            'Actions',
            'Delivery ID',
            'Status',
            'Directions',
            'W x H x L = V',
            'Weight Range',
            'Delivery Fee',
            'COD Surcharge',
            'COD Value',
            'Buyer',
            'ZIP',
            'Phone',
            'Reference'
            /*
			'Timestamp',
			'Requested Delivery Date',
			'Requested Time Slot',
			'Zone',
			'City',
			'Delivery ID',
			'No Kode Penjualan Toko',
			'App Name',
			'Merchant',
			'Buyer',
			'Shipping Address',
			'Type',
			'Delivery Charge',
			'COD Surcharge',
			'Weight',
			'Status',
			'Reference',
			'Actions'
            */

			); // Setting headings for the table

		$this->table->set_footing(
            '',
            '<input type="text" name="search_ordertime" id="search_ordertime" value="Search timestamp" class="search_init" />',
            '',
            '',
            '',
            '<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery time" class="search_init" />',
            '<input type="text" name="search_buyerdeliveryslot" id="search_buyerdeliveryslot" value="Search Slot" class="search_init" />',
            '<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />',
            '<input type="text" name="search_buyerdeliverycity" id="search_buyerdeliverycity" value="Search city" class="search_init" />',
            '<input type="text" name="search_shipping_address" value="Search address" class="search_init" />',
            '<input type="text" name="search_merchantid" value="Search merchant ID" class="search_init" />',
            '<input type="text" name="search_delivery_type" id="search_delivery_type" value="Search type" class="search_init" />',
            '<input type="text" name="search_application_name" id="search_application_name" value="Search app name" class="search_init" />',
            '',
            '<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
            '<input type="text" name="search_status" value="Search status" class="search_init" />',
            '<input type="text" name="search_directions" value="Search direction" class="search_init" />',
            '',
            '',
            '<input type="text" name="search_delivery_cost" id="search_delivery_cost" value="Search cost" class="search_init" />',
            '<input type="text" name="search_cod_cost" id="search_cod_cost" value="Search COD sur." class="search_init" />',
            '<input type="text" name="search_chargeable_amount" id="search_chargeable_amount" value="Search Value" class="search_init" />',
            '<input type="text" name="search_buyer_name" value="Search buyer" class="search_init" />',
            //'<input type="text" name="search_merchant" value="Search merchant" class="search_init" />',
            '<input type="text" name="search_zip" id="search_zip" value="Search ZIP" class="search_init" />',

            '<input type="text" name="search_phone" value="Search phone" class="search_init" />'

            /*
			'',
			'<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery time" class="search_init" />',
			'',
			'<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />',
			'<input type="text" name="search_city" id="search_city" value="Search city" class="search_init" />',
			'<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
			'<input type="text" name="search_transid" value="Search trans ID" class="search_init" />',
            form_button('do_confirm','Confirm Selection','id="doConfirm"'),
            form_button('do_cancel','Cancel Selection','id="doCancel"')
			*/
			);

		$page['sortdisable'] = '2';
		$page['ajaxurl'] = 'admin/delivery/ajaxincoming';
		$page['page_title'] = 'Incoming Delivery Orders';
		$this->ag_auth->view('incomingajaxlistview',$page); // Load the view
	}

    /* cancelled */

    public function ajaxcanceled(){

        $limit_count = $this->input->post('iDisplayLength');
        $limit_offset = $this->input->post('iDisplayStart');

        $sort_col = $this->input->post('iSortCol_0');
        $sort_dir = $this->input->post('sSortDir_0');

        $columns = array(
            'buyerdeliverytime',
            'buyerdeliveryzone',
            'buyerdeliverycity',
            'zip',
            'delivery_id',
            'merchant_trans_id',
            'app_name',
            'merchant',
            'buyer',
            'shipping_address',
            'phone',
            'status'
            );

        // get total count result
        $count_all = $this->db->count_all($this->config->item('incoming_delivery_table'));

        $count_display_all = $this->db
            ->where($this->config->item('incoming_delivery_table').'.status',$this->config->item('trans_status_canceled'))
            ->not_like($this->config->item('incoming_delivery_table').'.status','assigned','before')
            ->count_all_results($this->config->item('incoming_delivery_table'));

        $this->db->select($this->config->item('incoming_delivery_table').'.*,m.merchantname as merchant,a.application_name as app_name');
        //$this->db->join('members as b',$this->config->item('incoming_delivery_table').'.buyer_id=b.id','left');
        $this->db->join('members as m',$this->config->item('incoming_delivery_table').'.merchant_id=m.id','left');
        $this->db->join('applications as a',$this->config->item('incoming_delivery_table').'.application_id=a.id','left');

        $search = false;
                //search column
        if($this->input->post('sSearch') != ''){
            $srch = $this->input->post('sSearch');
            //$this->db->like('buyerdeliveryzone',$srch);
            $this->db->or_like('buyerdeliverytime',$srch);
            $this->db->or_like('delivery_id',$srch);
            $search = true;
        }

        if($this->input->post('sSearch_0') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.buyerdeliverytime',$this->input->post('sSearch_0'));
            $search = true;
        }


        if($this->input->post('sSearch_1') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.buyerdeliveryzone',$this->input->post('sSearch_1'));
            $search = true;
        }

        if($this->input->post('sSearch_2') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.shipping_zip',$this->input->post('sSearch_2'));
            $search = true;
        }

        if($this->input->post('sSearch_3') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.delivery_id',$this->input->post('sSearch_3'));
            $search = true;
        }

        /*
        if($this->input->post('sSearch_4') != ''){
            $this->db->like($this->config->item('incoming_delivery_table').'.merchant_trans_id',$this->input->post('sSearch_4'));
            $search = true;
        }
        */

        if($this->input->post('sSearch_4') != ''){
            $this->db->like('m.merchantname',$this->input->post('sSearch_4'));
            $search = true;
        }

        $this->db->where($this->config->item('incoming_delivery_table').'.merchant_id',$this->session->userdata('userid'));

        //if($search){
            $this->db->and_();
        //}

        $this->db->group_start()
            ->where($this->config->item('incoming_delivery_table').'.status',$this->config->item('trans_status_canceled'))
            ->not_like($this->config->item('incoming_delivery_table').'.status','assigned','before')
            ->group_end();

        $data = $this->db->limit($limit_count, $limit_offset)
            ->order_by($this->config->item('incoming_delivery_table').'.id','desc')
            ->order_by($this->config->item('incoming_delivery_table').'.ordertime','desc')
            ->order_by('buyerdeliverytime','desc')
            ->order_by($columns[$sort_col],$sort_dir)->get($this->config->item('incoming_delivery_table'));

        //print $this->db->last_query();

        //->group_by(array('buyerdeliverytime','buyerdeliveryzone'))

        $result = $data->result_array();

        $aadata = array();

        $num = $limit_offset;

        foreach($result as $value => $key)
        {
            $num++;

            $delete = anchor("admin/delivery/delete/".$key['delivery_id']."/", "Delete"); // Build actions links
            $edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links
            $assign = anchor("admin/delivery/assign/".$key['delivery_id']."/", "Assign"); // Build actions links
            $cancel = '<span class="cancel_link" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">Cancel</span>';
            $reschedule = '<span class="reschedule_link" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">Reschedule</span>';
            $revoke = '<span class="revoke_link" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">Revoke</span>';
            $purge = '<span class="purge_link" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">Purge</span>';

            $app = $this->get_app_info($key['application_key']);

            $lessday = ((strtotime($key['buyerdeliverytime']) - time()) < (get_option('auto_lock_hours')*60*60))?true:false;
            $lessday = ($key['buyerdeliverytime'] === '0000-00-00 00:00:00')?false:$lessday;

            if($lessday){
                $reqdate = '<span class="red">'.$key['buyerdeliverytime'].'</span>';
            }else{
                $reqdate = $key['buyerdeliverytime'];
            }

            $reference = '';

            if($key['reschedule_ref'] != ''){
                $reference = $key['reschedule_ref'];
            }
            if($key['revoke_ref'] != ''){
                $reference = $key['revoke_ref'];
            }

            if($key['status'] == $this->config->item('trans_status_canceled')){
                $delivery_check = '<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$key['delivery_id'].'</span>';
            }else{
                $delivery_check = form_checkbox('assign[]',$key['delivery_id'],FALSE,'class="assign_check"').'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$key['delivery_id'].'</span>';
            }

            $aadata[] = array(
                $num,
                $key['ordertime'],
                '<span id="'.$key['delivery_id'].'"><input type="hidden" value="'.$key['buyerdeliverytime'].'" id="cd_'.$key['delivery_id'].'">'.$reqdate.'</span>',
                get_slot_range($key['buyerdeliveryslot']),
                $key['buyerdeliveryzone'],
                $key['buyerdeliverycity'],
                $key['shipping_zip'],
                $delivery_check,
//              $this->hide_trx($key['merchant_trans_id']),
                colorizetype($key['delivery_type']),
                $app['application_name'],
                $key['width'].' x '.$key['height'].' x '.$key['length'],
                (double)$key['width']*(double)$key['height']*(double)$key['length'],
                get_weight_range($key['weight'],$key['application_id']),
                $key['delivery_cost'],
                ($key['delivery_type'] == 'COD')?$key['cod_cost']:'',
                ($key['delivery_type'] == 'COD')?(double)$key['chargeable_amount']:'',
                $key['merchant'],
                //$app['domain'],
                $key['buyer_name'],
                $key['shipping_address'],
                $key['directions'],
                $key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
                colorizestatus($key['status']),
                $reference,
                ($key['status'] == 'canceled')?$purge:$reschedule,
                //$key['reschedule_ref'],
                //$key['revoke_ref'],
                //($key['status'] === 'confirm')?$assign:''.' '.$edit.' '.$delete
            );

        }

        $result = array(
            'sEcho'=> $this->input->post('sEcho'),
            'iTotalRecords'=>$count_all,
            'iTotalDisplayRecords'=> $count_display_all,
            'aaData'=>$aadata
        );

        print json_encode($result);
    }

    public function canceled()
    {
        $this->breadcrumb->add_crumb('Orders','admin/delivery/cancelled');
        $this->breadcrumb->add_crumb('Cancelled Orders','admin/delivery/cancelled');

        $this->table->set_heading(
            '#',
            'Timestamp',
            'Requested Delivery Date',
            'Requested Time Slot',
            'Zone',
            'City',
            'ZIP',
            'Delivery ID',
            //'No Kode Penjualan Toko',
            'Type',
            'App Name',
            'W x H x L',
            'Volume',
            'Weight Range',
            'Delivery Fee',
            'COD Surcharge',
            'COD Value',
            'Merchant',
            //'App Domain',
            'Buyer',
            'Shipping Address',
            'Directions',
            'Phone',
            'Status',
            'Reference',
            //'Reschedule Ref',
            //'Revoke Ref',
            'Actions'
            ); // Setting headings for the table

        $this->table->set_footing(
            '',
            '',
            '<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery time" class="search_init" />',
            '',
            '<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />',
            '',
            '<input type="text" name="search_zip" value="Search ZIP" class="search_init" />',
            '<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
            //'<input type="text" name="search_merchantid" value="Search merchant ID" class="search_init" />',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '<input type="text" name="search_merchant" value="Search merchant" class="search_init" />',
            '',//form_button('do_assign','Assign Delivery Date to Selection','id="doAssign"'),
            '',//form_button('do_confirm','Confirm Selection','id="doConfirm"'),
            ''//form_button('do_cancel','Cancel Selection','id="doCancel"')
            );

        $page['sortdisable'] = '0,2';
        $page['ajaxurl'] = 'admin/delivery/ajaxcanceled';
        $page['page_title'] = 'Canceled Delivery Orders';
        $this->ag_auth->view('incomingajaxlistview',$page); // Load the view
    }


	/* zoning */


	public function ajaxzoning(){

		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		$sort_col = $this->input->post('iSortCol_0');
		$sort_dir = $this->input->post('sSortDir_0');

		$columns = array(
			'buyerdeliveryzone',
			'buyerdeliverycity',
			'buyerdeliverytime',
			'delivery_id',
			'app_name',
			'buyer',
			'merchant',
			'merchant_trans_id',
			'shipping_address',
			'phone',
			'status',
			'reschedule_ref',
			'revoke_ref',
			);



		// get total count result
		$count_all = $this->db->count_all($this->config->item('incoming_delivery_table'));

		$count_display_all = $this->db
			->where($this->config->item('incoming_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_admin_dated'))
			->count_all_results($this->config->item('incoming_delivery_table'));

		//search column
		if($this->input->post('sSearch') != ''){
			$srch = $this->input->post('sSearch');
			$this->db->like('buyerdeliveryzone',$srch);
			$this->db->like('buyerdeliverycity',$srch);
			$this->db->or_like('assignment_date',$srch);
			$this->db->or_like('delivery_id',$srch);
		}

		if($this->input->post('sSearch_0') != ''){
			$this->db->like('assignment_date',$this->input->post('sSearch_0'));
		}

		if($this->input->post('sSearch_1') != ''){
			$this->db->like('buyerdeliverycity',$this->input->post('sSearch_2'));
		}

		if($this->input->post('sSearch_2') != ''){
			$this->db->like('buyerdeliveryzone',$this->input->post('sSearch_1'));
		}

		if($this->input->post('sSearch_3') != ''){
			$this->db->like('delivery_id',$this->input->post('sSearch_3'));
		}

        if($this->input->post('sSearch_4') != ''){
            $this->db->like('merchant_trans_id',$this->input->post('sSearch_4'));
        }

		$this->db->select('*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name');
		$this->db->join('members as b',$this->config->item('incoming_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('incoming_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('incoming_delivery_table').'.application_id=b.id','left');

		$data = $this->db
			->where($this->config->item('incoming_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_admin_dated'))
			->limit($limit_count, $limit_offset)
			->order_by('assignment_date','desc')
			->order_by('buyerdeliverycity','asc')
			->order_by($columns[$sort_col],$sort_dir)
			//->group_by('assignment_date,buyerdeliverycity')
			->get($this->config->item('incoming_delivery_table'));

		//->group_by(array('buyerdeliverytime','buyerdeliveryzone'))
		//print $this->db->last_query();

		$result = $data->result_array();

		//print_r($data);

		$aadata = array();

		$bardate = '';

		$barcity = '';

		foreach($result as $value => $key)
		{
			$delete = anchor("admin/delivery/deleteassigned/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links
			$assign = anchor("admin/delivery/assign/".$key['delivery_id']."/", "Assign"); // Build actions links

			$app = $this->get_app_info($key['application_key']);

			$datecheck = form_radio('assign_date',$key['assignment_date'],FALSE,'class="assign_date"').'<strong>'.$key['assignment_date'].'</strong>';

			$citycheck = form_radio('assign_city',$key['buyerdeliverycity'],FALSE,'class="assign_city"').'<strong>'.$key['buyerdeliverycity'].'</strong>';

			$datefield = ($bardate == $key['assignment_date'])?'':$datecheck;
			$cityfield = ($barcity == $key['buyerdeliverycity'] && $bardate == $key['assignment_date'])?'':$citycheck;

			$aadata[] = array(
				$datefield,
				'<span id="c_'.$key['delivery_id'].'">'.$cityfield.'</span>',
				'<span id="'.$key['delivery_id'].'">'.$key['buyerdeliveryzone'].'</span>',
				'<input type="hidden" name="assign[]" class="'.$key['assignment_date'].'_'.str_replace(' ', '_', $key['buyerdeliverycity']).'" value="'.$key['delivery_id'].'">'.$key['delivery_id'],
				//form_checkbox('assign[]',$key['delivery_id'],FALSE,'class="'.$key['assignment_date'].'_'.$key['buyerdeliverycity'].'"').$key['delivery_id'],
				//$app['application_name'],
				//$app['domain'],
				$key['buyer'],
				$key['merchant'],
				$key['merchant_trans_id'],
				$key['shipping_address'],
				$key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
				colorizestatus($key['status']),
				//$key['reschedule_ref'],
				//$key['revoke_ref'],
				//($key['status'] == 'confirm')?$assign:''.' '.$edit.' '.$delete
			);

			$bardate = $key['assignment_date'];
			$barcity = $key['buyerdeliverycity'];
		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);

		print json_encode($result);
	}

	public function zoning()
	{
		$this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
		$this->breadcrumb->add_crumb('Device Zone Assignment','admin/delivery/zoning');

		$this->table->set_heading(
			'Delivery Time',
			'City',
			'Zone',
			'Delivery ID',
			//'App Name',
			//'App Domain',
			'Buyer',
			'Merchant',
			'Merchant Trans ID',
			'Shipping Address',
			'Phone',
			'Status'
			//'Reschedule Ref',
			//'Revoke Ref',
			//'Actions'
			); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery time" class="search_init" />',
			'<input type="text" name="search_city" id="search_city" value="Search city" class="search_init" />',
			'<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />',
			'<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
			form_button('do_assign','Assign Selection to Zone / Device','id="doAssign"'),
            '',
            '',
            '',
            '',
            '<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />'
			);

		$page['sortdisable'] = '1,2';
		$page['ajaxurl'] = 'admin/delivery/ajaxzoning';
		$page['page_title'] = 'Device Zone Assignment';
		$this->ag_auth->view('zoneajaxlistview',$page); // Load the view
	}

	public function ajaxcancel(){
		$delivery_id = $this->input->post('delivery_id');

		$actor = 'M:'.$this->session->userdata('userid');

		if(is_array($delivery_id)){
			foreach ($delivery_id as $d) {
				$this->db->where('delivery_id',$d)->update($this->config->item('incoming_delivery_table'),array('status'=>$this->config->item('trans_status_canceled'),'change_actor'=>$actor));

				$data = array(
						'timestamp'=>date('Y-m-d H:i:s',time()),
						'report_timestamp'=>date('Y-m-d H:i:s',time()),
						'delivery_id'=>$d,
						'device_id'=>'',
						'courier_id'=>'',
						'actor_type'=>'MC',
						'actor_id'=>$this->session->userdata('userid'),
						'latitude'=>'',
						'longitude'=>'',
						'status'=>$this->config->item('trans_status_canceled'),
						'notes'=>''
						);

				delivery_log($data);
			}
		}else{
			$this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),array('status'=>$this->config->item('trans_status_canceled'),'change_actor'=>$actor));

			$data = array(
					'timestamp'=>date('Y-m-d H:i:s',time()),
					'report_timestamp'=>date('Y-m-d H:i:s',time()),
					'delivery_id'=>$delivery_id,
					'device_id'=>'',
					'courier_id'=>'',
					'actor_type'=>'MC',
					'actor_id'=>$this->session->userdata('userid'),
					'latitude'=>'',
					'longitude'=>'',
					'status'=>$this->config->item('trans_status_canceled'),
					'notes'=>''
					);

			delivery_log($data);

		}

		print json_encode(array('result'=>'ok'));

		//send_notification('Cancelled Orders',$buyeremail,null,'rescheduled_order_buyer',$edata,null);

	}

	public function ajaxreschedule($condition = 'incoming'){
		// shoud be more complex !! not just updating status, but creating duplicate entry with different date and delivery ID

		$delivery_id = $this->input->post('delivery_id');
		$buyerdeliverytime = $this->input->post('buyerdeliverytime');

		$buyeremail = array();

		$single = true;

		if(is_array($delivery_id)){

			foreach ($delivery_id as $d) {
				$buyeremail[] = $this->do_reschedule($d,$buyerdeliverytime,$this->config->item('trans_status_rescheduled'),$condition);

				$data = array(
						'timestamp'=>date('Y-m-d H:i:s',time()),
						'report_timestamp'=>date('Y-m-d H:i:s',time()),
						'delivery_id'=>$d,
						'device_id'=>'',
						'courier_id'=>'',
						'actor_type'=>'MC',
						'actor_id'=>$this->session->userdata('userid'),
						'latitude'=>'',
						'longitude'=>'',
						'status'=>$this->config->item('trans_status_rescheduled'),
						'notes'=>''
						);

				delivery_log($data);
			}
				$single = false;
		}else{
			$buyeremail = $this->do_reschedule($delivery_id,$buyerdeliverytime,$this->config->item('trans_status_rescheduled'),$condition);

				$data = array(
						'timestamp'=>date('Y-m-d H:i:s',time()),
						'report_timestamp'=>date('Y-m-d H:i:s',time()),
						'delivery_id'=>$delivery_id,
						'device_id'=>'',
						'courier_id'=>'',
						'actor_type'=>'MC',
						'actor_id'=>$this->session->userdata('userid'),
						'latitude'=>'',
						'longitude'=>'',
						'status'=>$this->config->item('trans_status_rescheduled'),
						'notes'=>''
						);

				delivery_log($data);
		}

		//print_r($buyeremail);

		$edata = array();

		if($single){
			$edata = $buyeremail;
			$edata['detail'] = false;
			//send_notification('New Member Registration - Jayon Express COD Service',$email,null,'new_member',$edata,null);
			send_notification('Rescheduled Orders - Jayon Express COD Service',$buyeremail['buyeremail'],null,'rescheduled_order_buyer',$edata,null);
			//send_notification('Rescheduled Orders',$buyeremail,null,'rescheduled_order',$edata,null);
		}else{
			foreach($buyeremail as $b){
				$edata = $b;
				$edata['detail'] = false;
				send_notification('Rescheduled Orders',$b['buyeremail'],null,'rescheduled_order_buyer',$nedata,null);
				//send_notification('Rescheduled Orders',$buyeremail,null,'rescheduled_order',$edata,null);
			}
		}


		print json_encode(array('result'=>'ok'));

	}

	public function ajaxfullreschedule(){

		$delivery_id = $this->input->post('delivery_id');
		$buyerdeliverytime = $this->input->post('buyerdeliverytime');
		$shipping_address = $this->input->post('shipping_address');
		$shipping_zip = $this->input->post('shipping_zip');
		$recipient_name = $this->input->post('recipient_name');
		$req_by = $this->input->post('req_by');
		$req_name = $this->input->post('req_name');
		$req_note = $this->input->post('req_note');

		//get order

		$ord = $this->db
			->where('delivery_id',$delivery_id)
			->get($this->config->item('assigned_delivery_table'));

		$old = $ord->row_array();

	    unset($old['id']);
        unset($old['created']);
        unset($old['assigntime']);
        unset($old['deliverytime']);
        unset($old['assignment_date']);
        unset($old['assignment_timeslot']);
        unset($old['assignment_zone']);
        unset($old['assignment_city']);
        unset($old['laststatus']);
        unset($old['change_actor']);
        unset($old['actor_history']);
        unset($old['delivery_note']);
        unset($old['undersign']);
        unset($old['latitude']);
        unset($old['longitude']);
        $old['ordertime'] = date('Y-m-d H:i:s',time());
        $old['buyerdeliverytime'] = ($buyerdeliverytime == '')?$old['buyerdeliverytime']:$buyerdeliverytime;
        $old['recipient_name'] = ($recipient_name == '')?$old['recipient_name']:$recipient_name;
        $old['shipping_address'] =($shipping_address == '')?$old['shipping_address']:$shipping_address;
        $old['shipping_zip'] = ($shipping_zip == '')?$old['shipping_zip']:$shipping_zip;
        $old['status'] = $this->config->item('trans_status_new');
        $old['reschedule_ref'] = $old['delivery_id'];

		$inres = $this->db->insert($this->config->item('incoming_delivery_table'),$old);
		$sequence = $this->db->insert_id();

		$new_delivery_id = get_delivery_id($sequence,$old['merchant_id']);

		$this->db->where('id',$sequence)->update($this->config->item('incoming_delivery_table'),array('delivery_id'=>$new_delivery_id));

		//get details and reinsert with the new delivery id

		$dets = $this->db
			->where('delivery_id',$delivery_id)
			->get($this->config->item('delivery_details_table'));

		if($dets->num_rows() > 0){
			$seq = 0;
			foreach($dets->result() as $it){
				$item['ordertime'] = $old['ordertime'];
				$item['delivery_id'] = $new_delivery_id;
				$item['unit_sequence'] = $seq++;
				$item['unit_description'] = $it->unit_description;
				$item['unit_price'] = $it->unit_price;
				$item['unit_quantity'] = $it->unit_quantity;
				$item['unit_total']	= $it->unit_total;
				$item['unit_discount'] = $it->unit_discount;

				$rs = $this->db->insert($this->config->item('delivery_details_table'),$item);
			}
		}

		//do log

		$data = array(
			'timestamp'=>date('Y-m-d H:i:s',time()),
			'report_timestamp'=>date('Y-m-d H:i:s',time()),
			'delivery_id'=>$delivery_id,
			'device_id'=>'',
			'courier_id'=>'',
			'actor_type'=>'AD',
			'actor_id'=>$this->session->userdata('userid'),
			'latitude'=>'',
			'longitude'=>'',
			'status'=>$this->config->item('trans_status_rescheduled'),
			'req_by' => $req_by,
			'req_name' => $req_name,
			'req_note' => $req_note,
			'notes'=>''
		);

		delivery_log($data);
		print json_encode(array('result'=>'ok'));
	}


	public function ajaxrevoke(){
		// shoud be more complex !! not just updating status, but creating duplicate entry with different date and delivery ID
		$delivery_id = $this->input->post('delivery_id');

		$actor = 'M:'.$this->session->userdata('userid');

		if(is_array($delivery_id)){
			foreach ($delivery_id as $d) {
				$this->db->where('delivery_id',$d)->update($this->config->item('incoming_delivery_table'),array('status'=>'revoked','change_actor'=>$actor));
				$buyeremail[] = $this->do_revoke($d,null,$this->config->item('trans_status_revoked'),'incoming');
				$data = array(
						'timestamp'=>date('Y-m-d H:i:s',time()),
						'report_timestamp'=>date('Y-m-d H:i:s',time()),
						'delivery_id'=>$d,
						'device_id'=>'',
						'courier_id'=>'',
						'actor_type'=>'MC',
						'actor_id'=>$this->session->userdata('userid'),
						'latitude'=>'',
						'longitude'=>'',
						'status'=>$this->config->item('trans_status_revoked'),
						'notes'=>''
						);

				delivery_log($data);
			}
		}else{
			$this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),array('status'=>'revoked','change_actor'=>$actor));
			$buyeremail = $this->do_revoke($delivery_id,null,$this->config->item('trans_status_revoked'),'incoming');

				$data = array(
						'timestamp'=>date('Y-m-d H:i:s',time()),
						'report_timestamp'=>date('Y-m-d H:i:s',time()),
						'delivery_id'=>$delivery_id,
						'device_id'=>'',
						'courier_id'=>'',
						'actor_type'=>'MC',
						'actor_id'=>$this->session->userdata('userid'),
						'latitude'=>'',
						'longitude'=>'',
						'status'=>$this->config->item('trans_status_revoked'),
						'notes'=>''
					);

				delivery_log($data);
		}

		print json_encode(array('result'=>'ok'));
		send_notification('Revoked Orders',$buyeremail,null,'rescheduled_order_buyer',$edata,null);

	}

	public function ajaxpurge(){
		$delivery_id = $this->input->post('delivery_id');

		$actor = 'M:'.$this->session->userdata('userid');

		if(is_array($delivery_id)){
			foreach ($delivery_id as $d) {
				$this->db->where('delivery_id',$d)->update($this->config->item('incoming_delivery_table'),array('status'=>$this->config->item('trans_status_purged'),'change_actor'=>$actor));

					$data = array(
						'timestamp'=>date('Y-m-d H:i:s',time()),
						'report_timestamp'=>date('Y-m-d H:i:s',time()),
						'delivery_id'=>$d,
						'device_id'=>'',
						'courier_id'=>'',
						'actor_type'=>'MC',
						'actor_id'=>$this->session->userdata('userid'),
						'latitude'=>'',
						'longitude'=>'',
						'status'=>$this->config->item('trans_status_purged'),
						'notes'=>''
					);

				delivery_log($data);
			}
		}else{
			$this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),array('status'=>$this->config->item('trans_status_purged'),'change_actor'=>$actor));

				$data = array(
						'timestamp'=>date('Y-m-d H:i:s',time()),
						'report_timestamp'=>date('Y-m-d H:i:s',time()),
						'delivery_id'=>$delivery_id,
						'device_id'=>'',
						'courier_id'=>'',
						'actor_type'=>'MC',
						'actor_id'=>$this->session->userdata('userid'),
						'latitude'=>'',
						'longitude'=>'',
						'status'=>$this->config->item('trans_status_purged'),
						'notes'=>''
					);

				delivery_log($data);
		}

		print json_encode(array('result'=>'ok'));
	}

	public function ajaxarchive(){
		$delivery_id = $this->input->post('delivery_id');

		$actor = $this->config->item('actors_code');

		$actor = $actor['admin'].':'.$this->session->userdata('userid');

		if(is_array($delivery_id)){
			foreach ($delivery_id as $d) {
				$this->db->where('delivery_id',$d)->update($this->config->item('incoming_delivery_table'),array('status'=>$this->config->item('trans_status_archived'),'change_actor'=>$actor));

				$data = array(
						'timestamp'=>date('Y-m-d H:i:s',time()),
						'report_timestamp'=>date('Y-m-d H:i:s',time()),
						'delivery_id'=>$d,
						'device_id'=>'',
						'courier_id'=>'',
						'actor_type'=>'MC',
						'actor_id'=>$this->session->userdata('userid'),
						'latitude'=>'',
						'longitude'=>'',
						'status'=>$this->config->item('trans_status_archived'),
						'notes'=>''
					);

				delivery_log($data);
			}
		}else{
			$this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),array('status'=>$this->config->item('trans_status_archived'),'change_actor'=>$actor));

				$data = array(
						'timestamp'=>date('Y-m-d H:i:s',time()),
						'report_timestamp'=>date('Y-m-d H:i:s',time()),
						'delivery_id'=>$delivery_id,
						'device_id'=>'',
						'courier_id'=>'',
						'actor_type'=>'MC',
						'actor_id'=>$this->session->userdata('userid'),
						'latitude'=>'',
						'longitude'=>'',
						'status'=>$this->config->item('trans_status_archived'),
						'notes'=>''
					);

				delivery_log($data);
		}

		print json_encode(array('result'=>'ok'));
	}

	public function ajaxconfirm(){
		$delivery_id = $this->input->post('delivery_id');

		$actor = 'M:'.$this->session->userdata('userid');

		if(is_array($delivery_id)){
			foreach ($delivery_id as $d) {
				$this->db->where('delivery_id',$d)->update($this->config->item('incoming_delivery_table'),array('status'=>$this->config->item('trans_status_confirmed'),'change_actor'=>$actor));
					$data = array(
						'timestamp'=>date('Y-m-d H:i:s',time()),
						'report_timestamp'=>date('Y-m-d H:i:s',time()),
						'delivery_id'=>$d,
						'device_id'=>'',
						'courier_id'=>'',
						'actor_type'=>'MC',
						'actor_id'=>$this->session->userdata('userid'),
						'latitude'=>'',
						'longitude'=>'',
						'status'=>$this->config->item('trans_status_confirmed'),
						'notes'=>''
					);

				delivery_log($data);
			}
		}else{
			$this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),array('status'=>$this->config->item('trans_status_confirmed'),'change_actor'=>$actor));

			$data = array(
					'timestamp'=>date('Y-m-d H:i:s',time()),
					'report_timestamp'=>date('Y-m-d H:i:s',time()),
					'delivery_id'=>$delivery_id,
					'device_id'=>'',
					'courier_id'=>'',
					'actor_type'=>'MC',
					'actor_id'=>$this->session->userdata('userid'),
					'latitude'=>'',
					'longitude'=>'',
					'status'=>$this->config->item('trans_status_archived'),
					'notes'=>''
				);

			delivery_log($data);
		}

		print json_encode(array('result'=>'ok'));
	}

	public function ajaxassigndate(){
		$assignment_date = $this->input->post('assignment_date');
		$delivery_id = $this->input->post('delivery_id');

		if(is_array($delivery_id)){
			foreach($delivery_id as $d){
				$this->do_date_assignment($d,$assignment_date);
			}
		}else{
			$this->do_date_assignment($delivery_id,$assignment_date);
		}

		print json_encode(array('result'=>'ok'));
	}

	public function ajaxdispatch(){

		$assignment_device_id = $this->input->post('assignment_device_id');
		$assignment_courier_id = $this->input->post('assignment_courier_id');
		$assignment_date = $this->input->post('assignment_date');

		$this->db
			->where('device_id',$assignment_device_id)
			->where('assignment_date',$assignment_date)
			->update($this->config->item('assigned_delivery_table'),
				array('status'=>$this->config->item('trans_status_admin_courierassigned'),
						'courier_id'=>$assignment_courier_id));

					$data = array(
						'timestamp'=>date('Y-m-d H:i:s',time()),
						'report_timestamp'=>date('Y-m-d H:i:s',time()),
						'delivery_id'=>'',
						'device_id'=>$assignment_device_id,
						'courier_id'=>$assignment_courier_id,
						'actor_type'=>'MC',
						'actor_id'=>$this->session->userdata('userid'),
						'latitude'=>'',
						'longitude'=>'',
						'status'=>$this->config->item('trans_status_admin_courierassigned'),
						'notes'=>''
					);

				delivery_log($data);

		print json_encode(array('result'=>'ok'));
	}

	public function ajaxassignzone(){

		$assignment_zone = $this->input->post('assignment_zone');
		$assignment_city = $this->input->post('assignment_city');
		$assignment_timeslot = $this->input->post('assignment_timeslot');
		$assignment_device_id = $this->input->post('assignment_device_id');
		$delivery_ids = $this->input->post('delivery_id');

		foreach($delivery_ids as $did){
			$this->do_zone_assignment($did,$assignment_device_id,$assignment_zone,$assignment_city,$assignment_timeslot);
			$data = array(
				'timestamp'=>date('Y-m-d H:i:s',time()),
				'report_timestamp'=>date('Y-m-d H:i:s',time()),
				'delivery_id'=>$did,
				'device_id'=>'',
				'courier_id'=>'',
				'actor_type'=>'MC',
				'actor_id'=>$this->session->userdata('userid'),
				'latitude'=>'',
				'longitude'=>'',
				'status'=>$this->config->item('trans_status_admin_zoned'),
				'notes'=>''
			);

			delivery_log($data);

		}

		print json_encode(array('result'=>'ok'));
	}

	public function ajaxcourierassign(){

		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		$sort_col = $this->input->post('iSortCol_0');
		$sort_dir = $this->input->post('sSortDir_0');

		$columns = array(
			'assignment_date',
			'device',
			'assignment_timeslot',
			'delivery_id',
			'buyerdeliverycity',
			'buyerdeliveryzone',
			'app_name',
			'buyer',
			'merchant',
			'merchant_trans_id',
			'shipping_address',
			'phone',
			'status',
			);

		// get total count result
		$count_all = $this->db
			->where($this->config->item('assigned_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_admin_devassigned'))
			->count_all($this->config->item('assigned_delivery_table'));

		$count_display_all = $this->db->count_all_results($this->config->item('assigned_delivery_table'));

		//search column
		if($this->input->post('sSearch') != ''){
			$srch = $this->input->post('sSearch');
			$this->db->like('assignment_zone',$srch);
			$this->db->or_like('assignment_date',$srch);
			$this->db->or_like('buyerdeliverytime',$srch);
			$this->db->or_like('delivery_id',$srch);
		}

		if($this->input->post('sSearch_0') != ''){
			$this->db->like('d.identifier',$this->input->post('sSearch_0'));
		}

		if($this->input->post('sSearch_1') != ''){
			$this->db->like('assignment_date',$this->input->post('sSearch_1'));
		}

		if($this->input->post('sSearch_2') != ''){
			$this->db->like('assignment_zone',$this->input->post('sSearch_2'));
		}


		$this->db->select('*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name,d.identifier as device');
		$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=b.id','left');
		$this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');

		$data = $this->db
			->where($this->config->item('assigned_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_admin_devassigned'))
			->limit($limit_count, $limit_offset)
			->order_by('assignment_date','desc')
			->order_by('device_id','asc')
			->order_by($columns[$sort_col],$sort_dir)
			->get($this->config->item('assigned_delivery_table'));

		//print $this->db->last_query();

		$result = $data->result_array();

		$aadata = array();

		$bardate = '';

		$bardev = '';

		foreach($result as $value => $key)
		{
			$delete = anchor("admin/delivery/delete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links
			$printslip = anchor_popup("admin/prints/deliveryslip/".$key['delivery_id'], "Print Slip"); // Build actions links

			$app = $this->get_app_info($key['application_key']);

			$datecheck = form_radio('assign_date',$key['assignment_date'],FALSE,'class="assign_date"').'<strong>'.$key['assignment_date'].'</strong>';
			$devicecheck = form_radio('device_id',$key['device_id'],FALSE,'class="device_id" title="'.$key['device'].'"').$key['device'];

			$datefield = ($bardate == $key['assignment_date'])?'':$datecheck;

			$devicefield = ($bardate == $key['assignment_date'] && $bardev == $key['device_id'])?'':$devicecheck;


			$aadata[] = array(
				$datefield,
				$devicefield,
				$key['assignment_timeslot'],
				$key['delivery_id'],
				$key['buyerdeliverycity'],
				$key['buyerdeliveryzone'],
				$app['application_name'],
				//$app['domain'],
				$key['buyer'],
				$key['merchant'],
				$key['merchant_trans_id'],
				$key['shipping_address'],
				$key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
				colorizestatus($key['status']),
				//$key['reschedule_ref'],
				//$key['revoke_ref'],
				//$printslip.' '.$edit.' '.$delete
			);


			$bardate = $key['assignment_date'];
			$bardev = $key['device_id'];
		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);

		print json_encode($result);
	}

	public function courierassign()
	{
		$this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
		$this->breadcrumb->add_crumb('Courier Assignment','admin/delivery/assigned');

		$this->table->set_heading(
			'Delivery Date',
			'Device',
			'Time Slot',
			'Delivery ID',
			'Delivery City',
			'Delivery Zone',
			'App Name',
			'Buyer',
			'Merchant',
			'Merchant Trans ID',
			'Shipping Address',
			'Phone',
			'Status'
			); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery time" class="search_init" />',
			'<input type="text" name="search_device" id="search_device" value="Search device" class="search_init" />',
			'<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
			'<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />',
			form_button('do_dispatch','Assign Courier','id="doDispatch"')
			);

		$page['sortdisable'] = '0,1,2,9,10,11';
		$page['ajaxurl'] = 'admin/delivery/ajaxcourierassign';
		$page['page_title'] = 'Courier Assignment';
		$this->ag_auth->view('courierassignajaxlistview',$page); // Load the view
	}

	public function ajaxassigned(){

		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		$sort_col = $this->input->post('iSortCol_0');
		$sort_dir = $this->input->post('sSortDir_0');

		$columns = array(
			'assignment_date',
			'device',
			'delivery_id',
			'assignment_zone',
			'app_name',
			'buyer',
			'merchant',
			'merchant_trans_id',
			'shipping_address',
			'phone',
			'status',
			);

		// get total count result
		$count_all = $this->db
			->where($this->config->item('assigned_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_admin_dated'))
			->count_all($this->config->item('assigned_delivery_table'));

		$count_display_all = $this->db->count_all_results($this->config->item('assigned_delivery_table'));

		//search column
		if($this->input->post('sSearch') != ''){
			$srch = $this->input->post('sSearch');
			$this->db->like('assignment_zone',$srch);
			$this->db->or_like('assignment_date',$srch);
			$this->db->or_like('buyerdeliverytime',$srch);
			$this->db->or_like('delivery_id',$srch);
		}

		if($this->input->post('sSearch_0') != ''){
			$this->db->like('d.identifier',$this->input->post('sSearch_0'));
		}

		if($this->input->post('sSearch_1') != ''){
			$this->db->like('assignment_date',$this->input->post('sSearch_1'));
		}

		if($this->input->post('sSearch_2') != ''){
			$this->db->like('assignment_zone',$this->input->post('sSearch_2'));
		}


		$this->db->select('*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name,d.identifier as device');
		$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=b.id','left');
		$this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');

		$data = $this->db
			->where($this->config->item('assigned_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_admin_zoned'))
			->limit($limit_count, $limit_offset)
			->order_by('assignment_date','desc')
			->order_by('device_id','asc')
			->order_by($columns[$sort_col],$sort_dir)
			->get($this->config->item('assigned_delivery_table'));

		//print $this->db->last_query();

		$result = $data->result_array();

		$aadata = array();

		$bardate = '';

		$bardev = '';

		foreach($result as $value => $key)
		{
			$delete = anchor("admin/delivery/delete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links
			$printslip = anchor_popup("admin/prints/deliveryslip/".$key['delivery_id'], "Print Slip"); // Build actions links

			$app = $this->get_app_info($key['application_key']);
			/*
			if($bardate != $key['assignment_date']){
				$aadata[] = array(
					form_radio('assign_date',$key['assignment_date'],FALSE,'class="assign_date"').'<strong>'.$key['assignment_date'].'</strong>',
				//	'Device <input type="text" name="assign_device" id="assign_device" value="" class="search_init assign_device" />',
				//	'Courier <input type="text" name="assign_courier" id="assign_courier" value="" class="search_init assign_courier" />',
				//	'<span onClick="javascript:doDispatch("'.$key['assignment_date'].'")" class="dispatcher">Dispatch</span>',
					'','','','','','','','','','','','','',''
				);
			}
			*/

			$datecheck = form_radio('assign_date',$key['assignment_date'],FALSE,'class="assign_date"').'<strong>'.$key['assignment_date'].'</strong>';
			$devicecheck = form_radio('device_id',$key['device_id'],FALSE,'class="device_id" title="'.$key['device'].'"').$key['device'];

			$datefield = ($bardate == $key['assignment_date'])?'':$datecheck;

			$devicefield = ($bardate == $key['assignment_date'] && $bardev == $key['device_id'])?'':$devicecheck;


			$aadata[] = array(
				$datefield,
				$devicefield,
				$key['delivery_id'],
				$key['assignment_zone'],
				$app['application_name'],
				//$app['domain'],
				$key['buyer'],
				$key['merchant'],
				$key['merchant_trans_id'],
				$key['shipping_address'],
				$key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
				colorizestatus($key['status']),
				//$key['reschedule_ref'],
				//$key['revoke_ref'],
				$printslip.' '.$edit.' '.$delete
			);


			$bardate = $key['assignment_date'];
			$bardev = $key['device_id'];
		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);

		print json_encode($result);
	}

	public function assigned()
	{
		$this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
		$this->breadcrumb->add_crumb('Assigned Orders','admin/delivery/assigned');

		$this->table->set_heading(
			'Delivery Date',
			'Device',
			'Delivery ID',
			'Delivery Zone',
			'App Name',
			'Buyer',
			'Merchant',
			'Merchant Trans ID',
			'Shipping Address',
			'Phone',
			'Status',
			'Actions'
			); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery time" class="search_init" />',
			'<input type="text" name="search_device" id="search_device" value="Search device" class="search_init" />',
			'<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
			'<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />',
			form_button('do_dispatch','Dispatch Device','id="doDispatch"')
			);

		$page['sortdisable'] = '0,1,2,9,10,11';
		$page['ajaxurl'] = 'admin/delivery/ajaxassigned';
		$page['page_title'] = 'Assigned Delivery Orders';
		$this->ag_auth->view('assignedajaxlistview',$page); // Load the view
	}

    public function ajaxdispatched(){

        $limit_count = $this->input->post('iDisplayLength');
        $limit_offset = $this->input->post('iDisplayStart');

        $sort_col = $this->input->post('iSortCol_0');
        $sort_dir = $this->input->post('sSortDir_0');

        $columns = array(
            'assignment_date',
            'buyerdeliveryzone',
            'delivery_id',
            'device',
            'courier',
            'buyer',
            'shipping_address',
            'phone',
            '',
            'status',
            'merchant_id',
            'merchant_trans_id'
            );

        // get total count result
        $count_all = $this->db->count_all($this->config->item('assigned_delivery_table'));


        $search = false;

        //search column
        if($this->input->post('sSearch') != ''){
            $srch = $this->input->post('sSearch');
            $this->db->like('assignment_zone',$srch);
            $this->db->or_like('assignment_date',$srch);
            $this->db->or_like('buyerdeliverytime',$srch);
            $this->db->or_like('delivery_id',$srch);
            $search = true;
        }

        if($this->input->post('sSearch_0') != '' && !preg_match('/^sel_*/', $this->input->post('sSearch_0'))){
            $this->db->like($this->config->item('assigned_delivery_table').'.assignment_date',$this->input->post('sSearch_0'));
            $search = true;
        }

        if($this->input->post('sSearch_1') != ''){
            $this->db->like('d.identifier',$this->input->post('sSearch_1'));
            $search = true;
        }

        if($this->input->post('sSearch_2') != ''){
            if($this->input->post('sSearch_2') == 'DO'){
                $term = 'Delivery Only';
            }else if($this->input->post('sSearch_2') == 'COD') {
                $term = 'COD';
            }else{
                $term = $this->input->post('sSearch_2');
            }
            $this->db->like($this->config->item('assigned_delivery_table').'.delivery_type',$term);
            $search = true;
        }

        if($this->input->post('sSearch_3') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.buyerdeliverycity',$this->input->post('sSearch_3'));
            $search = true;
        }

        if($this->input->post('sSearch_4') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.buyerdeliveryzone',$this->input->post('sSearch_4'));
            $search = true;
        }

        if($this->input->post('sSearch_5') != ''){
            $this->db->like('m.merchantname',$this->input->post('sSearch_5'));
            $search = true;
        }

        if($this->input->post('sSearch_6') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.buyer_name',$this->input->post('sSearch_6'));
            $search = true;
        }
        if($this->input->post('sSearch_7') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.recipient_name',$this->input->post('sSearch_7'));
            $search = true;
        }

        if($this->input->post('sSearch_8') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.shipping_address',$this->input->post('sSearch_8'));
            $search = true;
        }


        if($this->input->post('sSearch_9') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.delivery_id',$this->input->post('sSearch_9'));
            $search = true;
        }

        if($this->input->post('sSearch_10') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.merchant_trans_id',$this->input->post('sSearch_10'));
            $search = true;
        }


        /* handle pulldown type filter , hacky thing but should work for now */

        if($this->input->post('sSearch_0') != '' && preg_match('/^sel_*/', $this->input->post('sSearch_0'))){
            $search = false;
            if($this->input->post('sSearch_0') == 'sel_DO'){
                $this->db->where($this->config->item('assigned_delivery_table').'.delivery_type','Delivery Only');
                $search = true;
            }else if($this->input->post('sSearch_0') == 'sel_COD'){
                $this->db->where($this->config->item('assigned_delivery_table').'.delivery_type','COD');
                $search = true;
            }
        }


        $this->db->select($this->config->item('assigned_delivery_table').'.*,m.merchantname as merchant,a.application_name as app_name,d.identifier as device,c.fullname as courier');
        //$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
        $this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
        $this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=a.id','left');
        $this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');
        $this->db->join('couriers as c',$this->config->item('assigned_delivery_table').'.courier_id=c.id','left');

        $this->db->where($this->config->item('assigned_delivery_table').'.merchant_id',$this->session->userdata('userid'));

        //if($search){
            $this->db->and_();
        //}
        $this->db->group_start()
            ->where('status',$this->config->item('trans_status_admin_courierassigned'))
            ->or_where('status',$this->config->item('trans_status_mobile_pickedup'))
            ->or_where('status',$this->config->item('trans_status_mobile_enroute'))
            ->or_()
                ->group_start()
                    ->where('status',$this->config->item('trans_status_new'))
                    ->where('pending_count >', 0)
                ->group_end()
            ->group_end();

        $dbca = clone $this->db;

        $this->db->order_by('assignment_date','desc')
            ->order_by('d.identifier','asc')
            ->order_by('c.fullname','asc')
            ->order_by('buyerdeliverycity','asc')
            ->order_by('buyerdeliveryzone','asc')
            ->order_by($columns[$sort_col],$sort_dir);

        $this->db->limit($limit_count, $limit_offset);

        $dbcr = clone $this->db;

        $data = $this->db->get($this->config->item('assigned_delivery_table'));

        //print $this->db->last_query();

        $lastquery = $this->db->last_query();

        $count_all = $dbca->count_all_results($this->config->item('assigned_delivery_table'));
        $count_display_all = $dbcr->count_all_results($this->config->item('assigned_delivery_table'));

        $result = $data->result_array();

        $aadata = array();

        $bardate = '';
        $bardev = '';
        $barcourier = '';
        $barcity = '';
        $barzone = '';

        $num = $limit_offset;



        foreach($result as $value => $key)
        {
            $num++;

            $delete = anchor("admin/delivery/delete/".$key['id']."/", "Delete"); // Build actions links
            $edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links
            //$printslip = anchor_popup("admin/prints/deliveryslip/".$key['delivery_id'], "Print Slip"); // Build actions links
            $printslip = '<span class="printslip" id="'.$key['delivery_id'].'" style="cursor:pointer;text-decoration:underline;" >Print Slip</span>';
            $changestatus = '<span class="changestatus" id="'.$key['delivery_id'].'" style="cursor:pointer;text-decoration:underline;" >ChgStat</span>';
            $reassign = '<span class="reassign" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">Reassign</span>';
            $viewlog = '<span class="view_log" id="'.$key['delivery_id'].'" style="cursor:pointer;text-decoration:underline;" >Log</span>';
            $printlabel = '<span class="printlabel" id="'.$key['delivery_id'].'" style="cursor:pointer;text-decoration:underline;" >Print Label</span>';


            $datefield = ($bardate == $key['assignment_date'])?'':$key['assignment_date'];
            $devicefield = ($bardev == $key['device'])?'':$key['device'];

            $courierlink = '<span class="change_courier" id="'.$key['assignment_date'].'_'.$key['device_id'].'_'.$key['courier_id'].'" style="cursor:pointer;text-decoration:underline;" >'.$key['courier'].'</span>';

            $courierfield = ($barcourier == $key['courier'] && $barzone == $key['buyerdeliveryzone'])?'':$courierlink;
            $cityfield = ($barcity == $key['buyerdeliverycity'])?'':$key['buyerdeliverycity'];
            $zonefield = ($barzone == $key['buyerdeliveryzone'])?'':$key['buyerdeliveryzone'];

            $delivery_check = form_checkbox('assign[]',$key['delivery_id'],FALSE,'class="assign_check"').'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$key['delivery_id'].'</span>';

            $lat = ($key['latitude'] == 0)? 'Set Loc':$key['latitude'];
            $lon = ($key['longitude'] == 0)? '':$key['longitude'];

            $style = 'style="cursor:pointer;padding:2px;display:block;"';
            $class = ($lat == 'Set Loc')?' red':'';

            $direction = '<span id="'.$key['id'].'" '.$style.' class="locpick'.$class.'">'.$lat.' '.$lon.'</span>';

            $thumbnail = get_thumbnail($key['delivery_id'],'thumb_multi');

            $thumbstat = colorizestatus($key['status']);
            if($key['status'] == 'pending'){
                $thumbstat .= '<br />'.$thumbnail;
            }

            $aadata[] = array(
                $num,
                $datefield,
                $devicefield,
                $courierfield,
                colorizetype($key['delivery_type']),
                ($key['delivery_type'] == 'COD')?(double)$key['chargeable_amount']:'',
                $cityfield,
                $zonefield,
                $key['merchant'],
                $key['buyer_name'],
                $key['recipient_name'],
                $key['shipping_address'], //.'<br />'.$direction
                $key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
                $delivery_check,
                //'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$key['delivery_id'].'</span>',
                $thumbstat,
                $key['pending_count'],
                $key['delivery_note'],
                $printslip.'<br /><br />'.$printlabel,
                //.' '.$reassign.' '.$changestatus.' '.$viewlog,

                $this->hide_trx($key['merchant_trans_id']),
                $key['delivery_cost'],
                ($key['delivery_type'] == 'COD')?$key['cod_cost']:'',
                $key['width'].' x '.$key['height'].' x '.$key['length'],
                (double)$key['width']*(double)$key['height']*(double)$key['length'],
                get_weight_range($key['weight'],$key['application_id'])

            );

            $bardate = $key['assignment_date'];
            $bardev =   $key['device'];
            $barcourier =   $key['courier'];
            $barcity =  $key['buyerdeliverycity'];
            $barzone =  $key['buyerdeliveryzone'];


        }

        $result = array(
            'sEcho'=> $this->input->post('sEcho'),
            'iTotalRecords'=>$count_all,
            'iTotalDisplayRecords'=> $count_display_all,
            'aaData'=>$aadata,
            'q'=>$lastquery
        );

        print json_encode($result);
    }

    public function dispatched()
    {
        $this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
        $this->breadcrumb->add_crumb('In Progress Orders','admin/delivery/assigned');

        $this->table->set_heading(
            '#',
            'Delivery Date',
            'Device',
            'Courier',
            'Type',
            'COD Value',
            'City',
            'Zone',
            'Merchant',
            'Buyer',
            'Delivered To',
            'Shipping Address',
            'Phone',
            'Delivery ID',
            'Status',
            'Pending',
            'Note',
            'Actions',

            'No Kode Penjualan Toko',
            'Delivery Fee',
            'COD Surcharge',
            'W x H x L',
            'Volume',
            'Weight Range'

            ); // Setting headings for the table

        $this->table->set_footing(
            '',
            '<input type="text" name="search_buyerdeliverytime" id="search_deliverytime" value="Search delivery time" class="search_init" />',
            '<input type="text" name="search_device" id="search_device" value="Search device" class="search_init" />',
            '',
            '<input type="text" name="search_delivery_type" id="search_delivery_type" value="Search delivery type" class="search_init" />',
            '',
            '<input type="text" name="search_buyerdeliverycity" id="search_city" value="Search City" class="search_init" />',
            '<input type="text" name="search_buyerdeliveryzone" id="search_zone" value="Search zone" class="search_init" />',
            '<input type="text" name="search_merchant" id="search_merchant" value="Search Merchant" class="search_init" />',
            '<input type="text" name="search_buyer" id="search_buyer" value="Search Buyer" class="search_init" />',
            '<input type="text" name="search_recipient_name" id="search_recipient" value="Search Recipient" class="search_init" />',
            '<input type="text" name="search_shipping_address" id="search_shipping" value="Search Address" class="search_init" />',
            '',
            '<input type="text" name="search_delivery_id" value="Search delivery ID" class="search_init" />',
            '',
            '',
            //'<input type="text" name="search_trxid" value="Search Trans ID" class="search_init" />',
            '',
            '',
            '<input type="text" name="search_merchant_trans_id" value="Search transaction ID" class="search_init" />',
            '',
            ''
            );

        $page['sortdisable'] = '0,1,2,3,11';
        $page['ajaxurl'] = 'admin/delivery/ajaxdispatched';
        $page['page_title'] = 'In Progress Orders';
        $this->ag_auth->view('dispatchajaxlistview',$page); // Load the view
    }


    public function ajaxdelivered()
    {
        $limit_count = $this->input->post('iDisplayLength');
        $limit_offset = $this->input->post('iDisplayStart');

        // get total count result
        //$count_all = $this->db->count_all($this->config->item('delivered_delivery_table'));

        //$count_display_all = $this->db
        //  ->where($this->config->item('assigned_delivery_table').'.status',$this->config->item('trans_status_mobile_delivered'))
        //  ->count_all_results($this->config->item('delivered_delivery_table'));
        $mtab = $this->config->item('assigned_delivery_table');

        $mfields = $mtab.'.id as id,delivery_type,
                buyerdeliverycity,
                buyerdeliveryzone,
                buyer_name,
                recipient_name,
                shipping_address,
                '.$mtab.'.phone,
                '.$mtab.'.mobile1,
                '.$mtab.'.mobile2,
                delivery_note,
                status,
                device_id,
                deliverytime,
                chargeable_amount,
                delivery_id,
                merchant_trans_id,
                delivery_cost,
                delivery_type,cod_cost,
                delivery_note,
                reschedule_ref,
                revoke_ref,
                '.$mtab.'.merchant_id';

        $this->db->select($mfields.',m.merchantname as merchant,a.application_name as app_name,d.identifier as device,c.fullname as courier');
        //$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
        $this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
        $this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=a.id','left');
        $this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');
        $this->db->join('couriers as c',$this->config->item('assigned_delivery_table').'.courier_id=c.id','left');

        $this->db->where($this->config->item('assigned_delivery_table').'.merchant_id',$this->session->userdata('userid'));

        $this->db->and_();

        $search = false;

        if($this->input->post('sSearch_0') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.deliverytime',$this->input->post('sSearch_0'));
            $search = true;
        }

        if($this->input->post('sSearch_1') != ''){
            $this->db->like('d.identifier',$this->input->post('sSearch_1'));
            $search = true;
        }

        if($this->input->post('sSearch_2') != ''){
            if($this->input->post('sSearch_2') == 'DO'){
                $term = 'Delivery Only';
            }else if($this->input->post('sSearch_2') == 'COD') {
                $term = 'COD';
            }else{
                $term = $this->input->post('sSearch_2');
            }
            $this->db->like($this->config->item('assigned_delivery_table').'.delivery_type',$term);
            $search = true;
        }



        if($this->input->post('sSearch_3') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.buyerdeliverycity',$this->input->post('sSearch_3'));
            $search = true;
        }

        if($this->input->post('sSearch_4') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.buyerdeliveryzone',$this->input->post('sSearch_4'));
            $search = true;
        }

        if($this->input->post('sSearch_5') != ''){
            $this->db->like('m.merchantname',$this->input->post('sSearch_5'));
            $search = true;
        }

        if($this->input->post('sSearch_6') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.buyer_name',$this->input->post('sSearch_6'));
            $search = true;
        }

        if($this->input->post('sSearch_7') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.shipping_address',$this->input->post('sSearch_7'));
            $search = true;
        }

        if($this->input->post('sSearch_8') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.status',$this->input->post('sSearch_8'));
            $search = true;
        }

        if($this->input->post('sSearch_9') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.delivery_id',$this->input->post('sSearch_9'));
            $search = true;
        }

        if($this->input->post('sSearch_10') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.merchant_trans_id',$this->input->post('sSearch_10'));
            $search = true;
        }

        if($search){
            $this->db->and_();
        }

        $this->db->group_start()
            ->where($this->config->item('assigned_delivery_table').'.status',$this->config->item('trans_status_mobile_delivered'))
            ->or_where($this->config->item('assigned_delivery_table').'.status',$this->config->item('trans_status_mobile_revoked'))
            ->or_where($this->config->item('assigned_delivery_table').'.status',$this->config->item('trans_status_mobile_noshow'))
            ->group_end();

        $dbca = clone $this->db;

        $this->db->limit($limit_count, $limit_offset)
            ->order_by('deliverytime','desc');

        $dbcr = clone $this->db;

        $data = $this->db->get($this->config->item('delivered_delivery_table'));


        // get total count result
        $count_all = $dbca->count_all_results($this->config->item('incoming_delivery_table'));
        $count_display_all = $dbcr->count_all_results($this->config->item('incoming_delivery_table'));


        $result = $data->result_array();

        $aadata = array();

        $num = $limit_offset;
        //foreach($result as $value => $key)
        for($i = 0; $i < count($result);$i++)
        {
            $key = $result[$i];

            $num++;
            $delete = anchor("admin/delivery/delete/".$key['id']."/", "Delete"); // Build actions links
            $edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links
            $printslip = '<span class="printslip" id="'.$key['delivery_id'].'" style="cursor:pointer;text-decoration:underline;" >Print Slip</span>';
            $viewlog = '<span class="view_log" id="'.$key['delivery_id'].'" style="cursor:pointer;text-decoration:underline;" >Log</span>';
            //if($key['status'] == 'pending'){
                $thumbnail = get_thumbnail($key['delivery_id'], 'thumb_multi');
            //}else{
            //    $thumbnail = get_thumbnail($key['delivery_id']);
            //}

            $changestatus = '<span class="changestatus" id="'.$key['delivery_id'].'" dev_id="'.$key['device_id'].'" style="cursor:pointer;text-decoration:underline;" >ChgStat</span>';

            $aadata[] = array(
                $num,
                '<span id="dt_'.$key['delivery_id'].'">'.$key['deliverytime'].'</span>',
                $key['device'],
                $key['courier'],
                colorizetype($key['delivery_type']),
                ($key['delivery_type'] == 'COD')?$key['chargeable_amount']:'',
                $key['buyerdeliverycity'],
                $key['buyerdeliveryzone'],
                $key['merchant'],
                $key['buyer_name'],
                $key['recipient_name'],
                $key['shipping_address'],
                $key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
                $thumbnail,
                $key['delivery_note'],
                colorizestatus($key['status']),
                $key['delivery_note'],
                form_checkbox('assign[]',$key['delivery_id'],FALSE,'class="assign_check" data-merchantid="'.$key['merchant_id'].'" data-merchant="'.$key['merchant'].'" title="'.$key['status'].'"').'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$key['delivery_id'].'</span>',
                $this->hide_trx($key['merchant_trans_id']),
                $key['delivery_cost'],
                ($key['delivery_type'] == 'COD')?$key['cod_cost']:'',
                $key['reschedule_ref'],
                $key['revoke_ref'],
                $printslip
                //.' '.$viewlog.' '.$changestatus
            );
        }

        $result = array(
            'sEcho'=> $this->input->post('sEcho'),
            'iTotalRecords'=>$count_all,
            'iTotalDisplayRecords'=> $count_display_all,
            'aaData'=>$aadata
        );

        print json_encode($result);
    }

    public function delivered()
    {
        $this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
        $this->breadcrumb->add_crumb('Delivery Status','admin/delivery/delivered');

        //$data = $this->db->where('status','delivered')->get($this->config->item('delivered_delivery_table'));
        //$result = $data->result_array();

        $this->table->set_heading(
            '#',
            'Delivery Time',
            'Device',
            'Courier',
            'Type',
            'COD Value',
            'City',
            'Zone',
            'Merchant',
            'Buyer',
            'Delivered To',
            'Shipping Address',
            'Phone',
            'Receiver Photo',
            'Receiver / Note',
            'Status',
            'Note',
            'Delivery ID',
            'No Kode Penjualan Toko',
            'Delivery Fee',
            'COD Surcharge',
            'Reschedule Ref',
            'Revoke Ref',
            'Action'
            ); // Setting headings for the table

        $this->table->set_footing(
            '',
            '<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery time" class="search_init" />',
            '<input type="text" name="search_device" id="search_device" value="Search Device" class="search_init" />',
            '',
            '<input type="text" name="search_delivery_type" id="search_delivery_type" value="Search delivery type" class="search_init" />',
            '',
            '<input type="text" name="search_city" id="search_city" value="Search city" class="search_init" />',
            '<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />',
            '<input type="text" name="search_merchant" id="search_merchant" value="Search Merchant" class="search_init" />',
            '<input type="text" name="search_buyer" id="search_buyer" value="Search Buyer" class="search_init" />',
            '',
            '<input type="text" name="search_shipping_address" id="search_shipping_address" value="Search Shipping Address" class="search_init" />',
            '',
            '',
            '',
            '<input type="text" name="search_status" value="Search status" class="search_init" />',
            '',
            '<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
            '<input type="text" name="search_merchant_trans_id" value="Search kode toko" class="search_init" />',
            '',//form_button('do_sending','Send Slip','id="doSending"'),
            ''//form_button('do_archive','Archive Selection','id="doArchive"')

            );


        $page['ajaxurl'] = 'admin/delivery/ajaxdelivered';
        $page['laststatus'] = $this->config->item('trans_status_mobile_delivered');
        $page['page_title'] = 'Delivered Orders';
        $this->ag_auth->view('ajaxlistview',$page); // Load the view
    }


	public function __ajaxdispatched(){

		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		$sort_col = $this->input->post('iSortCol_0');
		$sort_dir = $this->input->post('sSortDir_0');

		$columns = array(
            'assignment_date',
            'buyerdeliveryzone',
            'delivery_id',
            'device',
            'courier',
            'buyer',
            'shipping_address',
            'phone',
            '',
            'status',
            'merchant_id',
            'merchant_trans_id'
			);

		// get total count result
		$count_all = $this->db->count_all($this->config->item('assigned_delivery_table'));

		$count_display_all = $this->db
			->where($this->config->item('assigned_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->count_all_results($this->config->item('assigned_delivery_table'));

		//search column
		$search = false;

        $search = false;

        //search column
        if($this->input->post('sSearch') != ''){
            $srch = $this->input->post('sSearch');
            $this->db->like('assignment_zone',$srch);
            $this->db->or_like('assignment_date',$srch);
            $this->db->or_like('buyerdeliverytime',$srch);
            $this->db->or_like('delivery_id',$srch);
            $search = true;
        }

        if($this->input->post('sSearch_0') != '' && !preg_match('/^sel_*/', $this->input->post('sSearch_0'))){
            $this->db->like($this->config->item('assigned_delivery_table').'.assignment_date',$this->input->post('sSearch_0'));
            $search = true;
        }

        if($this->input->post('sSearch_1') != ''){
            $this->db->like('d.identifier',$this->input->post('sSearch_1'));
            $search = true;
        }

        if($this->input->post('sSearch_2') != ''){
            if($this->input->post('sSearch_2') == 'DO'){
                $term = 'Delivery Only';
            }else if($this->input->post('sSearch_2') == 'COD') {
                $term = 'COD';
            }else{
                $term = $this->input->post('sSearch_2');
            }
            $this->db->like($this->config->item('assigned_delivery_table').'.delivery_type',$term);
            $search = true;
        }

        if($this->input->post('sSearch_3') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.buyerdeliverycity',$this->input->post('sSearch_3'));
            $search = true;
        }

        if($this->input->post('sSearch_4') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.buyerdeliveryzone',$this->input->post('sSearch_4'));
            $search = true;
        }

        if($this->input->post('sSearch_5') != ''){
            $this->db->like('m.merchantname',$this->input->post('sSearch_5'));
            $search = true;
        }

        if($this->input->post('sSearch_6') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.buyer_name',$this->input->post('sSearch_6'));
            $search = true;
        }
        if($this->input->post('sSearch_7') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.recipient_name',$this->input->post('sSearch_7'));
            $search = true;
        }

        if($this->input->post('sSearch_8') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.shipping_address',$this->input->post('sSearch_8'));
            $search = true;
        }


        if($this->input->post('sSearch_9') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.delivery_id',$this->input->post('sSearch_9'));
            $search = true;
        }

        if($this->input->post('sSearch_10') != ''){
            $this->db->like($this->config->item('assigned_delivery_table').'.merchant_trans_id',$this->input->post('sSearch_10'));
            $search = true;
        }


        /* handle pulldown type filter , hacky thing but should work for now */

        if($this->input->post('sSearch_0') != '' && preg_match('/^sel_*/', $this->input->post('sSearch_0'))){
            $search = false;
            if($this->input->post('sSearch_0') == 'sel_DO'){
                $this->db->where($this->config->item('assigned_delivery_table').'.delivery_type','Delivery Only');
                $search = true;
            }else if($this->input->post('sSearch_0') == 'sel_COD'){
                $this->db->where($this->config->item('assigned_delivery_table').'.delivery_type','COD');
                $search = true;
            }
        }



		$this->db->select('*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name,d.identifier as device,c.fullname as courier');
		$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=b.id','left');
		$this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');
		$this->db->join('couriers as c',$this->config->item('assigned_delivery_table').'.courier_id=c.id','left');

		if($search){
			//$this->db->and_();
		}

		$this->db
			->where($this->config->item('assigned_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->and_()->group_start()
			->where('status',$this->config->item('trans_status_admin_courierassigned'))
			->or_where('status',$this->config->item('trans_status_mobile_pickedup'))
			->or_where('status',$this->config->item('trans_status_mobile_enroute'))
			->group_end();

		$data = $this->db->limit($limit_count, $limit_offset)
			->order_by('assignment_date','desc')
			->order_by('device','asc')
			->order_by('courier','asc')
			->order_by('buyerdeliverycity','asc')
			->order_by('buyerdeliveryzone','asc')
			->order_by($columns[$sort_col],$sort_dir)
			->get($this->config->item('assigned_delivery_table'));

		//print $this->db->last_query();

		$result = $data->result_array();

		$aadata = array();

		$bardate = '';
		$bardev = '';
		$barcourier = '';
		$barcity = '';
		$barzone = '';

		foreach($result as $value => $key)
		{
			$delete = anchor("admin/delivery/delete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links
			$printslip = '<span class="printslip" id="'.$key['delivery_id'].'" style="cursor:pointer;text-decoration:underline;" >Print Slip</span>';
			$changestatus = '<span class="changestatus" id="'.$key['delivery_id'].'" style="cursor:pointer;text-decoration:underline;" >ChgStat</span>';

			$datefield = ($bardate == $key['assignment_date'])?'':$key['assignment_date'];
			$devicefield = ($bardev == $key['device'])?'':$key['device'];
			$courierfield = ($barcourier == $key['courier'] && $barzone == $key['buyerdeliveryzone'])?'':$key['courier'];
			$cityfield = ($barcity == $key['buyerdeliverycity'])?'':$key['buyerdeliverycity'];
			$zonefield = ($barzone == $key['buyerdeliveryzone'])?'':$key['buyerdeliveryzone'];

            $orderno = explode('-',$key['delivery_id']);
            $orderno = array_pop($orderno);

			$aadata[] = array(
				$datefield,
				//$devicefield,
				//$courierfield,
				$cityfield,
				$zonefield,
				//$key['merchant'],
				$this->hide_trx($key['merchant_trans_id']),
				'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$orderno.'</span>',
				//$key['delivery_id'],
				$key['buyer_name'],
				$key['shipping_address'],
				$key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
				colorizestatus($key['status']),
				$printslip
			);

			$bardate = $key['assignment_date'];
			$bardev = 	$key['device'];
			$barcourier =	$key['courier'];
			$barcity = 	$key['buyerdeliverycity'];
			$barzone = 	$key['buyerdeliveryzone'];


		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);

		print json_encode($result);
	}

	public function __dispatched()
	{
		$this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
		$this->breadcrumb->add_crumb('In Progress Orders','admin/delivery/assigned');

		$this->table->set_heading(
			'Delivery Date',
			//'Device',
			//'Courier',
			'City',
			'Zone',
			//'Merchant',
			'No Kode Penjualan Toko',
			'Delivery ID',
			'Buyer',
			'Shipping Address',
			'Phone',
			'Status',
			'Actions'
            ); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery date" class="search_init" />',
			'',
			'<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />',
			'<input type="text" name="search_transactionid" id="search_transactionid" value="Search trans ID" class="search_init" />',
			'<input type="text" name="search_deliveryid" id="search_deliveryid" value="Search delivery ID" class="search_init" />'
			);

		$page['sortdisable'] = '0,1,2,3';
		$page['ajaxurl'] = 'admin/delivery/ajaxdispatched';
		$page['page_title'] = 'In Progress Orders';
		$this->ag_auth->view('dispatchajaxlistview',$page); // Load the view
	}


	public function __ajaxdelivered()
	{
		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		// get total count result
		$count_all = $this->db
			->where($this->config->item('delivered_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_mobile_delivered'))
			->count_all($this->config->item('delivered_delivery_table'));

		$count_display_all = $this->db->count_all_results($this->config->item('delivered_delivery_table'));

		$this->db->select('*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name,d.identifier as device,c.fullname as courier');
		$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=b.id','left');
		$this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');
		$this->db->join('couriers as c',$this->config->item('assigned_delivery_table').'.courier_id=c.id','left');


		$data = $this->db
			->where($this->config->item('delivered_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_mobile_delivered'))
			->limit($limit_count, $limit_offset)
			->get($this->config->item('delivered_delivery_table'));

		$result = $data->result_array();

		$aadata = array();


		foreach($result as $value => $key)
		{
			$delete = anchor("admin/delivery/delete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links

			$aadata[] = array(
				'<span id="dt_'.$key['delivery_id'].'">'.$key['deliverytime'].'</span>',
				form_checkbox('assign[]',$key['delivery_id'],FALSE,'class="assign_check"').$key['delivery_id'],
				//$key['application_id'],
				$key['buyer'],
				$key['merchant'],
				$key['merchant_trans_id'],
				$key['courier'],
				$key['shipping_address'],
				$key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
				colorizestatus($key['status']),
				$key['reschedule_ref'],
				$key['revoke_ref']
			);
		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);

		print json_encode($result);
	}

	public function __delivered()
	{
		$this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
		$this->breadcrumb->add_crumb('Delivered Orders','admin/delivery/delivered');

		$data = $this->db->where('status','delivered')->get($this->config->item('delivered_delivery_table'));
		$result = $data->result_array();

		$this->table->set_heading(
			'Delivery Time',
			'Delivery ID',
			//'Application ID',
			'Buyer',
			'Merchant',
			'No Kode Penjualan Toko',
			'Courier',
			'Shipping Address',
			'Phone',
			'Status',
			'Reschedule Ref',
			'Revoke Ref'
			); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery time" class="search_init" />',
			'<input type="text" name="search_device" id="search_device" value="Search device" class="search_init" />',
			'<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
			'<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />'
			);


		$page['ajaxurl'] = 'admin/delivery/ajaxdelivered';
		$page['page_title'] = 'Delivered Orders';
		$this->ag_auth->view('ajaxlistview',$page); // Load the view
	}

	/*rescheduled*/

	public function ____ajaxdelivered()
	{
		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		$sort_col = $this->input->post('iSortCol_0');
		$sort_dir = $this->input->post('sSortDir_0');

		$columns = array(
			'assignment_date',
			'buyerdeliveryzone',
			'delivery_id',
			'device',
			'courier',
			'buyer',
			'shipping_address',
			'phone',
			'status',
			'merchant_id',
			'merchant_trans_id'
			);

		// get total count result
		$count_all = $this->db
			->where($this->config->item('delivered_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_mobile_rescheduled'))
			->count_all($this->config->item('delivered_delivery_table'));

		$count_display_all = $this->db->count_all_results($this->config->item('delivered_delivery_table'));

		$this->db->select('*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name,d.identifier as device,c.fullname as courier');
		$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=b.id','left');
		$this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');
		$this->db->join('couriers as c',$this->config->item('assigned_delivery_table').'.courier_id=c.id','left');

		$search = false;

		//search column
		if($this->input->post('sSearch') != ''){
			$srch = $this->input->post('sSearch');
			$this->db->like('assignment_zone',$srch);
			$this->db->or_like('assignment_date',$srch);
			$this->db->or_like('buyerdeliverytime',$srch);
			$this->db->or_like('delivery_id',$srch);
			$search = true;
		}

		if($this->input->post('sSearch_0') != ''){
			$this->db->like('deliverytime',$this->input->post('sSearch_0'));
			$search = true;
		}

		if($this->input->post('sSearch_1') != ''){
			$this->db->like('delivery_id',$this->input->post('sSearch_1'));
			$search = true;
		}

		if($this->input->post('sSearch_2') != ''){
			$this->db->like('b.fullname',$this->input->post('sSearch_2'));
			$search = true;
		}

		if($this->input->post('sSearch_3') != ''){
			$this->db->like('a.application_name',$this->input->post('sSearch_3'));
			$search = true;
		}

		if($this->input->post('sSearch_4') != ''){
			$this->db->like('merchant_trans_id',$this->input->post('sSearch_4'));
			$search = true;
		}

        if($this->input->post('sSearch_5') != ''){
            $this->db->like('shipping_address',$this->input->post('sSearch_5'));
            $search = true;
        }

        if($this->input->post('sSearch_6') != ''){
            $this->db->like('delivery_note',$this->input->post('sSearch_6'));
            $search = true;
        }

        if($this->input->post('sSearch_7') != ''){
            $this->db->like('phone',$this->input->post('sSearch_7'));
            $search = true;
        }

		if($search){
			//$this->db->and_();
		}

		$data = $this->db
			->where($this->config->item('delivered_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_mobile_delivered'))
			->order_by('deliverytime','desc')
			->limit($limit_count, $limit_offset)
			->get($this->config->item('delivered_delivery_table'));

		$result = $data->result_array();

		$aadata = array();


		foreach($result as $value => $key)
		{
			$delete = anchor("admin/delivery/delete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links

			//$thumbnail = get_thumbnail($key['delivery_id']);

            if($key['status'] == 'pending'){
                $thumbnail = get_thumbnail($key['delivery_id'], 'thumb_multi');
            }else{
                $thumbnail = get_thumbnail($key['delivery_id']);
            }


            $orderno = explode('-',$key['delivery_id']);
            $orderno = array_pop($orderno);

			$aadata[] = array(
				'<span id="dt_'.$key['delivery_id'].'">'.$key['deliverytime'].'</span>',
				form_checkbox('assign[]',$key['delivery_id'],FALSE,'class="assign_check"').'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$orderno.'</span>',
				//$key['application_id'],
				$key['buyer_name'],
				$key['app_name'],
				$this->hide_trx($key['merchant_trans_id']),
				$key['courier'],
				$key['shipping_address'],
				$thumbnail,
                $key['delivery_note'],
				$key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
				colorizestatus($key['status']),
				$key['reschedule_ref'],
				$key['revoke_ref']
			);
		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);

		print json_encode($result);
	}



	public function ____delivered()
	{
		$this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
		$this->breadcrumb->add_crumb('Delivered Orders','admin/delivery/delivered');

		$this->table->set_heading(
			'Delivery Time',
			'Delivery ID',
			//'Application ID',
			'Buyer',
			'Application Domain',
			'No Kode Penjualan Toko',
			'Courier',
			'Shipping Address',
			'Receiver',
            'Note',
			'Phone',
			'Status',
			'Reschedule Ref',
			'Revoke Ref'
			); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_timestamp" value="Search delivery time" class="search_init" />',
			'<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
			'<input type="text" name="search_buyer" id="search_buyer" value="Search buyer" class="search_init" />',
			'<input type="text" name="search_app" id="search_app" value="Search app domain" class="search_init" />',
			'<input type="text" name="search_merchant_trans_id" id="search_merchant_trans_id" value="Search transaction ID" class="search_init" />',
            '',
            '<input type="text" name="search_shipping_address" value="Search Address" class="search_init" />',
            '',
            '<input type="text" name="search_delivery_note" value="Search note" class="search_init" />',
            '<input type="text" name="search_phone" value="Search phone" class="search_init" />'
			//form_button('do_archive','Archive Selection','id="doArchive"')
			);


		$page['ajaxurl'] = 'admin/delivery/ajaxdelivered';
		$page['page_title'] = 'Delivered Orders';
		$this->ag_auth->view('ajaxlistview',$page); // Load the view
	}



	/*revoked*/

	public function __ajaxrevoked()
	{
		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		// get total count result
		$count_all = $this->db
			->where($this->config->item('delivered_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_mobile_delivered'))
			->count_all($this->config->item('delivered_delivery_table'));

		$count_display_all = $this->db->count_all_results($this->config->item('delivered_delivery_table'));

		$this->db->select('*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name,d.identifier as device,c.fullname as courier');
		$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=b.id','left');
		$this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');
		$this->db->join('couriers as c',$this->config->item('assigned_delivery_table').'.courier_id=c.id','left');


		$data = $this->db
			->where($this->config->item('delivered_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_mobile_revoked'))
			->or_where('status',$this->config->item('trans_status_mobile_noshow'))
			->limit($limit_count, $limit_offset)
			->get($this->config->item('delivered_delivery_table'));

		$result = $data->result_array();

		$aadata = array();


		foreach($result as $value => $key)
		{
			$delete = anchor("admin/delivery/delete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links

			$aadata[] = array(
				'<span id="dt_'.$key['delivery_id'].'">'.$key['deliverytime'].'</span>',
				form_checkbox('assign[]',$key['delivery_id'],FALSE,'class="assign_check"').$key['delivery_id'],
				//$key['application_id'],
				$key['buyer'],
				$key['merchant'],
				$key['merchant_trans_id'],
				$key['courier'],
				$key['shipping_address'],
				$key['phone'],
				colorizestatus($key['status']),
				$key['reschedule_ref'],
				$key['revoke_ref']
			);
		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);

		print json_encode($result);
	}

	public function __revoked()
	{
		$this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
		$this->breadcrumb->add_crumb('Revoked Orders','admin/delivery/revoked');

		$data = $this->db->where('status','delivered')->get($this->config->item('delivered_delivery_table'));
		$result = $data->result_array();

		$this->table->set_heading(
			'Delivery Time',
			'Delivery ID',
			//'Application ID',
			'Buyer',
			'Merchant',
			'Merchant Trans ID',
			'Courier',
			'Shipping Address',
			'Phone',
			'Status',
			'Reschedule Ref',
			'Revoke Ref'
			); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery time" class="search_init" />',
			'<input type="text" name="search_device" id="search_device" value="Search device" class="search_init" />',
			'<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
			'<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />'
			);


		$page['ajaxurl'] = 'admin/delivery/ajaxrevoked';
		$page['page_title'] = 'Revoked Orders';
		$this->ag_auth->view('ajaxlistview',$page); // Load the view
	}


	public function ajaxrevoked()
	{
		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		$sort_col = $this->input->post('iSortCol_0');
		$sort_dir = $this->input->post('sSortDir_0');

		$columns = array(
			'assignment_date',
			'buyerdeliveryzone',
			'delivery_id',
			'device',
			'courier',
			'buyer',
			'shipping_address',
			'phone',
			'status',
			'merchant_id',
			'merchant_trans_id'
			);

		// get total count result
		$count_all = $this->db
			->where($this->config->item('delivered_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_mobile_rescheduled'))
			->count_all($this->config->item('delivered_delivery_table'));

		$count_display_all = $this->db->count_all_results($this->config->item('delivered_delivery_table'));

		$this->db->select('*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name,d.identifier as device,c.fullname as courier');
		$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=b.id','left');
		$this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');
		$this->db->join('couriers as c',$this->config->item('assigned_delivery_table').'.courier_id=c.id','left');

		$search = false;

		//search column
		if($this->input->post('sSearch') != ''){
			$srch = $this->input->post('sSearch');
			$this->db->like('assignment_zone',$srch);
			$this->db->or_like('assignment_date',$srch);
			$this->db->or_like('buyerdeliverytime',$srch);
			$this->db->or_like('delivery_id',$srch);
			$search = true;
		}

		if($this->input->post('sSearch_0') != ''){
			$this->db->like('deliverytime',$this->input->post('sSearch_0'));
			$search = true;
		}

		if($this->input->post('sSearch_1') != ''){
			$this->db->like('delivery_id',$this->input->post('sSearch_1'));
			$search = true;
		}

		if($this->input->post('sSearch_2') != ''){
			$this->db->like('b.fullname',$this->input->post('sSearch_2'));
			$search = true;
		}

		if($this->input->post('sSearch_3') != ''){
			$this->db->like('a.application_name',$this->input->post('sSearch_3'));
			$search = true;
		}

		if($this->input->post('sSearch_4') != ''){
			$this->db->like('merchant_trans_id',$this->input->post('sSearch_4'));
			$search = true;
		}

        if($this->input->post('sSearch_5') != ''){
            $this->db->like('shipping_address',$this->input->post('sSearch_5'));
            $search = true;
        }

        if($this->input->post('sSearch_6') != ''){
            $this->db->like('delivery_note',$this->input->post('sSearch_6'));
            $search = true;
        }

        if($this->input->post('sSearch_7') != ''){
            $this->db->like('phone',$this->input->post('sSearch_7'));
            $search = true;
        }

		if($search){
			//$this->db->and_();
		}

		$this->db
			->where($this->config->item('delivered_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->and_()->group_start()
			->where('status',$this->config->item('trans_status_mobile_revoked'))
			->or_where('status',$this->config->item('trans_status_mobile_noshow'))
			->group_end();

		$data = $this->db
			->limit($limit_count, $limit_offset)
			->order_by($this->config->item('delivered_delivery_table').'.deliverytime','desc')
			->get($this->config->item('delivered_delivery_table'));

		$result = $data->result_array();

		$aadata = array();


		foreach($result as $value => $key)
		{
			$delete = anchor("admin/delivery/delete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links

			$aadata[] = array(
				'<span id="dt_'.$key['delivery_id'].'">'.$key['deliverytime'].'</span>',
				'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$key['delivery_id'].'</span>',
				//form_checkbox('assign[]',$key['delivery_id'],FALSE,'class="assign_check"').$key['delivery_id'],
				//$key['application_id'],
				$key['buyer'],
				$key['app_name'],
				$key['merchant_trans_id'],
				$key['courier'],
				$key['shipping_address'],
				get_thumbnail($key['delivery_id']),
                $key['delivery_note'],
				$key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
				colorizestatus($key['status']),
				$key['reschedule_ref'],
				$key['revoke_ref']
			);
		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);

		print json_encode($result);
	}

	public function revoked()
	{
		$this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
		$this->breadcrumb->add_crumb('Revoked Orders','admin/delivery/revoked');

		$this->table->set_heading(
			'Delivery Time',
			'Delivery ID',
			//'Application ID',
			'Buyer',
			'Application Domain',
			'Merchant Trans ID',
			'Courier',
			'Shipping Address',
			'Addressee',
            'Note',
			'Phone',
			'Status',
			'Reschedule Ref',
			'Revoke Ref'
			); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_timestamp" value="Search delivery time" class="search_init" />',
			'<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
			'<input type="text" name="search_buyer" id="search_buyer" value="Search buyer" class="search_init" />',
			'<input type="text" name="search_app" id="search_app" value="Search app domain" class="search_init" />',
			'<input type="text" name="search_merchant_trans_id" id="search_merchant_trans_id" value="Search transaction ID" class="search_init" />',
            '',
            '<input type="text" name="search_shipping_address" value="Search Address" class="search_init" />',
            '',
            '<input type="text" name="search_delivery_note" value="Search note" class="search_init" />',
            '<input type="text" name="search_phone" value="Search phone" class="search_init" />'
			//form_button('do_archive','Archive Selection','id="doArchive"')
			);


		$page['ajaxurl'] = 'admin/delivery/ajaxrevoked';
		$page['page_title'] = 'Revoked Orders';
		$this->ag_auth->view('ajaxlistview',$page); // Load the view
	}


	/*rescheduled*/

	public function ajaxrescheduled()
	{
		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		$sort_col = $this->input->post('iSortCol_0');
		$sort_dir = $this->input->post('sSortDir_0');

		$columns = array(
			'assignment_date',
			'buyerdeliveryzone',
			'delivery_id',
			'device',
			'courier',
			'buyer',
			'shipping_address',
			'phone',
			'status',
			'merchant_id',
			'merchant_trans_id'
			);

		// get total count result
		$count_all = $this->db
			->where($this->config->item('delivered_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_mobile_rescheduled'))
			->count_all($this->config->item('delivered_delivery_table'));

		$count_display_all = $this->db->count_all_results($this->config->item('delivered_delivery_table'));

		$this->db->select('*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name,d.identifier as device,c.fullname as courier');
		$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=a.id','left');
		$this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');
		$this->db->join('couriers as c',$this->config->item('assigned_delivery_table').'.courier_id=c.id','left');

		$search = false;

		//search column
		if($this->input->post('sSearch') != ''){
			$srch = $this->input->post('sSearch');
			$this->db->like('assignment_zone',$srch);
			$this->db->or_like('assignment_date',$srch);
			$this->db->or_like('buyerdeliverytime',$srch);
			$this->db->or_like('delivery_id',$srch);
			$search = true;
		}

		if($this->input->post('sSearch_0') != ''){
			$this->db->like('deliverytime',$this->input->post('sSearch_0'));
			$search = true;
		}

		if($this->input->post('sSearch_1') != ''){
			$this->db->like('delivery_id',$this->input->post('sSearch_1'));
			$search = true;
		}

		if($this->input->post('sSearch_2') != ''){
			$this->db->like('b.fullname',$this->input->post('sSearch_2'));
			$search = true;
		}

		if($this->input->post('sSearch_3') != ''){
			$this->db->like('a.application_name',$this->input->post('sSearch_3'));
			$search = true;
		}

		if($this->input->post('sSearch_4') != ''){
			$this->db->like('merchant_trans_id',$this->input->post('sSearch_4'));
			$search = true;
		}

        if($this->input->post('sSearch_5') != ''){
            $this->db->like('shipping_address',$this->input->post('sSearch_5'));
            $search = true;
        }

        if($this->input->post('sSearch_6') != ''){
            $this->db->like('delivery_note',$this->input->post('sSearch_6'));
            $search = true;
        }

        if($this->input->post('sSearch_7') != ''){
            $this->db->like('phone',$this->input->post('sSearch_7'));
            $search = true;
        }


		if($search){
			//$this->db->and_();
		}

		$data = $this->db
			->where($this->config->item('delivered_delivery_table').'.merchant_id',$this->session->userdata('userid'))
			->where('status',$this->config->item('trans_status_mobile_rescheduled'))
			->order_by('deliverytime','desc')
			->limit($limit_count, $limit_offset)
			->get($this->config->item('delivered_delivery_table'));

		$result = $data->result_array();

		$aadata = array();


		foreach($result as $value => $key)
		{
			$delete = anchor("admin/delivery/delete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/delivery/edit/".$key['id']."/", "Edit"); // Build actions links
			$cancel = '<span class="cancel_link" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">Cancel</span>';
			$proceed = '<span class="reschedule_link" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">Proceed</span>';

			$aadata[] = array(
				'<span id="dt_'.$key['delivery_id'].'">'.$key['deliverytime'].'</span>',
				'<span id="'.$key['delivery_id'].'"><input type="hidden" value="'.$key['buyerdeliverytime'].'" id="cd_'.$key['delivery_id'].'">'.$key['buyerdeliverytime'].'</span>',
				form_checkbox('assign[]',$key['delivery_id'],FALSE,'class="assign_check"').'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$key['delivery_id'].'</span>',
				//$key['application_id'],
				$key['buyer'],
				$key['app_name'],
				$key['merchant_trans_id'],
				$key['courier'],
				$key['shipping_address'],
				get_thumbnail($key['delivery_id']),
                $key['delivery_note'],
				$key['phone'].'<br />'.$key['mobile1'].'<br />'.$key['mobile2'],
				colorizestatus($key['status']),
				$proceed.' '.$cancel
				//$key['reschedule_ref'],
				//$key['revoke_ref']
			);
		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);

		print json_encode($result);
	}

	public function rescheduled()
	{
		$this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
		$this->breadcrumb->add_crumb('Rescheduled Orders','admin/delivery/rescheduled');

		$this->table->set_heading(
			'Delivery Time',
			'Requested Time',
			'Delivery ID',
			//'Application ID',
			'Buyer',
			'Application Domain',
			'Merchant Trans ID',
			'Courier',
			'Shipping Address',
			'Receiver',
            'Note',
			'Phone',
			'Status',
			'Action'
			//'Reschedule Ref',
			//'Revoke Ref'
			); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_timestamp" value="Search delivery time" class="search_init" />',
			'',
			'<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
			'<input type="text" name="search_buyer" id="search_buyer" value="Search buyer" class="search_init" />',
			'<input type="text" name="search_app" id="search_app" value="Search app domain" class="search_init" />',
			'<input type="text" name="search_merchant_trans_id" id="search_merchant_trans_id" value="Search transaction ID" class="search_init" />',
            '',
            '<input type="text" name="search_shipping_address" value="Search Address" class="search_init" />',
            '',
            '<input type="text" name="search_delivery_note" value="Search note" class="search_init" />',
            '<input type="text" name="search_phone" value="Search phone" class="search_init" />'

			//form_button('do_archive','Archive Selection','id="doArchive"')
			);


		$page['ajaxurl'] = 'admin/delivery/ajaxrescheduled';
		$page['page_title'] = 'Rescheduled Orders';
		$this->ag_auth->view('rescheduledajaxlistview',$page); // Load the view
	}

	public function ajaxlog()
	{
		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		// get total count result
		$count_all = $this->db
			->count_all($this->config->item('delivery_log_table'));

		$count_display_all = $this->db
			->count_all_results($this->config->item('delivery_log_table'));

/*
		$this->db->select('*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name,d.identifier as device,c.fullname as courier');
		$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=b.id','left');
		$this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');
		$this->db->join('couriers as c',$this->config->item('assigned_delivery_table').'.courier_id=c.id','left');
*/

		$data = $this->db
			->limit($limit_count, $limit_offset)
			->get($this->config->item('delivery_log_table'));

		$result = $data->result_array();

		$aadata = array();


		foreach($result as $value => $key)
		{
			$aadata[] = array(
				$key['timestamp'],
				$key['report_timestamp'],
				'<span class="view_detail" id="'.$key['delivery_id'].'" style="text-decoration:underline;cursor:pointer;">'.$key['delivery_id'].'</span>',
				$key['device_id'],
				$key['courier_id'],
				$key['actor_type'],
				$key['actor_id'],
				$key['latitude'],
				$key['longitude'],
				$key['status'],
				$key['notes']
			);
		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);

		print json_encode($result);
	}

	public function log()
	{
		$this->breadcrumb->add_crumb('Orders','admin/delivery/incoming');
		$this->breadcrumb->add_crumb('Delivery Log','admin/delivery/log');

		$data = $this->db->get($this->config->item('delivery_log_table'));
		$result = $data->result_array();

		$this->table->set_heading(
			'Captured',
			'Reported',
			'Delivery ID',
			'Device ID',
			'Courier',
			'Actor',
			'Actor ID',
			'Latitude',
			'Longitude',
			'Status',
			'Note'
			); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery time" class="search_init" />',
			'<input type="text" name="search_device" id="search_device" value="Search device" class="search_init" />',
			'<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
			'<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />'
			);


		$page['ajaxurl'] = 'admin/delivery/ajaxlog';
		$page['page_title'] = 'Delivery Log';
		$this->ag_auth->view('archivedajaxlistview',$page); // Load the view
	}

	public function view($delivery_id){
		$this->db->select($this->config->item('assigned_delivery_table').'.*,b.fullname as buyer,m.merchantname as merchant,a.application_name as app_name,d.identifier as device,c.fullname as courier');
		$this->db->join('members as b',$this->config->item('assigned_delivery_table').'.buyer_id=b.id','left');
		$this->db->join('members as m',$this->config->item('assigned_delivery_table').'.merchant_id=m.id','left');
		$this->db->join('applications as a',$this->config->item('assigned_delivery_table').'.application_id=b.id','left');
		$this->db->join('devices as d',$this->config->item('assigned_delivery_table').'.device_id=d.id','left');
		$this->db->join('couriers as c',$this->config->item('assigned_delivery_table').'.courier_id=c.id','left');

		$res = $this->db->where('delivery_id',$delivery_id)->get($this->config->item('assigned_delivery_table'));
		$result = $res->row_array();

		$data['order_details'] = $result;
		$this->load->view('auth/pages/viewdetails',$data);
	}

	public function deleteassigned($id)
	{
		$this->db->where('id', $id)->delete($this->config->item('assigned_delivery_table'));

		$data['page_title'] = 'Delete';
		$data['message'] = "Delivery order is now assigned to device.";
		$data['back_url'] = anchor('admin/delivery/assigned','Back to list');
		$this->ag_auth->view('message', $data);
	}

	public function delete($id)
	{
		$this->db->where('delivery_id', $id)->delete($this->config->item('incoming_delivery_table'));

		$data['page_title'] = 'Delete';
		$data['message'] = "Delivery order is now assigned to device.";
		$data['back_url'] = anchor('admin/delivery/incoming','Back to list');
		$this->ag_auth->view('message', $data);
	}

	public function get_devices(){
		$this->db->select('id,identifier,descriptor,devname,mobile');
		$result = $this->db->get($this->config->item('jayon_devices_table'));
		foreach($result->result_array() as $row){
			$res[$row['id']] = $row['descriptor'].'['.$row['mobile'].']';
		}
		return $res;
	}

	public function get_device_info($device_id){
		$result = $this->db->where('id',$device_id)->get($this->config->item('jayon_devices_table'));
		return $result->row_array();
	}

	public function get_app_info($app_key){
		$result = $this->db->where('key',$app_key)->get($this->config->item('applications_table'));
		return $result->row_array();
	}

	public function add()
	{
		$this->form_validation->set_rules('username', 'Username', 'required|min_length[6]|callback_field_exists');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[password_conf]');
		$this->form_validation->set_rules('password_conf', 'Password Confirmation', 'required|min_length[6]|matches[password]');
		$this->form_validation->set_rules('email', 'Email Address', 'required|min_length[6]|valid_email|callback_field_exists');
		$this->form_validation->set_rules('group_id', 'Group', 'trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['groups'] = $this->get_group();
			$page['page_title'] = 'Add User';
			$this->ag_auth->view('users/add',$data);
		}
		else
		{
			$username = set_value('username');
			$password = $this->ag_auth->salt(set_value('password'));
			$email = set_value('email');
			$group_id = set_value('group_id');

			if($this->ag_auth->register($username, $password, $email, $group_id) === TRUE)
			{
				$data['message'] = "The user account has now been created.";
				$this->ag_auth->view('message', $data);

			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$data['message'] = "The user account has not been created.";
				$this->ag_auth->view('message', $data);
			}

		} // if($this->form_validation->run() == FALSE)

	} // public function register()

	public function edit($username)
	{
		$this->form_validation->set_rules('username', 'Username', 'required|min_length[6]|callback_field_exists');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[password_conf]');
		$this->form_validation->set_rules('password_conf', 'Password Confirmation', 'required|min_length[6]|matches[password]');
		$this->form_validation->set_rules('email', 'Email Address', 'required|min_length[6]|valid_email|callback_field_exists');
		$this->form_validation->set_rules('group_id', 'Group', 'trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['groups'] = $this->get_group();
			$this->ag_auth->view('users/add',$data);
		}
		else
		{
			$username = set_value('username');
			$password = $this->ag_auth->salt(set_value('password'));
			$email = set_value('email');
			$group_id = set_value('group_id');

			if($this->ag_auth->register($username, $password, $email, $group_id) === TRUE)
			{
				$data['message'] = "The user account has now been created.";
				$this->ag_auth->view('message', $data);

			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$data['message'] = "The user account has not been created.";
				$this->ag_auth->view('message', $data);
			}

		} // if($this->form_validation->run() == FALSE)

	} // public function register()

	public function ajaxdevicecap(){

		$assignment_date = $this->input->post('assignment_date');
		$assignment_zone = $this->input->post('assignment_zone');
		$assignment_city = $this->input->post('assignment_city');

		$dev = $this->db->select('id,identifier,descriptor,devname')->where('city',$assignment_city)->get($this->config->item('jayon_devices_table'));
		$result = array();

		$slots = get_option('daily_shifts');

		$slotradio = '<input type="radio" name="timeslot[]" value="%s" class="timeslot" > %s [ %s ]';

		foreach($dev->result_array() as $device){

			$slotform = '';
			for($sl = 1;$sl <= $slots;$sl++){
				$count_dev = $this->db
					->where('assignment_date',$assignment_date)
					->where('assignment_timeslot',$sl)
					->where('device_id',$device['id'])
					->count_all_results($this->config->item('assigned_delivery_table'));
				//$result[] = array('id'=>$device['id'],'device'=>$device['identifier'],'assignment'=>$count_dev);
				$slotform .= sprintf($slotradio,$sl, $sl,$count_dev);
			}
			$result[] = sprintf('<li style="padding:5px;border-bottom:thin solid grey;margin-left:0px;"><input type="radio" name="dev_id" value="%s">%s <br />Delivery Slot : %s</li>',
				$device['id'],
				$device['identifier'].' - '.$device['devname'],
				$slotform );
		}
		print json_encode(array('html'=>implode('',$result)));

	}

	public function ajaxassign(){

	}

	public function ajaxchangestatus(){
		$delivery_id = $this->input->post('delivery_id');
		$dataset['status'] = $this->input->post('new_status');
		$dataset['change_actor']= $this->input->post('actor').':'.$this->session->userdata('userid');

		if(
			$dataset['status'] == $this->config->item('trans_status_mobile_delivered') ||
			$dataset['status'] == $this->config->item('trans_status_mobile_revoked') ||
			$dataset['status'] == $this->config->item('trans_status_mobile_noshow')){
			$dataset['deliverytime'] = date('Y-m-d H:i:s', time());
		}

		if($this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),$dataset) === TRUE)
		{
			$order_exist = 'ok';
		}
		else
		{
			$order_exist = 'ORDER_FAILED_ASSIGNMENT';
		}

				$data = array(
					'timestamp'=>date('Y-m-d H:i:s',time()),
					'report_timestamp'=>date('Y-m-d H:i:s',time()),
					'delivery_id'=>$delivery_id,
					'device_id'=>'',
					'courier_id'=>'',
					'actor_type'=>$this->input->post('actor'),
					'actor_id'=>$this->session->userdata('userid'),
					'latitude'=>'',
					'longitude'=>'',
					'status'=>$this->input->post('new_status'),
					'notes'=>''
				);

			delivery_log($data);

		print json_encode(array('result'=>$order_exist));
	}

	public function getzone(){
		$q = $this->input->get('term');
		$zones = ajax_find_zones($q,'district');
		print json_encode($zones);
	}

	public function assign($delivery_id){

		$this->form_validation->set_rules('device_id', 'Device ID', 'required|trim|xss_clean');
		$this->form_validation->set_rules('assignment_date', 'Assignment Date', 'required|trim|xss_clean');

		if($this->form_validation->run() == FALSE)
		{
			$data['devices'] = $this->get_devices();
			$data['delivery_id'] = $delivery_id;

			$data['page_title'] = 'Delivery Assigment - '.$delivery_id;
			$data['back_url'] = anchor('admin/delivery/assigned','Back to list');
			$this->ag_auth->view('delivery/assign',$data);
		}
		else
		{
			$device_id = set_value('device_id');
			$assignment_date = set_value('assignment_date');

			$order_exist = $this->do_date_assignment($delivery_id,$assignment_date,$device_id);

			if($order_exist == 'ORDER_ALREADY_ASSIGNED'){
				$data['message'] = 'Delivery order: '.$delivery_id.' already assigned. Please use "re-assign" in Assigned Delivery list';
				$data['back_url'] = anchor('admin/delivery/assigned','Back to list');
				$this->ag_auth->view('message', $data);
			}else if($order_exist == 'ORDER_ASSIGNED'){
				$data['page_title'] = 'Delivery Assigment - '.$delivery_id;
				$data['message'] = "Delivery order is now assigned to date.";
				$data['back_url'] = anchor('admin/delivery/assigned','Back to list');
				$this->ag_auth->view('message', $data);
			}else if($order_exist == 'ORDER_FAILED_ASSIGNMENT'){
				$data['page_title'] = 'Delivery Assigment - '.$delivery_id;
				$data['message'] = "Failed to assign delivery order.";
				$data['back_url'] = anchor('admin/delivery/assigned','Back to list');
				$this->ag_auth->view('message', $data);
			}

		} // if($this->form_validation->run() == FALSE)

	}

	private function do_date_assignment($delivery_id,$assignment_date){
		/*
		$incoming = $this->db->where('delivery_id',$delivery_id)->get($this->config->item('incoming_delivery_table'));
		$dataset = $incoming->row_array();
		unset($dataset['id']);
		*/
		$dataset['status'] = $this->config->item('trans_status_admin_dated');
		$dataset['assigntime'] = date('Y-m-d H:i:s',time());
		$dataset['assignment_date'] = $assignment_date;

		if($this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),$dataset) === TRUE)
		{
			$order_exist = 'ORDER_ASSIGNED';
		}
		else
		{
			$order_exist = 'ORDER_FAILED_ASSIGNMENT';
		}

			$data = array(
				'timestamp'=>date('Y-m-d H:i:s',time()),
				'report_timestamp'=>date('Y-m-d H:i:s',time()),
				'delivery_id'=>$delivery_id,
				'device_id'=>'',
				'courier_id'=>'',
				'actor_type'=>'MC',
				'actor_id'=>$this->session->userdata('userid'),
				'latitude'=>'',
				'longitude'=>'',
				'status'=>$this->config->item('trans_status_admin_dated'),
				'notes'=>''
			);

		delivery_log($data);

		return $order_exist;
	}

	private function do_zone_assignment($delivery_id,$device_id,$assignment_zone,$assignment_city,$assignment_timeslot){
		//$incoming = $this->db->where('delivery_id',$delivery_id)->get($this->config->item('incoming_delivery_table'));
		//$dataset = $incoming->row_array();
		//unset($dataset['id']);
		$dataset['device_id'] = $device_id;
		$dataset['status'] = $this->config->item('trans_status_admin_devassigned');
		//$dataset['assigntime'] = date('Y-m-d H:i:s',time());
		$dataset['assignment_zone'] = $assignment_zone;
		$dataset['assignment_city'] = $assignment_city;
		$dataset['assignment_timeslot'] = $assignment_timeslot;

		if($this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),$dataset) == TRUE)
		{
			$order_exist = 'ORDER_ASSIGNED';
		}
		else
		{
			$order_exist = 'ORDER_FAILED_ASSIGNMENT';
		}

			$data = array(
				'timestamp'=>date('Y-m-d H:i:s',time()),
				'report_timestamp'=>date('Y-m-d H:i:s',time()),
				'delivery_id'=>$delivery_id,
				'device_id'=>'',
				'courier_id'=>'',
				'actor_type'=>'MC',
				'actor_id'=>$this->session->userdata('userid'),
				'latitude'=>'',
				'longitude'=>'',
				'status'=>$this->config->item('trans_status_admin_zoned'),
				'notes'=>''
			);

		delivery_log($data);

		return $order_exist;
	}

	private function do_reschedule($delivery_id,$buyerdeliverytime,$status,$stage){

		if($stage == 'dispatched'){

			$this->db->select('*,b.email as buyeremail');
			$this->db->join('members as b','delivery_order_incoming.buyer_id=b.id','left');

			$incomingcomplete = $this->db->where('delivery_id',$delivery_id)->get($this->config->item('incoming_delivery_table'));
			$datasetcomplete = $incomingcomplete->row_array();
			$buyeremail = $datasetcomplete['buyeremail'];

			//generate new delivery id
			full_reschedule($delivery_id, $datachanged);

		}else if($stage == 'incoming'){
			$actor = $this->session->userdata('userid');
			$change_actor = 'A:'.$actor;
			$this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),array('buyerdeliverytime'=>$buyerdeliverytime, 'change_actor'=>$change_actor));

			$this->db->select('*,b.fullname as buyerfullname,b.email as buyeremail,m.merchantname as merchantname,a.* as app');
			$this->db->join('members as b',$this->config->item('incoming_delivery_table').'.buyer_id=b.id','left');
			$this->db->join('members as m',$this->config->item('incoming_delivery_table').'.merchant_id=m.id','left');
			$this->db->join('applications as a',$this->config->item('incoming_delivery_table').'.application_id=b.id','left');


			$fullorder = $this->db->where('delivery_id',$delivery_id)->get($this->config->item('incoming_delivery_table'));

			$fullorder = $fullorder->row_array();

			$fullorder['new_date'] = $buyerdeliverytime;

			$order_exist = $fullorder;

			$data = array(
				'timestamp'=>date('Y-m-d H:i:s',time()),
				'report_timestamp'=>date('Y-m-d H:i:s',time()),
				'delivery_id'=>$delivery_id,
				'device_id'=>'',
				'courier_id'=>'',
				'actor_type'=>'MC',
				'actor_id'=>$this->session->userdata('userid'),
				'latitude'=>'',
				'longitude'=>'',
				'status'=>$this->config->item('trans_status_rescheduled'),
				'notes'=>''
			);

			delivery_log($data);

		}

		return $order_exist;
	}

	private function do_revoke($delivery_id,$buyerdeliverytime,$status,$table){

		if($table == 'dispatched'){

			$this->db->select('*,b.email as buyeremail');
			$this->db->join('members as b','delivery_order_incoming.buyer_id=b.id','left');

			$incomingcomplete = $this->db->where('delivery_id',$delivery_id)->get($this->config->item('incoming_delivery_table'));
			$datasetcomplete = $incomingcomplete->row_array();
			$buyeremail = $datasetcomplete['buyeremail'];


			$incoming = $this->db->where('delivery_id',$delivery_id)->get($this->config->item('incoming_delivery_table'));
			$dataset = $incoming->row_array();

			unset($dataset['id']);

			$dataset['status'] = 'pending';

			if($status == 'rescheduled'){
				$dataset['reschedule_ref'] = $dataset['delivery_id'];
				$dataset['buyerdeliverytime'] = $buyerdeliverytime;
			}else if($status == 'revoked'){
				$dataset['revoke_ref'] = $dataset['delivery_id'];
			}
			//generate new delivery id

			if($this->db->insert($this->config->item('incoming_delivery_table'),$dataset) == true)
			{
				$sequence = $this->db->insert_id();

				$year_count = str_pad($sequence, 10, '0', STR_PAD_LEFT);
				$merchant_id = str_pad($dataset['merchant_id'], 8, '0', STR_PAD_LEFT);
				$delivery_id = $merchant_id.'-'.date('d-mY',time()).'-'.$year_count;

				$this->db->where('id',$sequence)->update($this->config->item('incoming_delivery_table'),array('delivery_id'=>$delivery_id));

				$order_exist = $delivery_id;
			}
			else
			{
				$order_exist = 'ORDER_FAILED_ASSIGNMENT';
			}

		}else if($table == 'incoming'){
			$actor = $this->session->userdata('userid');
			$change_actor = 'A:'.$actor;
			$this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),array('buyerdeliverytime'=>$buyerdeliverytime, 'change_actor'=>$change_actor));
			$order_exist = 'ORDER_UPDATED';

			$data = array(
				'timestamp'=>date('Y-m-d H:i:s',time()),
				'report_timestamp'=>date('Y-m-d H:i:s',time()),
				'delivery_id'=>$delivery_id,
				'device_id'=>'',
				'courier_id'=>'',
				'actor_type'=>'MC',
				'actor_id'=>$this->session->userdata('userid'),
				'latitude'=>'',
				'longitude'=>'',
				'status'=>$this->config->item('trans_status_rescheduled'),
				'notes'=>''
			);

			delivery_log($data);

		}

		return $order_exist;
	}

    public function hide_trx($trx_id){
        if(preg_match('/^TRX_/', $trx_id)){
            return '';
        }else{
            return $trx_id;
        }
    }


}

?>