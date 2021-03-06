<?php

class Apps extends Application
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

	public function ajaxmanage()
	{
		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');

		$sort_col = $this->input->post('iSortCol_0');
		$sort_dir = $this->input->post('sSortDir_0');

		$columns = array(
			'merchant_id',
			'application_name',
			'domain',
			'key',
			'callback_url',
			'application_description'
			);

		// get total count result
		$count_all = $this->db->count_all($this->config->item('applications_table'));

		$count_display_all = $this->db->count_all_results($this->config->item('applications_table'));

		$this->db->where('merchant_id',$this->session->userdata('userid'));

		$data = $this->db->limit($limit_count, $limit_offset)->order_by($columns[$sort_col],$sort_dir)->get($this->config->item('applications_table'));

		//print $this->db->last_query();

		$result = $data->result_array();

		$aadata = array();

		foreach($result as $value => $key)
		{
			$delete = anchor("admin/apps/delete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/apps/edit/".$key['id']."/", "Edit"); // Build actions links
			$add = anchor("admin/apps/add/".$key['merchant_id']."/", "Add"); // Build actions links
			$aadata[] = array(
				$this->get_merchant($key['merchant_id']),
				$key['application_name'],
				$key['domain'],
				$key['key'],
				$key['callback_url'],
				$key['application_description'],
				$edit.' '.$delete
			); // Adding row to table
		}

		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);

		print json_encode($result); // Load the view
	}

	public function manage()
	{
		$this->breadcrumb->add_crumb('Application Keys','admin/apps/manage');

		$this->table->set_heading(
			'Merchant',
			'Application Name',
			'Domain',
			'Key',
			'Callback URL',
			'Description',
			'Actions'
			); // Setting headings for the table

		$page['merchant_id'] = $this->session->userdata('userid');
		$page['sortdisable'] = '6';
		$page['add_button'] = array('link'=>'admin/apps/add','label'=>'Add Application');
		$page['ajaxurl'] = 'admin/apps/ajaxmanage';
		$page['page_title'] = 'Application Keys';
		$this->ag_auth->view('ajaxlistview',$page); // Load the view
	}

	public function merchantmanage($id)
	{

		$data = $this->db->where('merchant_id',$id)->get($this->config->item('applications_table'));
		$result = $data->result_array();
		$this->table->set_heading(
			'Merchant',
			'Application Name',
			'Domain',
			'Key',
			'Callback URL',
			'Description',
			'Actions'
			); // Setting headings for the table

		foreach($result as $value => $key)
		{
			$delete = anchor("admin/members/merchantdelete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/members/merchantedit/".$key['id']."/", "Edit"); // Build actions links
			$this->table->add_row(
				$this->get_merchant($key['merchant_id']),
				$key['application_name'],
				$key['domain'],
				$key['key'],
				$key['callback_url'],
				$key['application_description'],
				$edit.' '.$delete
			); // Adding row to table
		}
		$page['merchant_id'] = $id;
		$page['page_title'] = 'Application Keys - '.$id.' - '.$this->get_merchant($id);
		$this->ag_auth->view('apps/merchantmanage',$page); // Load the view
	}

	public function delete($id)
	{
		$this->db->where('id', $id)->delete($this->config->item('applications_table'));
		$page['page_title'] = 'Delete Application';
		$this->ag_auth->view('apps/delete_success');
	}

	public function get_merchant($id){
		$result = $this->db->select('merchantname')->where('id',$id)->get($this->config->item('jayon_members_table'));
		$row = $result->row();
		return ($row->merchantname === '')?'anonymous merchant':$row->merchantname;
	}

	public function get_app($id){
		$result = $this->db->where('id', $id)->get($this->config->item('applications_table'));
		if($result->num_rows() > 0){
			return $result->row_array();
		}else{
			return false;
		}
	}

	public function add($merchant_id = null)
	{
		$merchant_id = is_null($merchant_id)?$this->session->userdata('userid'):$merchant_id;

		$this->breadcrumb->add_crumb('Application Keys','admin/apps/manage');
		$this->breadcrumb->add_crumb('Add Application Keys','admin/apps/add');

		$this->form_validation->set_rules('owner_id','Owner ID','trim');
		$this->form_validation->set_rules('merchant_id','Merchant ID','trim');
		$this->form_validation->set_rules('domain','Application Domain','required|trim|xss_clean');
		$this->form_validation->set_rules('application_name','Application Name','required|trim|xss_clean');
		$this->form_validation->set_rules('callback_url','Callback URL','trim|xss_clean');
		$this->form_validation->set_rules('fetch_detail_url','Fetch Detail URL','trim|xss_clean');
		$this->form_validation->set_rules('fetch_method','Fetch Method','trim|xss_clean');
		$this->form_validation->set_rules('application_description','Application Description','trim|xss_clean');
		$this->form_validation->set_rules('logo_url','Logo URL','trim|xss_clean');
		$this->form_validation->set_rules('signature','Signature','trim|xss_clean');

		$this->form_validation->set_rules('reply_to', 'Reply To', 'trim|xss_clean');
		$this->form_validation->set_rules('cc_to', 'CC', 'trim|xss_clean');

		$this->form_validation->set_rules('same_as_personal_address', 'Same As Personal Address', 'trim|xss_clean');
		$this->form_validation->set_rules('contact_person', 'Contact Person', 'trim|xss_clean');
		$this->form_validation->set_rules('street', 'Street', 'trim|xss_clean');
		$this->form_validation->set_rules('district', 'District', 'trim|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|xss_clean');
		$this->form_validation->set_rules('province', 'Province', 'trim|xss_clean');
		$this->form_validation->set_rules('country', 'Country', 'trim|xss_clean');
		$this->form_validation->set_rules('zip', 'ZIP', 'trim|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone Number', 'trim|xss_clean');
		$this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|xss_clean');

		if($this->form_validation->run() == FALSE)
		{
			$data['merchant_id'] = $merchant_id;
			$data['merchant_name'] = $this->get_merchant($merchant_id);
			//$data['page_title'] = 'Application Keys';
			$data['page_title'] = 'Add Application Keys';

			$data['act_url'] = 'admin/apps/add/'.$merchant_id;
			$data['back_url'] = anchor('admin/apps/manage','Back to list');
			$this->ag_auth->view('apps/add',$data);
		}
		else
		{
			//$dataset['owner_id'] = set_value('owner_id');
			$dataset['merchant_id'] = set_value('merchant_id');
			$dataset['domain'] = set_value('domain');
			$dataset['application_name'] = set_value('application_name');
			$dataset['key'] = random_string('sha1',40);
			$dataset['callback_url'] = set_value('callback_url');
			$dataset['fetch_detail_url'] = set_value('fetch_detail_url');
			$dataset['fetch_method'] = set_value('fetch_method');
			$dataset['application_description'] = set_value('application_description');
			$dataset['logo_url'] = set_value('logo_url');
			$dataset['signature'] = set_value('signature');

			$dataset['reply_to'] = set_value('reply_to');
			$dataset['cc_to'] = set_value('cc_to');

			$dataset['same_as_personal_address'] = set_value('same_as_personal_address');
			$dataset['contact_person'] = set_value('contact_person');
			$dataset['street'] = set_value('street');
			$dataset['district'] = set_value('district');
			$dataset['province'] = set_value('province');
			$dataset['city'] = set_value('city');
			$dataset['country'] = set_value('country');
			$dataset['zip'] = set_value('zip');
			$dataset['phone'] = set_value('phone');
			$dataset['mobile'] = set_value('mobile');

			if($this->db->insert($this->config->item('applications_table'),$dataset) === TRUE)
			{

				$this->oi->add_success('Application saved');
				redirect('admin/apps/manage');

			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$this->oi->add_error('Failed to save application');
				redirect('admin/apps/add');
			}

		} // if($this->form_validation->run() == FALSE)

	} // public function register()

	public function edit($id)
	{
		$this->breadcrumb->add_crumb('Application Keys','admin/apps/manage');
		$this->breadcrumb->add_crumb('Edit Application Keys','admin/apps/edit');

		$this->form_validation->set_rules('domain','Application Domain','required|trim|xss_clean');
		$this->form_validation->set_rules('application_name','Application Name','requiredtrim|xss_clean');
		$this->form_validation->set_rules('callback_url','Callback URL','trim|xss_clean');
		$this->form_validation->set_rules('fetch_detail_url','Fetch Detail URL','trim|xss_clean');
		$this->form_validation->set_rules('fetch_method','Fetch Method','trim|xss_clean');
		$this->form_validation->set_rules('application_description','Application Description','trim|xss_clean');
		$this->form_validation->set_rules('logo_url','Logo URL','trim|xss_clean');
		$this->form_validation->set_rules('signature','Signature','trim|xss_clean');

		$this->form_validation->set_rules('reply_to', 'Reply To', 'trim|xss_clean');
		$this->form_validation->set_rules('cc_to', 'CC', 'trim|xss_clean');

		$this->form_validation->set_rules('same_as_personal_address', 'Same As Personal Address', 'trim|xss_clean');
		$this->form_validation->set_rules('contact_person', 'Contact Person', 'trim|xss_clean');
		$this->form_validation->set_rules('street', 'Street', 'trim|xss_clean');
		$this->form_validation->set_rules('district', 'District', 'trim|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|xss_clean');
		$this->form_validation->set_rules('province', 'Province', 'trim|xss_clean');
		$this->form_validation->set_rules('country', 'Country', 'trim|xss_clean');
		$this->form_validation->set_rules('zip', 'ZIP', 'trim|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone Number', 'trim|xss_clean');
		$this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|xss_clean');

		//$this->form_validation->set_rules('notify_on_new_buyer','Send notification on new member', 'trim|xss_clean');
		//$this->form_validation->set_rules('notify_on_new_member','Send notification on new member', 'trim|xss_clean');
		$this->form_validation->set_rules('notify_on_new_order','Send notification on new order', 'trim|xss_clean');
		//$this->form_validation->set_rules('notify_on_reschedule,','Send notification on rescheduled order', 'trim|xss_clean');
		//$this->form_validation->set_rules('notify_on_revoked','Send notification on revoked order', 'trim|xss_clean');
		//$this->form_validation->set_rules('notify_on_noshow','Send notification on no show', 'trim|xss_clean');

		$user = $this->get_app($id);
		$data['user'] = $user;
		$merchant_id = $user['merchant_id'];

		if($this->form_validation->run() == FALSE)
		{
			$data['merchant_id'] = $merchant_id;
			$data['merchant_name'] = $this->get_merchant($merchant_id);
			//$data['page_title'] = 'Application Keys';
			$data['page_title'] = 'Edit Application Keys <br />'.$user['application_name'].' - '.$this->get_merchant($merchant_id);
			if(in_array('members',$this->uri->segment_array())){
				$data['act_url'] = 'admin/members/merchantedit/'.$id;
				$data['back_url'] = anchor('admin/members/merchantmanage/'.$merchant_id,'Back to list');
			}else{
				$data['act_url'] = 'admin/apps/edit/'.$id;
				$data['back_url'] = anchor('admin/apps/manage','Back to list');
			}
			$this->ag_auth->view('apps/edit',$data);
		}
		else
		{
			//$dataset['owner_id'] = set_value('owner_id');
			//$dataset['merchant_id'] = set_value('merchant_id');
			$dataset['domain'] = set_value('domain');
			$dataset['application_name'] = set_value('application_name');
			$dataset['callback_url'] = set_value('callback_url');
			$dataset['fetch_detail_url'] = set_value('fetch_detail_url');
			$dataset['fetch_method'] = set_value('fetch_method');
			$dataset['application_description'] = set_value('application_description');
			$dataset['logo_url'] = set_value('logo_url');
			$dataset['signature'] = set_value('signature');

			$dataset['reply_to'] = set_value('reply_to');
			$dataset['cc_to'] = set_value('cc_to');

			$dataset['same_as_personal_address'] = set_value('same_as_personal_address');
			$dataset['contact_person'] = set_value('contact_person');
			$dataset['street'] = set_value('street');
			$dataset['district'] = set_value('district');
			$dataset['province'] = set_value('province');
			$dataset['city'] = set_value('city');
			$dataset['country'] = set_value('country');
			$dataset['zip'] = set_value('zip');
			$dataset['phone'] = set_value('phone');
			$dataset['mobile'] = set_value('mobile');

			//$dataset['notify_on_new_buyer'] = set_value('notify_on_new_buyer');
			$dataset['notify_on_new_order'] = set_value('notify_on_new_order');
			//$dataset['notify_on_reschedule'] = set_value('notify_on_reschedule');
			//$dataset['notify_on_revoked'] = set_value('notify_on_revoked');
			//$dataset['notify_on_noshow'] = set_value('notify_on_noshow');


			if($this->db->where('id',$id)->update($this->config->item('applications_table'),$dataset) === TRUE)
			{
				$this->oi->add_success('Application updated & saved');
				redirect('admin/apps/manage');

			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$this->oi->add_error('Failed to update application');
				redirect('admin/apps/edit');
			}

		}

	}

}

?>