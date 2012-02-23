<?php

class Buyer extends Application
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

	public function get_app_info($app_key){
		$result = $this->db->where('key',$app_key)->get($this->config->item('applications_table'));
		return $result->row_array();
	}

	public function getzone(){
		$q = $this->input->get('term');
		$zones = ajax_find_zones($q,'district');
		print json_encode($zones);
	}

	public function ajaxorders(){

		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		$sort_col = $this->input->post('iSortCol_0');
		$sort_dir = $this->input->post('sSortDir_0');

		$columns = array(
			'assignment_date',
			'buyerdeliveryzone',
			'delivery_id',
			'buyer',
			'shipping_address',
			'phone',
			'status',
			'merchant_trans_id'
			);

		// get total count result
		$count_all = $this->db->count_all($this->config->item('assigned_delivery_table'));

		$count_display_all = $this->db
			->where($this->config->item('assigned_delivery_table').'.buyer_id',$this->session->userdata('userid'))
			->count_all_results($this->config->item('assigned_delivery_table'));

		//search column
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
			$this->db->like('assignment_date',$this->input->post('sSearch_0'));
			$search = true;
		}

		if($this->input->post('sSearch_1') != ''){
			$this->db->like('buyerdeliveryzone',$this->input->post('sSearch_1'));
			$search = true;
		}

		if($this->input->post('sSearch_2') != ''){
			$this->db->like('delivery_id',$this->input->post('sSearch_2'));
			$search = true;
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
			->where($this->config->item('assigned_delivery_table').'.buyer_id',$this->session->userdata('userid'))
			->and_()->group_start()
			->where('status !=',$this->config->item('trans_status_mobile_delivered'))
			->group_end();

		$data = $this->db->limit($limit_count, $limit_offset)
			->order_by($columns[$sort_col],$sort_dir)
			->get($this->config->item('assigned_delivery_table'));

		//print $this->db->last_query();

		$result = $data->result_array();

		$aadata = array();

		foreach($result as $value => $key)
		{

			$aadata[] = array(
				$key['assignment_date'],
				$key['buyerdeliverycity'],
				$key['buyerdeliveryzone'],
				$key['courier'],
				$key['delivery_id'],
				$key['merchant'],
				$key['merchant_trans_id'],
				$key['buyer'],
				$key['shipping_address'],
				$key['phone'],
				colorizestatus($key['status'])
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

	public function orders()
	{
		$this->breadcrumb->add_crumb('My Orders','admin/buyer/orders');
		$this->breadcrumb->add_crumb('In Progress Orders','admin/buyer/orders');

		$this->table->set_heading(
			'Delivery Date',
			'City',
			'Zone',
			'Courier',
			'Delivery ID',
			'Merchant',
			'Merchant Trans ID',
			'Buyer',
			'Shipping Address',
			'Phone',
			'Status'
			); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_deliverytime" value="Search delivery date" class="search_init" />',
			'',
			'<input type="text" name="search_zone" id="search_zone" value="Search zone" class="search_init" />',
			'',
			'<input type="text" name="search_deliveryid" id="search_deliveryid" value="Search delivery ID" class="search_init" />'
			);

		$page['sortdisable'] = '0,1,2,3';
		$page['ajaxurl'] = 'admin/buyer/ajaxorders';
		$page['page_title'] = 'In Progress Orders';
		$this->ag_auth->view('dispatchajaxlistview',$page); // Load the view
	}

	public function ajaxdelivered()
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
			->where($this->config->item('delivered_delivery_table').'.buyer_id',$this->session->userdata('userid'))
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

		if($search){
			//$this->db->and_();
		}

		$data = $this->db
			->where($this->config->item('delivered_delivery_table').'.buyer_id',$this->session->userdata('userid'))
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
				$key['merchant_trans_id'],
				$key['courier'],
				$key['shipping_address'],
				$key['phone'],
				colorizestatus($key['status'])
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
		$this->breadcrumb->add_crumb('My Orders','admin/buyer/orders');
		$this->breadcrumb->add_crumb('Delivered Orders','admin/buyer/delivered');

		$this->table->set_heading(
			'Delivery Time',
			'Delivery ID',
			//'Application ID',
			'Buyer',
			'Merchant Trans ID',
			'Courier',
			'Shipping Address',
			'Phone',
			'Status'
			); // Setting headings for the table

		$this->table->set_footing(
			'<input type="text" name="search_deliverytime" id="search_timestamp" value="Search delivery time" class="search_init" />',
			'<input type="text" name="search_deliveryid" value="Search delivery ID" class="search_init" />',
			'<input type="text" name="search_buyer" id="search_buyer" value="Search buyer" class="search_init" />',
			'<input type="text" name="search_merchant_trans_id" id="search_merchant_trans_id" value="Search transaction ID" class="search_init" />'
			//form_button('do_archive','Archive Selection','id="doArchive"')
			);


		$page['ajaxurl'] = 'admin/buyer/ajaxdelivered';
		$page['page_title'] = 'Delivered Orders';
		$this->ag_auth->view('ajaxlistview',$page); // Load the view
	}


}

?>