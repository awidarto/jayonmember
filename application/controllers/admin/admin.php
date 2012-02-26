<?php

class Admin extends Application
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		if(logged_in())
		{
			$this->breadcrumb->add_crumb('Home','admin/dashboard');
			$page['page_title'] = 'Dashboard';
			$this->ag_auth->view('dashboard',$page);
		}
		else
		{
			$this->login();
		}
	}
	
	
	public function register(){
		$this->breadcrumb->add_crumb('Home',base_url());
		$this->breadcrumb->add_crumb('Register','register');
		
		$this->form_validation->set_rules('username', 'Username', 'required|min_length[6]|callback_field_exists');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[password_conf]');
		$this->form_validation->set_rules('password_conf', 'Password Confirmation', 'required|min_length[6]|matches[password]');
		$this->form_validation->set_rules('email', 'Email Address', 'required|min_length[6]|valid_email|callback_field_exists');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required|trim|xss_clean');	
		$this->form_validation->set_rules('merchantname', 'Merchant Name', 'trim|xss_clean');	
		$this->form_validation->set_rules('merchant_request', 'Request Merchant Account', 'trim|xss_clean');	
		$this->form_validation->set_rules('bank', 'Bank', 'trim|xss_clean');	
		$this->form_validation->set_rules('account_name', 'Account Name', 'trim|xss_clean');	
		$this->form_validation->set_rules('account_number', 'Account Number', 'trim|xss_clean');	
		$this->form_validation->set_rules('street', 'Street', 'required|trim|xss_clean');	
		$this->form_validation->set_rules('district', 'District', 'required|trim|xss_clean');  
		$this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');	
		$this->form_validation->set_rules('country', 'Country', 'required|trim|xss_clean');	
		$this->form_validation->set_rules('zip', 'ZIP', 'required|trim|xss_clean');		
		$this->form_validation->set_rules('phone', 'Phone Number', 'required|trim|xss_clean');   
		$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|trim|xss_clean');
		$this->form_validation->set_rules('group_id', 'Group', 'trim');
				
		if($this->form_validation->run() == FALSE)
		{
			
			$data['groups'] = array(
				group_id('merchant')=>group_desc('merchant'),
				group_id('buyer')=>group_desc('buyer')
			);
			$data['page_title'] = 'Register';
			$this->ag_auth->view('register',$data);
		}
		else
		{
			$username = set_value('username');
			$password = $this->ag_auth->salt(set_value('password'));
			$fullname = set_value('fullname');
			$merchantname = set_value('merchantname');
			$merchant_request = set_value('merchant_request');
			$bank = set_value('bank');
			$account_number = set_value('account_number');
			$account_name = set_value('account_name');
			$street = set_value('street'); 
			$district = set_value('district');
			$province = set_value('province');
			$city = set_value('city');
			$country = set_value('country');
			$zip = set_value('zip');
			$phone= set_value('phone');
			$mobile= set_value('mobile'); 
			$email = set_value('email');
			$group_id = set_value('group_id');
			
			$dataset = array(
				'username'=>$username,
				'password'=>$password,
				'fullname'=>$fullname,
				'merchantname'=>$merchantname,
				'merchant_request'=>$merchant_request,
				'bank'=>$bank,
				'account_number'=>$account_number,
				'account_name'=>$account_name,
				'street'=>$street,
				'district'=>$district,
				'province'=>$province,
				'city'=>$city,
				'country'=>$country, 
				'zip'=>$zip,
				'phone'=>$phone,
				'mobile'=>$mobile, 
				'email'=>$email,
				'group_id'=>$group_id
			);
			
			if($this->db->insert($this->config->item('jayon_members_table'),$dataset) === TRUE)
			{
				$this->breadcrumb->append_crumb('Success','');
				
				$data['message'] = "The user account has now been created.";
				$data['page_title'] = 'Add Member';
				$data['back_url'] = anchor('admin/members/manage','Back to list');
				$this->ag_auth->view('message', $data);
				
			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$this->breadcrumb->append_crumb('Failed','');
				
				$data['message'] = "The user account has not been created.";
				$data['page_title'] = 'Add Member Error';
				$data['back_url'] = anchor('admin/members/manage','Back to list');
				$this->ag_auth->view('message', $data);
			}

		} // if($this->form_validation->run() == FALSE)
		
	}

	public function resetpass(){

		$this->form_validation->set_rules('email', 'Email Address', 'required|min_length[6]|valid_email');

		if($this->form_validation->run() == FALSE)
		{
			$this->ag_auth->view('resetpass');
		}
		else
		{
			$email = set_value('email');
			if($buyer = $this->check_email($email)){
				$password = random_string('alnum', 8);
				$dataset['password'] = $this->ag_auth->salt($password);
				$this->oi->add_success('New password has been sent to your email.');
			}else{
				$this->oi->add_success('Your email can not be found, please consider registering as new member.');
			}

			redirect('resetpass');
		}

	}

	private function check_email($email){
		$em = $this->db->where('email',$email)->get($this->config->item('jayon_members_table'));
		if($em->num_rows() > 0){
			return $em->row_array();
		}else{
			return false;
		}
	}

}

/* End of file: dashboard.php */
/* Location: application/controllers/admin/dashboard.php */