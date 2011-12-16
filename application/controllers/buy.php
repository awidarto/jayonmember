<?php

class Buy extends Application
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($trx_id)
	{

		if(logged_in())
		{
			$page['page_title'] = 'Buy';
			$this->ag_auth->view('buy',$page);
		}
		else
		{
			$this->login('admin/buy/trx/'.$api_key.'/'.$trx_id);
		}
	}
	
	public function trx($api_key,$trx_id){

		$data['trx_id'] = $trx_id;
		$data['api_key'] = $api_key;

		if(logged_in())
		{
			redirect('buy/process/'.$api_key.'/'.$trx_id);
			
			//$data['page_title'] = 'Buy';
			//$this->ag_auth->view('buy',$data);
		}
		else
		{
			$data['page_title'] = 'Buy';
			$this->ag_auth->buyview('loginreg',$data);
		}
		
	}
	
	public function process($api_key,$trx_id){
		
		$merchant = $this->get_key_info($api_key);
		if($trx_info = $this->get_trx_info($trx_id)){
			if($trx_info->table == $this->config->item('assigned_delivery_table')){
				//transaction already assigned
				$data['message'] = "This delivery order has already processed and ongoing delivery. have a nice day :)";
				$data['page_title'] = 'Delivery is on its way';
				$this->ag_auth->buyview('message',$data);
			}else if($trx_info->table == $this->config->item('incoming_delivery_table')){
				//transaction already recorded in incoming table
				$data['message'] = "This delivery order has already received. Would you like to make changes ?";
				$data['page_title'] = 'Delivery order recieved';
				$this->ag_auth->buyview('message',$data);
			}
		}else{
				
			if($merchant->fetch_method == 'URL'){
				$url = $merchant->fetch_detail_url.'/'.$api_key.'/'.$trx_id;
			}else{
				$url = $merchant->fetch_detail_url.'?key='.$api_key.'&trx='.$trx_id;
			}
			
			//print $url;
			$trx_detail = $this->curl->simple_get($url);
			//print $trx_detail;
			
			$delivery_id = $this->trxrecord($api_key,$trx_id,$trx_detail);
			
			$trx_detail = get_object_vars(json_decode($trx_detail));
			
			$this->table->set_heading(
				'No.',		 	 	
				'Description',	 	 	 	 	 	 	 
				'Unit Price',			
				'Quantity',		
				'Total',			
				'Discount'
				); // Setting headings for the table

			$d = 0;
			$gt = 0;
			
			//print_r($trx_detail['trx_detail'] );
			$seq = 1;
			foreach($trx_detail['trx_detail'] as $val)
			{

				$this->table->add_row(
					$seq,		 	 	
					$val->unit_description,	 	 	 	 	 	 	 
					$val->unit_price,			
					$val->unit_quantity,		
					$val->unit_total,			
					$val->unit_discount
				);

				$gt += $val->unit_total;
				$d += $val->unit_discount;
				$seq++;
			}
			
			if(isset($trx_detail['cod_charges'])){
				$this->table->add_row(
					'&nbsp',		
					'&nbsp',		
					'&nbsp',		
					'COD Charges',		
					($trx_detail['cod_charges'] == 0 )?'-':$trx_detail['cod_charges'],			
					'-'
				);
				
				$gt += $trx_detail['cod_charges'];
			}

			$this->table->add_row(
				'&nbsp',		
				'&nbsp',		
				'&nbsp',		
				'Total',		
				$gt,			
				$d
			);
			
			$data['delivery_id'] = $delivery_id;
			$data['trx_detail'] = $trx_detail;
			$data['page_title'] = 'Order Process';
			$this->ag_auth->buyview('buy/process',$data);
		}
	}
	
	public function confirm(){
		$delivery_id = $this->input->post('delivery_id');
		$order = $this->get_order($delivery_id);
		$app = $this->get_key_info($order->application_key);
		
		if($this->input->post('cancel')){
			$this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),array('status'=>'cancel'));
			$data['message'] = 'You have canceled your order';
			$data['page_title'] = 'Order Canceled';
			$data['back_url'] = $app->callback_url;
			$this->ag_auth->buyview('message',$data);
		}else if($this->input->post('confirm')){
			$dataset = array(
					'status'=>'confirm',
					'buyerdeliverytime'=>$this->input->post('buyerdeliverytime'),
					'buyerdeliveryzone'=>$this->input->post('buyerdeliveryzone'),
					'shipping_address'=>$this->input->post('shipping_address')
				);
			$this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),$dataset);
			$data['message'] = 'Order confirmed, Thank you & have a nice day';
			$data['page_title'] = 'Order Confirmed';
			$data['back_url'] = $app->callback_url;
			$this->ag_auth->buyview('message',$data);
		}
	}
	
	public function login($api_key,$trx_id){
		
		$this->form_validation->set_rules('username', 'Username', 'required|min_length[6]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->ag_auth->buyview('loginreg');
		}
		else
		{
			$username = set_value('username');
			$password = $this->ag_auth->salt(set_value('password'));
			$field_type  = (valid_email($username)  ? 'email' : 'username');
			
			$user_data = $this->ag_auth->get_user($username, $field_type);
			
			//print_r($user_data);
			
			
			if(array_key_exists('password', $user_data) AND $user_data['password'] === $password)
			{
				
				unset($user_data['password']);
				$user_data['userid'] = $user_data['id'];
				unset($user_data['id']);

				$this->ag_auth->login_user($user_data);
				
				redirect('buy/process/'.$api_key.'/'.$trx_id);
				
				
			} // if($user_data['password'] === $password)
			else
			{
				$data['message'] = "The username and password did not match.";
				$this->ag_auth->buyview('message', $data);
			}
		} // if($this->form_validation->run() == FALSE)		
		$this->ag_auth->login_user($user);
	}
		
	public function register($api_key,$trx_id){
		
		$this->form_validation->set_rules('username', 'Username', 'required|min_length[6]|callback_field_exists');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[password_conf]');
		$this->form_validation->set_rules('email', 'Email Address', 'required|min_length[6]|valid_email|callback_field_exists');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required|trim|xss_clean');
		
		$data['trx_id'] = $trx_id;
		$data['api_key'] = $api_key;
				
				
		if($this->form_validation->run() == FALSE)
		{
			$this->ag_auth->buyview('loginreg',$data);
		}
		else
		{
			$username = set_value('username');
			$password = $this->ag_auth->salt(set_value('password'));
			$group_id = group_id('buyer');
			$fullname = set_value('fullname');
			$email = set_value('email');
			
			$dataset = array(
				'username'=>$username,
				'fullname'=>$fullname,
				'email'=>$email,
				'password'=>$password,
				'group_id'=>$group_id
			);
			
			if($this->db->insert($this->config->item('jayon_members_table'),$dataset) === TRUE)
			{
				$user = array(
					'id'=>$this->db->insert_id(),
					'username'=>$username,
					'group_id'=>$group_id,
					'email'=>$email,
					'fullname'=>$fullname
				);
			
				$this->ag_auth->login_user($user);
				
				redirect('buy/process/'.$api_key.'/'.$trx_id);
				
				
				$data['message'] = "The user account has now been created.";
				$data['page_title'] = 'Registration Success';
				$data['back_url'] = anchor('buy/process/'.$api_key.'/'.$trx_id,'Proceed To Order Process');
				$this->ag_auth->buyview('members/edit', $data);
				
			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$data['message'] = "The user account has not been created.";
				$data['page_title'] = 'Registration Error';
				$data['back_url'] = anchor('admin/members/manage','Back to list');
				$this->ag_auth->buyview('message', $data);
			}

		} // if($this->form_validation->run() == FALSE)
	}
	
	private function get_key_info($key){
		if(!is_null($key)){
			$this->db->where('key',$key);
			$result = $this->db->get($this->config->item('applications_table'));
			if($result->num_rows() > 0){
				$row = $result->row();
				return $row;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	private function get_trx_info($trx){
		if(!is_null($trx)){
			$this->db->where('merchant_trans_id',$trx);
			$result = $this->db->get($this->config->item('incoming_delivery_table'));
			if($result->num_rows() > 0){
				$row = $result->row();
				$row->table = $this->config->item('incoming_delivery_table');
				return $row;
			}else{
				$this->db->where('merchant_trans_id',$trx);
				$result = $this->db->get($this->config->item('assigned_delivery_table'));
				if($result->num_rows() > 0){
					$row = $result->row();
					$row->table = $this->config->item('assigned_delivery_table');
					return $row;
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}
	
	private function get_order($delivery_id){
		$result = $this->db->where('delivery_id',$delivery_id)->get($this->config->item('incoming_delivery_table'));
		if($result->num_rows() > 0){
			$row = $result->row();
			return $row;
		}else{
			return false;
		}
		
	}
	
	private function trxrecord($api_key,$transaction_id,$trx_detail)
	{
		
		$app = $this->get_key_info(trim($api_key));
		
		if($app){
			$in = json_decode($trx_detail);
			
			//print_r($in);
			
			$order['ordertime'] = date('Y-m-d h:i:s',time());
			$order['application_id'] = $app->id;
			$order['application_key'] = $app->key;
			$order['buyer_id'] = $this->session->userdata('userid'); // change this to current buyer after login
			$order['merchant_id'] = $app->merchant_id;
			$order['merchant_trans_id'] = trim($transaction_id);
			
			$order['shipping_address'] = $in->shipping_address;
			$order['phone'] = $in->phone;
			$order['status'] = 'incoming';
			
			$this->db->insert($this->config->item('incoming_delivery_table'),$order);
			$sequence = $this->db->insert_id();
			
			$result = $this->db->affected_rows();
			
			$year_count = str_pad($sequence, 10, '0', STR_PAD_LEFT);
			$merchant_id = str_pad($app->merchant_id, 8, '0', STR_PAD_LEFT);
			$delivery_id = $merchant_id.'-'.date('d-mY',time()).'-'.$year_count;	 	 	 
			
			$this->db->where('id',$sequence)->update($this->config->item('incoming_delivery_table'),array('delivery_id'=>$delivery_id));
			
			if($in->trx_detail){
				$seq = 0;
				foreach($in->trx_detail as $it){
					$item['ordertime'] = $order['ordertime'];
					$item['delivery_id'] = $delivery_id;	 	 	 	 	 	 
					$item['unit_sequence'] = $seq++; 	 	 	 	
					$item['unit_description'] = $it->unit_description;
					$item['unit_price'] = $it->unit_price;
					$item['unit_quantity'] = $it->unit_quantity;
					$item['unit_total']	= $it->unit_total;
					$item['unit_discount'] = $it->unit_discount;

					$rs = $this->db->insert($this->config->item('delivery_details_table'),$item);
				}

			}
			
			return $delivery_id;
		}else{
			return false;
		}
		
	} // public function add() transaction
	
	public function getzone(){
		$q = $this->input->get('term');
		$zones = ajax_find_zones($q,'district');
		print json_encode($zones);
	}
	

}

/* End of file: buy.php */
/* Location: application/controllers/admin/dashboard.php */