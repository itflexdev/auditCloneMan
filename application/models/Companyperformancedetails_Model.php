<?php

class Companyperformancedetails_Model extends CC_Model
{
	public function getList($type, $requestdata=[])
	{
		$this->db->select('t1.*, t2.points as points');
		$this->db->from('company_performance_details t1');
		$this->db->join('company_performance_type t2','t2.document_type = t1.document_type', 'left');

		if(isset($requestdata['user_id'])) 		$this->db->where('t1.user_id', $requestdata['user_id']);		
		if(isset($requestdata['document_type'])) 	$this->db->where('t1.document_type', $requestdata['document_type']);
		if(isset($requestdata['status']))	$this->db->where_in('t1.status', $requestdata['status']);			
		if(isset($requestdata['id'])) 		$this->db->where('t1.id', $requestdata['id']);		
		if(isset($requestdata['date'])) 		$this->db->where('t1.date_of_renewal >=', $requestdata['date']);		

		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			$column = ['t1.updated_at', 't1.date_of_renewal', 't2.document_name', 't2.points', 't1.attachments'];
			$this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
		}

		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
			$this->db->group_start();
				$this->db->like('t2.document_name', $searchvalue);							
			$this->db->group_end();
		}
				
		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}	
		
		return $result;
	}

	public function GetDate_of_Renewal($requestdata=[])
	{
		$this->db->select('*');
		$this->db->from('company_performance_details');

		if(isset($requestdata['date_of_renewal'])) 	$this->db->where('date_of_renewal >', $requestdata['date_of_renewal']);
		if(isset($requestdata['user_id'])) 		$this->db->where('user_id', $requestdata['user_id']);		
		if(isset($requestdata['document_type'])) 	$this->db->where('document_type', $requestdata['document_type']);
		if(isset($requestdata['status']))	$this->db->where_in('status', $requestdata['status']);

		$result = $this->db->order_by('id',"desc")->limit(1)->get()->row_array();

		return $result;
	}
	
	public function action($data)
	{
		//print_r($data);die;
		$this->db->trans_begin();
		$userid = isset($data['userid']) ? $data['userid'] : $this->getUserID();
			
		if(isset($data['id']) ? $id = $data['id'] : $id	= '');
		$datetime		= 	date('Y-m-d H:i:s');

		// echo $id;
		// exit();
		
		$request		=	[
			'updated_at' 		=> $datetime,
			'updated_by' 		=> $userid,
			'user_id'	 		=> $userid
		];
									
		if(isset($data['document_type'])) 	$request['document_type'] 	= $data['document_type'];		
		if(isset($data['date_of_renewal'])) $request['date_of_renewal'] = date("Y-m-d", strtotime($data['date_of_renewal']));
		if(isset($data['image1'])) 			$request['attachments'] 	= $data['image1'];

		$request['status'] 	= 1;
					
		if($id==''){
			$request['created_at'] = $datetime;
			$request['created_by'] = $userid;
			// echo "<pre>";
			// print_r($request);
			// exit();
			$this->db->insert('company_performance_details', $request);
		}else{
			$this->db->update('company_performance_details', $request, ['id' => $id]);
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
			'company_performance_details', 
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