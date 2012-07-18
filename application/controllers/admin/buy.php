<?php

class Buy extends Application
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($trx_id)
	{
		$page['trx_id'] = $trx_id;

		if(logged_in())
		{
			$page['page_title'] = 'Buy';
			$this->ag_auth->view('buy',$page);
		}
		else
		{
			$this->login('admin/buy/'.$trx_id);
		}
	}
		
	public function register($trx_id){
		
		$this->form_validation->set_rules('username', 'Username', 'required|min_length[6]|callback_field_exists');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[password_conf]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[password_conf]');
				
				
		if($this->form_validation->run() == FALSE)
		{
			$this->ag_auth->view('loginreg',$data);
		}
		else
		{
			$username = set_value('username');
			$password = $this->ag_auth->salt(set_value('password'));
			$group_id = group_id('buyer');
			
			$dataset = array(
				'username'=>$username,
				'password'=>$password,
				'group_id'=>$group_id
			);
			
			if($this->db->insert($this->config->item('jayon_members_table'),$dataset) === TRUE)
			{
				$data['message'] = "The user account has now been created.";
				$data['page_title'] = 'Add Member';
				$data['back_url'] = anchor('admin/members/manage','Back to list');
				$this->ag_auth->view('members/edit', $data);
				
			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$data['message'] = "The user account has not been created.";
				$data['page_title'] = 'Add Member Error';
				$data['back_url'] = anchor('admin/members/manage','Back to list');
				$this->ag_auth->view('message', $data);
			}

		} // if($this->form_validation->run() == FALSE)
	}

}

/* End of file: dashboard.php */
/* Location: application/controllers/admin/dashboard.php */