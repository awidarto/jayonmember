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
		
	}

	public function request(){
		
		$page['page_title'] = 'Merchant Request';
		$this->ag_auth->view('merchant/request',$page); // Load the view
	}

}

?>