<?php

function get_delivery_id($sequence,$merchant_id){
	$CI =& get_instance();
	$year_count = str_pad($sequence, $CI->config->item('year_sequence_pad'), '0', STR_PAD_LEFT);
	$merchant_id = str_pad($merchant_id, $CI->config->item('merchant_id_pad'), '0', STR_PAD_LEFT);
	$delivery_id = $merchant_id.'-'.date('d-mY',time()).'-'.$year_count;

	return $delivery_id;
}

function get_yearly_sequence()
{
	$CI =& get_instance();

	$year = date('Y',time());

	$q = $CI->db->select('sequence')->where('year',$year)->get($CI->config->item('sequence_table'));
	if($q->num_rows() > 0){

	}else{
		$CI->db->insert($CI->config->item('sequence_table'),array('year'=>$year,'sequence'=>1));
		return 1;
	}
}

function get_zones($col = '*'){
	$CI =& get_instance();
	$q = $CI->db->select($col)->get('district');
	return $q->result_array();
}

function ajax_find_zones($zone,$col = 'district'){
	$CI =& get_instance();
	$q = $CI->db->select($col.' as id ,'.$col.' as label, '.$col.' as value',false)->like($col,$zone)->get('districts');
	return $q->result_array();
}

function get_option($key){
	$CI =& get_instance();

	$CI->db->select('val');
	$CI->db->where('key',$key);
	$result = $CI->db->get($CI->config->item('jayon_options_table'));
	$row = $result->row();
	return $row->val;
}

function user_group_id($group)
{
	$CI =& get_instance();

	$CI->db->select('id');
	$CI->db->where('title',$group);
	$result = $CI->db->get($CI->ag_auth->config['auth_group_table']);
	$row = $result->row();
	return $row->id;
}

function getdateblock($month = null){
	$blocking = array();
	$month = (is_null($month))?date('m',time()):$month;
	$year = date('Y',time());

	for($m = $month; $m < ($month + 2);$m++){
		for($i = 1;$i < 32;$i++){
			//print $date."\r\n";
			if(checkdate($m,$i,$year)){
				//check weekends
				$month = str_pad($m,2,'0',STR_PAD_LEFT);
				$day = str_pad($i,2,'0',STR_PAD_LEFT);
				$date = $year.'-'.$month.'-'.$day;
				$day = getdate(strtotime($date));
				//print_r($day)."\r\n";
				if($day['weekday'] == 'Sunday' || $day['weekday'] == 'Saturday'){
					$blocking[$date] = 'weekend';
				}else{
					$blocking[$date] = 'open';
				}
			}
		}
	}
	return json_encode($blocking);
}

function delivery_log($data){
	$CI =& get_instance();
	$CI->db->insert($CI->config->item('delivery_log_table'),$data);
	return true;
}

function full_reschedule($delivery_id, $datachanged){
	$CI =& get_instance();
	$old_order = $CI->db->where('delivery_id',$delivery_id)->get($CI->config->item('assigned_delivery_table'));
	$old_order = $old_order->row_array();

	$new_order = array_replace($old_order,$datachanged);
	$new_order['status'] = $CI->config->item('trans_status_new');
	$new_order['reschedule_ref'] = $delivery_id;
	$new_order['reattemp'] = (int) $new_order['reattemp'] + 1;
	unset($new_order['delivery_id']);
	unset($new_order['id']);
	
	$old_delivery_id = $delivery_id;

	$CI->db->insert($CI->config->item('assigned_delivery_table'),$new_order);

	$sequence = $this->db->insert_id();

	$year_count = str_pad($sequence, 10, '0', STR_PAD_LEFT);
	$merchant_id = str_pad($new_order['merchant_id'], 8, '0', STR_PAD_LEFT);
	$delivery_id = $merchant_id.'-'.date('d-mY',time()).'-'.$year_count;

	$this->db->where('id',$sequence)->update($this->config->item('assigned_delivery_table'),array('delivery_id'=>$delivery_id));

	$old_details = $CI->db->where('delivery_id',$old_delivery_id)->get($CI->config->item('delivery_details_table'));

	foreach ($old_details->result_array() as $detail){
		$detail['delivery_id'] = $delivery_id;
		$CI->db->insert($CI->config->item('delivery_details_table'),$detail);
	}

	return true;
}

function send_notification($subject,$to,$cc = null,$template = 'default',$data = null,$attachment = null){
	$CI =& get_instance();

	$config = array(
		'protocol' => 'smtp',
		'smtp_host' => $CI->config->item('smtp_host'),
		'smtp_port' => $CI->config->item('smtp_port'),
		'smtp_user' => $CI->config->item('notify_username'),
		'smtp_pass' => $CI->config->item('notify_password'),
		'charset'   => 'iso-8859-1'
	);

	$CI->load->library('email',$config);

	$CI->email->set_newline("\r\n");

	$CI->email->from($CI->config->item('notify_username'), 'Jayon Express Notification');

	if(is_null($data)){
		$data['type'] = 'notification';
	}

	if(is_array($to)){
		foreach($to as $em){
			$CI->email->to($em);
		}
		$log['to'] = implode(';',$to);
	}else{
		$CI->email->to($to);
		$log['to'] = $to;			
	}

	if(!is_null($cc)){
		if(is_array($cc)){
			foreach ($cc as $cm) {
				$CI->email->cc($cm);
			}
			$log['cc'] = implode(';',$cc);
		}else{
			$CI->email->cc($cc);
			$log['cc'] = $cc;			
		}
	}

	if(!is_null($attachment)){
		if(is_array($attachment)){
			foreach($attachment as $att){
				$CI->email->attach($att);
			}
			$log['att'] = implode(';',$attachment);
		}else{
			$CI->email->attach($attachment);			
			$log['att'] = $attachment;			
		}
	}

	$CI->email->cc('admin@jayonexpress.com');
	$CI->email->subject($subject);

	$body = $CI->load->view('email/'.$template,$data,TRUE);

	$CI->email->message($body);

	$result = $CI->email->send();

	$log['timestamp'] = date('Y-m-d h:i:s',time());
	$log['from'] = $CI->config->item('notify_username');
	$log['subject'] = $subject;
	$log['body'] = $body;
	$log['delivery_id'] = (isset($data['delivery_id']))?$data['delivery_id']:'-';
	$log['status'] = (isset($data['status']))?$data['delivery_id']:'-';
	$log['msg_status'] = $result;

	$CI->db->insert($CI->config->item('jayon_email_outbox_table'),$log);

	return $result;
}

function send_admin($subject,$to,$cc = null,$template = 'default',$data = '',$attachment = null){
	$CI =& get_instance();

	$config = array(
		'protocol' => 'smtp',
		'smtp_host' => $CI->config->item('smtp_host'),
		'smtp_port' => $CI->config->item('smtp_port'),
		'smtp_user' => $CI->config->item('admin_username'),
		'smtp_pass' => $CI->config->item('admin_password'),
		'charset'   => 'iso-8859-1'
	);

	$CI->load->library('email',$config);

	$CI->email->set_newline("\r\n");

	$CI->email->from($CI->config->item('notify_username'), 'Jayon Express Admin');

	if(is_null($data)){
		$data['type'] = 'adminmessage';
	}

	if(is_array($to)){
		foreach($to as $em){
			$CI->email->to($em);
		}
		$log['to'] = implode(';',$to);
	}else{
		$CI->email->to($to);
		$log['to'] = $to;			
	}

	if(!is_null($cc)){
		if(is_array($cc)){
			foreach ($cc as $cm) {
				$CI->email->cc($cm);
			}
			$log['cc'] = implode(';',$cc);
		}else{
			$CI->email->cc($cc);
			$log['cc'] = $cc;			
		}
	}

	if(!is_null($attachment)){
		if(is_array($attachment)){
			foreach($attachment as $att){
				$CI->email->attach($att);
			}
			$log['att'] = implode(';',$attachment);
		}else{
			$CI->email->attach($attachment);			
			$log['att'] = $attachment;			
		}
	}

	$CI->email->cc('admin@jayonexpress.com');
	$CI->email->subject($subject);

	$body = $CI->load->view('email/'.$template,$data,TRUE);

	$CI->email->message($body);

	$result = $CI->email->send();

	$log['timestamp'] = date('Y-m-d h:i:s',time());
	$log['from'] = $CI->config->item('admin_username');
	$log['subject'] = $subject;
	$log['body'] = $body;
	$log['delivery_id'] = (isset($data['delivery_id']))?$data['delivery_id']:'-';
	$log['status'] = (isset($data['status']))?$data['delivery_id']:'-';
	$log['msg_status'] = $result;

	$CI->db->insert($CI->config->item('jayon_email_outbox_table'),$log);

	return $result;
}

function colorizestatus($status){

	$colors = config_item('status_colors');
	if($status == '' || !in_array($status, array_keys($colors))){
		$class = 'brown';
		$status = 'N/A';
	}else{
		$class = $colors[$status];
	}

	return sprintf('<span class="%s">%s</span>',$class,$status);
}

function statusaction($status){
	switch($status){
		case 'canceled':
			$class = 'red';
			break;
		case 'cancel':
			$class = 'orange';
			break;
		case 'confirmed':
			$class = 'green';
			break;
		case 'confirmed':
			$class = 'green';
			break;
		default:
			$class = 'black';
	}

	return sprintf('<span class="%s">%s</span>',$class,$status);
}

?>