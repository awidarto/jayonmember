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

function user_group_id($group)
{
	$CI =& get_instance();
	
	$this->db->select('id');
	$this->db->where('title',$group);
	$result = $this->db->get($this->ag_auth->config['auth_group_table']);
	$row = $result->row();
	return $row->id;
}


?>