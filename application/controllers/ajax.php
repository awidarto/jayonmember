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
		$merchant_id = $this->session->userdata('userid');
		$merchants = ajax_find_buyer($q,'fullname','id',$merchant_id);
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

	public function getzoneselect(){
		$city = $this->input->post('city');

		$this->db->where(array('city'=>$city));
		$this->db->where(array('is_on'=>1));
		$zones = $this->db->get($this->config->item('jayon_zones_table'));

		if($zones->num_rows() > 0){
			$zone[0] = 'Select delivery zone';
			foreach ($zones->result() as $r) {
				$zone[$r->district] = $r->district;
			}
		}else{
			$zone[0] = 'Select delivery zone';
		}

		$select = form_dropdown('buyerdeliveryzone',$zone,null,'id="buyerdeliveryzone"');

		print json_encode(array('result'=>'ok','data'=>$select));
	}

	public function getweightdata(){
		$app_key = $this->input->post('app_key');

		if($app_key == '0'){
			$dctable = false;
			$app_id = 0;
		}else{
			$app_id = get_app_id_from_key(trim($app_key));
			/*
			$this->db->select('id');
			$this->db->where('key',$app_key);
			$result = $this->db->get($CI->config->item('applications_table'));

			print $this->db->last_query();

			$app_id = $result->row()->id;
			*/
			$dctable = get_delivery_charge_table($app_id);
		}

		if($dctable == true){
			$weight[0] = 'Select weight range';
			foreach ($dctable as $r) {
				$weight[$r->total] = $r->kg_from.' kg - '.$r->kg_to.' kg';
				$this->table->add_row($r->kg_from.' kg - '.$r->kg_to.' kg', 'IDR '.number_format($r->total,2,',','.'));
			}
		}else{
			$dctable = get_delivery_charge_table(0);
			$weight[0] = 'Select weight range';
			foreach ($dctable as $r) {
				$weight[$r->total] = $r->kg_from.' kg - '.$r->kg_to.' kg';
				$this->table->add_row($r->kg_from.' kg - '.$r->kg_to.' kg', 'IDR '.number_format($r->total,2,',','.'));
			}
		}

		$weightselect = form_dropdown('package_weight',$weight,null,'id="package_weight"');
		$weighttable = $this->table->generate();

		print json_encode(array('app_id'=>$app_id,'result'=>'ok','data'=>array('selector'=>$weightselect,'table'=>$weighttable)));
	}

	public function getpickupdata(){
		$app_key = $this->input->post('app_key');
		if($app_key == '0'){
			$dctable = false;
			$app_id = 0;
		}else{
			$app_id = get_app_id_from_key($app_key);
			$dctable = get_pickup_charge_table($app_id);
		}

		if($dctable == true){
			$weight[0] = 'Select weight range';
			foreach ($dctable as $r) {
				$weight[$r->total] = $r->kg_from.' kg - '.$r->kg_to.' kg';
				$this->table->add_row($r->kg_from.' kg - '.$r->kg_to.' kg', 'IDR '.number_format($r->total,2,',','.'));
			}
		}else{
			$dctable = get_pickup_charge_table(0);
			$weight[0] = 'Select weight range';
			foreach ($dctable as $r) {
				$weight[$r->total] = $r->kg_from.' kg - '.$r->kg_to.' kg';
				$this->table->add_row($r->kg_from.' kg - '.$r->kg_to.' kg', 'IDR '.number_format($r->total,2,',','.'));
			}
		}

		$weightselect = form_dropdown('package_weight',$weight,null,'id="package_weight"');
		$weighttable = $this->table->generate();

		print json_encode(array('result'=>'ok','data'=>array('app_id'=>$app_id,'selector'=>$weightselect,'table'=>$weighttable)));
	}

	public function getcoddata(){
		$app_key = $this->input->post('app_key');
		if($app_key == '0'){
			$dctable = false;
			$app_id = 0;
		}else{
			$app_id = get_app_id_from_key($app_key);
			$dctable = get_cod_table($app_id);
		}

		if($dctable == true){
			foreach ($dctable as $r) {
				$this->table->add_row('IDR '.number_format($r->from_price,2,',','.').' - IDR '.number_format($r->to_price,2,',','.'), 'IDR '.number_format($r->surcharge,2,',','.'));
			}
		}else{
			$dctable = get_cod_table(0);
			foreach ($dctable as $r) {
				$this->table->add_row('IDR '.number_format($r->from_price,2,',','.').' - IDR '.number_format($r->to_price,2,',','.'), 'IDR '.number_format($r->surcharge,2,',','.'));
			}
		}

		$codhash = json_encode($dctable);
		$codselect = $dctable;
		$codtable = $this->table->generate();

		print json_encode(array('app_id'=>$app_id,'result'=>'ok','data'=>array('selector'=>$codselect,'codhash'=>$codhash,'table'=>$codtable)));
	}

	public function saveweight(){
		$delivery_id = $this->input->post('delivery_id');
        $delivery_cost = $this->input->post('weight_tariff');

			$order = $this->db->where('delivery_id',$delivery_id)->get($this->config->item('incoming_delivery_table'));
			$order = $order->row_array();

			$total = str_replace(array(',','.'), '', $order['total_price']);
			$dsc = str_replace(array(',','.'), '', $order['total_discount']);
			$tax = str_replace(array(',','.'), '',$order['total_tax']);

			$dc = str_replace(array(',','.'), '',$delivery_cost);
			$cod = str_replace(array(',','.'), '',$order['cod_cost']);

			$total = (int)$total;
			$dsc = (int)$dsc;
			$tax = (int)$tax;
			$dc = (int)$dc;
			$cod = (int)$cod;

			$chg = ($total - $dsc) + $tax + $dc + $cod;

			$newdata = array(
				'delivery_cost'=>$delivery_cost,
				'weight'=>$delivery_cost
			);

		$this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),array('delivery_cost'=>$delivery_cost,'weight'=>$delivery_cost));

		if($this->db->affected_rows() > 0){

			print json_encode(array('status'=>'OK','delivery_cost'=>number_format($delivery_cost,2,',','.'),'weight_range'=>get_weight_range($delivery_cost),'total_charges'=>number_format($chg,2,',','.')));
		}else{
			print json_encode(array('status'=>'ERR','delivery_cost'=>0));
		}

	}

	public function toggle()
	{
		$field = $this->input->post('field');
		$id = $this->input->post('id');
		$setsw = $this->input->post('switchto');
		$toggle = ($setsw == 'On')?1:0;

		$dataset[$field] = $toggle;

		if($this->db->where('delivery_id',$id)->update($this->config->item('incoming_delivery_table'),$dataset) == TRUE){
			print json_encode(array('result'=>'ok','state'=>$setsw));
		}else{
			print json_encode(array('result'=>'failed'));
		}
	}

	public function savedeliverytype(){
		$delivery_id = $this->input->post('delivery_id');
        $delivery_type = $this->input->post('delivery_type');

			$order = $this->db->where('delivery_id',$delivery_id)->get($this->config->item('incoming_delivery_table'));
			$order = $order->row_array();

			$total = str_replace(array(',','.'), '', $order['total_price']);
			$dsc = str_replace(array(',','.'), '', $order['total_discount']);
			$tax = str_replace(array(',','.'), '',$order['total_tax']);

			$dc = str_replace(array(',','.'), '',$order['delivery_cost']);
			$cod = str_replace(array(',','.'), '',$order['cod_cost']);

			$total = (int)$total;
			$dsc = (int)$dsc;
			$tax = (int)$tax;
			$dc = (int)$dc;

			if($delivery_type == 'COD'){
				$cod = get_cod_tariff(($total - $dsc) + $tax);
			}else{
				$cod = 0;
			}

			$chg = ($total - $dsc) + $tax + $dc + $cod;

			$newdata = array(
				'cod_cost'=>$cod,
				'delivery_type'=>$delivery_type
			);


		$this->db->where('delivery_id',$delivery_id)->update($this->config->item('incoming_delivery_table'),$newdata);

		if($this->db->affected_rows() > 0){
			print json_encode(array('status'=>'OK','delivery_type'=>$delivery_type,'cod_cost'=>number_format($cod,2,',','.'),'total_charges'=>number_format($chg,2,',','.')));
		}else{
			print json_encode(array('status'=>'ERR','delivery_type'=>0));
		}

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
			'buyerdeliveryslot'=>$this->input->post('buyerdeliveryslot'),
			'directions'=>$this->input->post('direction'),
			'auto_confirm'=>$this->input->post('auto_confirm'),
			'email'=>$this->input->post('email'),
			'zip' => $this->input->post('zip'),
			'phone' => $this->input->post('phone'),
			'mobile1' => $this->input->post('mobile1'),
			'mobile2' => $this->input->post('mobile2'),
			'total_price'=>$this->input->post('total_price'),
			'total_discount'=>$this->input->post('total_discount'),
			'total_tax'=>$this->input->post('total_tax'),
			'chargeable_amount'=>$this->input->post('chargeable_amount'),
			'delivery_cost' => $this->input->post('delivery_cost'),
			'cod_cost' => $this->input->post('cod_cost'),
			'currency' => $this->input->post('currency'),
			'status'=>$this->input->post('status'),
			'merchant_id'=>$this->input->post('merchant_id'),
			'buyer_id'=>$this->input->post('buyer_id'),
			'trx_detail'=>$trx_detail,
			'width' => $this->input->post('width'),
			'height' => $this->input->post('height'),
			'length' => $this->input->post('length'),
			'weight' => $this->input->post('weight'),
			'delivery_type' => $this->input->post('delivery_type'),
			'show_merchant' => $this->input->post('show_merchant'),
			'show_shop' => $this->input->post('show_shop'),
			'cod_bearer' => $this->input->post('bearer_cod'),
			'delivery_bearer' => $this->input->post('bearer_delivery'),
			'cod_method' => $this->input->post('cod_method'),
			'ccod_method' => $this->input->post('ccod_method')
		);

		$trx['transaction_id'] = 'TRX_'.$merchant_id.'_'.str_replace(array(' ','.'), '', microtime());

		$api_key = $this->input->post('api_key');
		$trx_id = $trx['transaction_id'];

        $api_key = $this->input->post('api_key');
        $trx_id = $trx['transaction_id'];

        $result = $this->jexclient
                    ->base($this->config->item('api_url'))
                    ->endpoint('order/key/'.$api_key.'/trx/'.$trx_id)
                    ->data($trx)
                    ->format('json')
                    ->send();
        print $result;




		/*
		$lessday = ((strtotime($this->input->post('buyerdeliverytime')) - time()) < (get_option('auto_lock_hours')*60*60))?true:false;
		$lessday = ($this->input->post('buyerdeliverytime') === '0000-00-00 00:00:00')?false:$lessday;

		//assert value for test only
		//$lessday = false;

		if($lessday){
			$result = json_encode(array('status'=>'ERR:LOCKTIME','timestamp'=>now()));
		}else{
			$result = $this->curl->simple_post($url,array('transaction_detail'=>json_encode($trx)));
		}
		*/
        /*
        $url = $this->config->item('api_url').'post/'.$api_key.'/'.$trx_id;

		$result = $this->curl->simple_post($url,array('transaction_detail'=>json_encode($trx)));

		print $result;
        */
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