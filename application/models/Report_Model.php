<?php

class Report_Model extends CC_Model
{
	public function getList($type, $requestdata=[])
	{
		$this->db->select('t1.*');
		$this->db->from('reports t1');


		if(isset($requestdata['id'])) $this->db->where('t1.id', $requestdata['id']);
		if(isset($requestdata['status'])) $this->db->where_in('t1.status', $requestdata['status']);
		

		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$this->db->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			$column = ['t1.id', 't1.report_name', 't1.short_description'];
			$this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
		}

		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = strtolower((trim($requestdata['search']['value'])));
			$this->db->or_like('t1.id', $searchvalue);
			$this->db->or_like('t1.report_name', $searchvalue);
			$this->db->or_like('t2.short_description', $searchvalue);
			
		}
		

		if($type=='count'){
			$result = $this->db->count_all_results();

		}else{
			$query = $this->db->get();

			if($type=='all') $result = $query->result_array();
			elseif($type=='row') $result = $query->row_array();
		}

		return $result;
	}
	
	public function action($data)
	{ 
		$this->db->trans_begin();
		
		$userid			= 	$this->getUserID();
		$id 			= 	$data['id'];
		$datetime		= 	date('Y-m-d H:i:s');
		
		// $request		=	[

		// 	'updated_at' 		=> $datetime,
		// 	'updated_by' 		=> $userid
		// ];

		if(isset($data['report_name'])) 		$request['report_name'] 		= $data['report_name'];
		if(isset($data['short_description'])) 	$request['short_description'] 	= $data['short_description'];
		if(isset($data['message'])) 			$request['result_query'] 		= $data['message'];

		$request['status'] 	= (isset($data['status'])) ? $data['status'] : '1';

		if($id=='')
		{
			$request['created_at'] = $datetime;
			$request['created_by'] = $userid;
			
			$this->db->insert('reports', $request);
		}
		else
		{
			$request['updated_at'] = $datetime;
			$request['updated_by'] = $userid;

			$this->db->update('reports', $request, ['id' => $id]);
		}

		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function changestatus($data)
	{
		$userid		= 	$this->getUserID();
		$id			= 	$data['id'];
		$status		= 	$data['status'];
		$datetime	= 	date('Y-m-d H:i:s');
		
		$this->db->trans_begin();
		
		$delete 	= 	$this->db->update(
			'reports', 
			['status' => $status, 'updated_at' => $datetime, 'updated_by' => $userid], 
			['id' => $id]
		);
		
		if(!$delete || $this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}
}