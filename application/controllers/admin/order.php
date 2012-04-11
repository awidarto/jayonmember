<?php

class Order extends Application
{
	
	public function __construct()
	{
		parent::__construct();
		$this->ag_auth->restrict('admin'); // restrict this controller to admins only
		$this->table_tpl = array(
			'table_open' => '<table border="0" cellpadding="4" cellspacing="0" class="dataTable">',
			'row_start' => '<tr class="detail_row">',
			'tbody_open' => '<tbody id="detail_body">'
		);
		$this->table->set_template($this->table_tpl);
	    
	}

	public function neworder()
	{
		$merchant_id = $this->session->userdata('userid');
		//print_r($this->session->userdata);
		$this->db->select('key,application_name');
		$this->db->where('merchant_id',$merchant_id);
		$apps = $this->db->get($this->config->item('applications_table'));

		if($apps->num_rows() > 0){
			$app[0] = 'Select application domain';
			foreach ($apps->result() as $r) {
				$app[$r->key] = $r->application_name;
			}
		}else{
			$app[0] = 'Select application domain';
		}

		$select = form_dropdown('app_id',$app,null,'id="app_id"');


	    $data['merchantemail'] = $this->session->userdata('email');
	    $data['merchantname'] = $this->session->userdata('merchantname');
	    $data['merchantfullname'] = $this->session->userdata('fullname');
		$data['merchant_id'] = $merchant_id;
		$data['appselect'] = $select;
		$data['page_title'] = 'New Delivery Orders';
		$this->load->view('auth/pages/neworderform',$data); // Load the view
	}

}

?>