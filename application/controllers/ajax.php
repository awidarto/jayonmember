<?php

class Ajax extends Application
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getzone(){
		$q = $this->input->get('term');
		$zones = ajax_find_zones($q,'district');
		print json_encode($zones);
	}

	public function getcity(){
		$q = $this->input->get('term');
		$zones = ajax_find_cities($q,'city');
		print json_encode($zones);
	}

	public function getcities(){
		$q = $this->input->get('term');
		$zones = ajax_find_cities($q,'city');
		print json_encode($zones);
	}                       

	public function getcourier(){
		$q = $this->input->get('term');
		$zones = ajax_find_courier($q,'fullname','id');
		print json_encode($zones);
	}

	public function getbuyer(){
		$q = $this->input->get('term');
		$merchants = ajax_find_buyer($q,'fullname','id');
		print json_encode($merchants);
	}

	public function getbuyeremail(){
		$q = $this->input->get('term');
		$merchants = ajax_find_buyer_email($q,'fullname','id');
		print json_encode($merchants);
	}

	public function getdevice(){
		$q = $this->input->get('term');
		$zones = ajax_find_device($q,'identifier');
		print json_encode($zones);
	}
	
	public function getdateblock($month = null){
		print getdateblock($month);
	}

	public function neworder(){

		$this->load->library('curl');

		$udescs = $this->input->post('udescs');
        $uqtys = $this->input->post('uqtys');
        $uprices = $this->input->post('uprices');
        $upctdisc = $this->input->post('upctdisc');
        $unomdisc = $this->input->post('unomdisc');
        $utotals = $this->input->post('utotals');

        $trx_detail = array();

        for($i=0;$i < sizeof($uprices);$i++){
        	$line = array(
        			'unit_description'=>$udescs[$i],
					'unit_price'=>$uprices[$i],
					'unit_quantity'=>$uqtys[$i],
					'unit_total'=>$utotals[$i],
					'unit_pct_discount'=>$upctdisc[$i],
					'unit_discount'=>$unomdisc[$i]
        		);
        	$trx_detail[] = $line;
        }


		$merchant_id = $this->input->post('merchant_id');
		$buyer_id = $this->input->post('buyer_id');		

		$trx = array(
			'api_key'=>$this->input->post('api_key'),
			'buyer_name'=>$this->input->post('buyer_name'),
			'recipient_name'=>$this->input->post('recipient_name'),
			'shipping_address'=>$this->input->post('shipping_address'),
			'buyerdeliveryzone'=>$this->input->post('buyerdeliveryzone'),
			'buyerdeliverycity'=>$this->input->post('buyerdeliverycity'),
			'buyerdeliverytime'=>$this->input->post('buyerdeliverytime'),
			'directions'=>$this->input->post('direction'),
			'auto_confirm'=>$this->input->post('auto_confirm'),
			'email'=>$this->input->post('email'),
			'zip' => $this->input->post('zip'),
			'phone' => $this->input->post('phone'),
			'total_price'=>$this->input->post('total_price'),
			'total_discount'=>$this->input->post('total_discount'),
			'total_tax'=>$this->input->post('total_tax'),
			'chargeable_amount'=>$this->input->post('chargeable_amount'),
			'cod_cost' => $this->input->post('cod_cost'), 		
			'currency' => $this->input->post('currency'), 	
			'status'=>$this->input->post('status'),
			'merchant_id'=>$this->input->post('merchant_id'),
			'buyer_id'=>$this->input->post('buyer_id'),
			'trx_detail'=>$trx_detail,
			'width' => $this->input->post('width'),
			'height' => $this->input->post('height'),
			'length' => $this->input->post('length')
		);

		$trx['transaction_id'] = 'TRX_'.$merchant_id.'_'.str_replace(array(' ','.'), '', microtime());

		$api_key = $this->input->post('api_key');
		$trx_id = $trx['transaction_id'];

		$url = $this->config->item('api_url').'post/'.$api_key.'/'.$trx_id;

		$result = $this->curl->simple_post($url,array('transaction_detail'=>json_encode($trx)));
		
		print $result;

	}

	
	public function incomingmonthly(){
		
	}

	public function deliveredmonthly(){
		
	}

	public function rescheduledmonthly(){
		
	}

	public function revokedmonthly(){
		
	}

}

/* End of file: buy.php */
/* Location: application/controllers/admin/dashboard.php */