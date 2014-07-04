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

			$year = date('Y',time());
			$month = date('m',time());

			$page['period'] = ' - '.date('M Y',time());

			//print_r($this->session->userdata);

			$page['page_title'] = 'Dashboard';
			$page['ajaxurl'] = 'ajaxpos/ajaxlog';
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
            if($_SERVER['HTTP_HOST'] == 'localhost'){

                $data['groups'] = array(
                    group_id('merchant')=>group_desc('merchant'),
                    group_id('buyer')=>group_desc('buyer')
                );
                $data['page_title'] = 'Register';
                $this->ag_auth->view('register',$data);

            }else{
                $this->session->set_flashdata('registerError', validation_errors('<div class="error">', '</div>') );
                redirect('http://www.jayonexpress.com/register','location');
            }

		}
		else
		{
			$epassword = set_value('password'); // for email notification

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

			//$group_id = ($group_id == 0)?group_id('buyer'):$group_id;

            // by default all web registration become a merchant
            $group_id = group_id('pendingmerchant');

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
				'group_id'=>$group_id,
                'created'=> date('Y-m-d h:i:s',time())
			);

			if($this->db->insert($this->config->item('jayon_members_table'),$dataset) === TRUE)
			{
				$edata['email'] = $email;
				$edata['fullname'] = $fullname;
				$edata['password'] = $epassword;
				send_notification('New Member Registration - Jayon Express COD Service',$email,null,'new_pending_merchant',$edata,null);

				redirect($this->config->item('auth_register_success').'?reg=success');

				/*
				$this->breadcrumb->append_crumb('Success','');

				$data['message'] = "The user account has now been created.";
				$data['page_title'] = 'Add Member';
				$data['back_url'] = anchor('admin/members/manage','Back to list');
				$this->ag_auth->view('message', $data);
				*/
			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				redirect($this->config->item('auth_register_fail').'?reg=err');
				/*
				$this->breadcrumb->append_crumb('Failed','');

				$data['message'] = "The user account has not been created.";
				$data['page_title'] = 'Add Member Error';
				$data['back_url'] = anchor('admin/members/manage','Back to list');
				$this->ag_auth->view('message', $data);
				*/
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
				$this->db->where('email',$email)->update($this->config->item('jayon_members_table'),$dataset);

				$edata['fullname'] = $buyer->fullname;
				$edata['password'] = $password;
				$subject = 'Password reset request at Jayon Express.';
				send_notification($subject,$email,null,'resetpassd',$edata);
				$this->oi->add_success('New password has been sent to your email.');
			}else{
				$this->oi->add_error('Your email can not be found, please consider registering as new member.');
			}

			redirect('resetpass');
		}

	}

	public function changepass()
	{
		$this->form_validation->set_rules('password', 'Password', 'min_length[6]|matches[password_conf]');
		$this->form_validation->set_rules('password_conf', 'Password Confirmation', 'min_length[6]|matches[password]');

		$id = $this->session->userdata('userid');
		$user = $this->get_user($id);
		$data['user'] = $user;

		if($this->form_validation->run() == FALSE)
		{
			$data['groups'] = $this->get_group();
			$data['page_title'] = 'Change Password';
			$this->ag_auth->view('editpass',$data);
		}
		else
		{
			$result = TRUE;

			$dataset['password'] = $this->ag_auth->salt(set_value('password'));

			if( $result = $this->update_user($id,$dataset))
			{
				$this->oi->add_success('Your password is now updated');
				redirect('admin/dashboard');

			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$this->oi->add_error('Your password can not be changed.');
				redirect('admin/dashboard');
			}

		} // if($this->form_validation->run() == FALSE)

	} // public function register()


	private function check_email($email){
		$em = $this->db->where('email',$email)->get($this->config->item('jayon_members_table'));
		if($em->num_rows() > 0){
			return $em->row_array();
		}else{
			return false;
		}
	}

	private function get_user($id){
		$result = $this->db->where('id', $id)->get($this->ag_auth->config['auth_user_table']);
		if($result->num_rows() > 0){
			return $result->row_array();
		}else{
			return false;
		}
	}

	private function get_group(){
		$this->db->select('id,description');
		$result = $this->db->get($this->ag_auth->config['auth_group_table']);
		foreach($result->result_array() as $row){
			$res[$row['id']] = $row['description'];
		}
		return $res;
	}

	private function update_user($id,$data){
		$result = $this->db->where('id', $id)->update($this->ag_auth->config['auth_user_table'],$data);
		return $result;
	}


}

/* End of file: dashboard.php */
/* Location: application/controllers/admin/dashboard.php */