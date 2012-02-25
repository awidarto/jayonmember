<?php

class Merchant extends Application
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
		$this->breadcrumb->add_crumb('Merchant','admin/merchant');
		
	}

	public function index(){
		$this->load->library('table');		
	
		$id = $this->session->userdata('userid');

		$user = $this->get_user($id);
		
		foreach($user as $key=>$val){
			$this->table->add_row($key,$val); // Adding row to table
		}
		
		$page['page_title'] = 'Merchant Info';
		$this->ag_auth->view('merchant/info',$page);
	}

	public function request()
	{

		$id = $this->session->userdata('userid');

		$this->breadcrumb->add_crumb('Merchant Request','admin/merchant/request');

		$this->form_validation->set_rules('merchantname', 'Merchant Name', 'trim|xss_clean');
		$this->form_validation->set_rules('bank', 'Bank', 'trim|xss_clean');
		$this->form_validation->set_rules('account_name', 'Account Name', 'trim|xss_clean');
		$this->form_validation->set_rules('account_number', 'Account Number', 'trim|xss_clean');

		$this->form_validation->set_rules('same_as_personal_address', 'Same As Personal Address', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_email', 'Official Email', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_street', 'Street', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_district', 'District', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_city', 'City', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_province', 'Province', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_country', 'Country', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_zip', 'ZIP', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_phone', 'Phone Number', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_mobile', 'Mobile Number', 'trim|xss_clean');

		$user = $this->get_user($id);		
		$page['user'] = $user;

		//print_r($user);

		if($this->form_validation->run() == FALSE)
		{			
			$page['page_title'] = 'Merchant Request';
			$this->ag_auth->view('merchant/request',$page); // Load the view
		}
		else
		{

			$dataset['merchantname'] = set_value('merchantname');
			$dataset['bank'] = set_value('bank');
			$dataset['account_name'] = set_value('account_name');
			$dataset['account_number'] = set_value('account_number');

			$dataset['same_as_personal_address'] = set_value('same_as_personal_address');
			$dataset['mc_email'] = set_value('mc_email');
			$dataset['mc_street'] = set_value('mc_street');
			$dataset['mc_district'] = set_value('mc_district');
			$dataset['mc_province'] = set_value('mc_province');
			$dataset['mc_city'] = set_value('mc_city');
			$dataset['mc_country'] = set_value('mc_country');
			$dataset['mc_zip'] = set_value('mc_zip');
			$dataset['mc_phone'] = set_value('mc_phone');
			$dataset['mc_mobile'] = set_value('mc_mobile');

			$dataset['group_id'] = user_group_id('merchant');

			$this->session->set_userdata(array('group_id'=>user_group_id('merchant')));

			if($this->db->where('id',$id)->update($this->config->item('jayon_members_table'),$dataset) === TRUE)
			//if($this->update_user($id,$dataset) === TRUE)
			{
				$this->oi->add_success('Successfully register merchant');
				redirect('admin/merchant');
			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$this->oi->add_error('Failed to register merchant');
				redirect('admin/merchant/request');
			}

		} // if($this->form_validation->run() == FALSE)
	}

	public function edit()
	{
		$id = $this->session->userdata('userid');

		$this->breadcrumb->add_crumb('Edit Merchant Info','admin/merchant/edit');

		$this->form_validation->set_rules('merchantname', 'Merchant Name', 'trim|xss_clean');
		$this->form_validation->set_rules('bank', 'Bank', 'trim|xss_clean');
		$this->form_validation->set_rules('account_name', 'Account Name', 'trim|xss_clean');
		$this->form_validation->set_rules('account_number', 'Account Number', 'trim|xss_clean');

		$this->form_validation->set_rules('same_as_personal_address', 'Same As Personal Address', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_email', 'Official Email', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_street', 'Street', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_district', 'District', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_city', 'City', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_province', 'Province', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_country', 'Country', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_zip', 'ZIP', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_phone', 'Phone Number', 'trim|xss_clean');
		$this->form_validation->set_rules('mc_mobile', 'Mobile Number', 'trim|xss_clean');

		$user = $this->get_user($id);		
		$page['user'] = $user;

		//print_r($user);

		if($this->form_validation->run() == FALSE)
		{			
			$page['page_title'] = 'Edit Merchant Info';
			$this->ag_auth->view('merchant/request',$page); // Load the view
		}
		else
		{

			$dataset['merchantname'] = set_value('merchantname');
			$dataset['bank'] = set_value('bank');
			$dataset['account_name'] = set_value('account_name');
			$dataset['account_number'] = set_value('account_number');

			$dataset['same_as_personal_address'] = set_value('same_as_personal_address');
			$dataset['mc_email'] = set_value('mc_email');
			$dataset['mc_street'] = set_value('mc_street');
			$dataset['mc_district'] = set_value('mc_district');
			$dataset['mc_province'] = set_value('mc_province');
			$dataset['mc_city'] = set_value('mc_city');
			$dataset['mc_country'] = set_value('mc_country');
			$dataset['mc_zip'] = set_value('mc_zip');
			$dataset['mc_phone'] = set_value('mc_phone');
			$dataset['mc_mobile'] = set_value('mc_mobile');

			if($this->db->where('id',$id)->update($this->config->item('jayon_members_table'),$dataset) === TRUE)
			//if($this->update_user($id,$dataset) === TRUE)
			{
				$this->oi->add_success('Successfully updating merchant info');
				redirect('admin/merchant');
			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$this->oi->add_error('Failed to update merchant info');
				redirect('admin/merchant/edit');
			}

		} // if($this->form_validation->run() == FALSE)
	}


	public function __request(){

		$this->breadcrumb->add_crumb('Merchant Request','admin/merchant/request');
		
		$page['page_title'] = 'Merchant Request';
		$this->ag_auth->view('merchant/request',$page); // Load the view
	}

	private function get_user($id){
		$result = $this->db->where('id', $id)->get($this->config->item('jayon_members_table'));
		if($result->num_rows() > 0){
			return $result->row_array();
		}else{
			return false;
		}
	}

}

?>