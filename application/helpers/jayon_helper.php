<?php

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

	$this->db->select('id');
	$this->db->where('title',$group);
	$result = $this->db->get($this->ag_auth->config['auth_group_table']);
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

function colorizestatus($status){
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