<?php

class Renewal_Model extends CC_Model
{
	public function getList($type, $requestdata=[])
	{
        $this->db->select ('inv.*, ud.name, ud.surname, ud.status as userstatus, up.registration_no, us.expirydate');
        $this->db->from('invoice inv');    
        $this->db->join('users_detail ud', 'ud.user_id = inv.user_id', 'left');
        $this->db->join('users_plumber up', 'up.user_id = inv.user_id', 'left');
        $this->db->join('users us', 'us.id = inv.user_id', 'left');
        $this->db->group_start();		
			$this->db->where('inv.inv_type', '2');
			$this->db->or_where('inv.inv_type', '3');
			$this->db->or_where('inv.inv_type', '4');
		$this->db->group_end();
        // $this->db->order_by("inv.inv_id", "desc"); 
               
     
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length']))
		{
			$this->db->limit($requestdata['length'], $requestdata['start']);
		}
		
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			$column = ['inv.inv_id', 'inv.created_at', 'ud.name', 'up.registration_no', 'inv.description', 'inv.total_cost', 'inv.status', 'inv.internal_inv'];
			$this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
		}

		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = trim($requestdata['search']['value']);
			if($searchvalue == 'Paid'){
				$this->db->where('inv.status', '1');
			}
			elseif($searchvalue == 'Unpaid'){
				$this->db->where('inv.status', '0');	
			}
			else{
				$this->db->group_start();			
					$this->db->like('inv.inv_id', $searchvalue);
					$this->db->or_like('ud.name', $searchvalue);
					$this->db->or_like('ud.surname', $searchvalue);		
					$this->db->or_like('up.registration_no', $searchvalue);
					$this->db->or_like('inv.description', $searchvalue);
					$this->db->or_like('inv.total_cost', $searchvalue);
					$this->db->or_like('inv.internal_inv', $searchvalue);
				$this->db->group_end();
			}
		}
		
		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}

		// echo $this->db->last_query();
		// exit;

		
		return $result;
	}
	
	public function getUserids()
	{
		$this->db->select('us.id, us.expirydate, up.designation');		
		$this->db->from('users us');
		$this->db->join('users_plumber as up', 'up.user_id=us.id', 'inner');
		$this->db->where(['us.type' => '3', 'us.status' => '1', 'us.expirystatus' => '0', 'DATE_SUB(DATE(us.expirydate), INTERVAL 30 DAY) <=' => date('Y-m-d'), 'DATE(us.expirydate) >=' => date('Y-m-d')]);
		$this->db->group_by('us.id');
		//$this->db->limit(20); // to get 20 members for live testing.
		$result = $this->db->get()->result_array();		
		echo $this->db->last_query();
		return $result;
	}

	public function getUseridsNinetydays()
	{
		$this->db->select('us.id, us.email, us.expirydate, up.designation');		
		$this->db->from('users us');
		$this->db->join('users_plumber as up', 'up.user_id=us.id', 'inner');
		// $this->db->where(['us.type' => '3', 'us.status' => '1', 'us.expirystatus' => '0', 'up.designation' => '4']);
		$this->db->where(['us.type' => '3', 'us.status' => '1', 'us.expirystatus' => '0', 'up.designation' => '4']);
		$this->db->where('DATE(us.expirydate) = DATE_ADD(DATE(curdate()), INTERVAL 90 DAY)');
		$this->db->group_by('us.id');
		$result = $this->db->get()->result_array();		
		// echo '<pre>'.$this->db->last_query();die;
		return $result;
	}

	public function getRenewalPlumbers()
	{
		$date = date('Y-m-d');
		$this->db->select('us.id, us.email, us.expirydate, us.old_expirydate, us.renewal_date, up.designation, up.registration_no, ud.name, ud.surname, us.old_expirydate, ud.status');		
		$this->db->from('users us');
		$this->db->join('users_plumber as up', 'up.user_id=us.id', 'inner');
		$this->db->join('users_detail as ud', 'ud.user_id=us.id', 'inner');
		// $this->db->where(['us.type' => '3', 'us.status' => '1', 'us.expirystatus' => '0']);
		$this->db->where(['us.type' => '3', 'us.status' => '1', 'us.expirystatus' => '0']);
		$this->db->where('ud.status !=', '2');
		$this->db->group_start();
				$this->db->where('DATE(us.expirydate) <= DATE(curdate())');
				// $this->db->or_where('DATE(us.old_expirydate) <= DATE(curdate())');
				$this->db->or_where('DATE(us.old_expirydate) >= DATE(curdate())');
		$this->db->group_end();
		$this->db->group_by('us.id');
		$result = $this->db->get()->result_array();		
		// echo '<pre>'.$this->db->last_query();die;
		return $result;
	}

	public function getRenewalPlumbers1()
	{
		$this->db->select('us.id, us.email, us.expirydate, us.old_expirydate, us.renewal_date, up.designation, up.registration_no, ud.name, ud.surname, us.old_expirydate, ud.status');
		$this->db->from('users us');
		$this->db->join('users_plumber as up', 'up.user_id=us.id', 'inner');
		$this->db->join('users_detail as ud', 'ud.user_id=us.id', 'inner');
		// $this->db->where(['us.type' => '3', 'us.status' => '1', 'us.expirystatus' => '0']);
		$this->db->where(['us.type' => '3', 'us.status' => '1', 'us.expirystatus' => '0']);
		$this->db->group_start();
				$this->db->where('DATE(us.expirydate) = DATE(curdate())');
				$this->db->or_where('DATE(us.old_expirydate) = DATE(curdate())');
		$this->db->group_end();
		$this->db->group_by('us.id');
		$result = $this->db->get()->result_array();		
		// echo '<pre>'.$this->db->last_query();die;
		return $result;
	}

	public function getUserids_alert2() 
	{	
		$this->db->select('us.id, us.expirydate, up.designation, inv.inv_id, coc.id as cocid');	
		$this->db->from('users us');
		$this->db->join('users_plumber as up', 'up.user_id=us.id', 'inner');
		$this->db->join('invoice inv', 'inv.inv_id=us.expiryinvoiceid', 'inner');
		$this->db->join('coc_orders coc', 'coc.inv_id=inv.inv_id', 'inner');
		$this->db->where(['us.type' => '3', 'us.status' => '1', 'us.expirystatus' => '1', 'DATE(us.expirydate) <=' => date('Y-m-d'), 'inv.inv_type' => '2', 'inv.status' => '0']);
		$this->db->group_by('us.id');
		$result = $this->db->get()->result_array();			
		
		return $result;
	}

	public function getUserids_alert3()
	{
		$this->db->select('us.id, us.expirydate, up.designation, inv.inv_id, coc.id as cocid');		
		$this->db->from('users us');
		$this->db->join('users_plumber as up', 'up.user_id=us.id', 'inner');
		$this->db->join('invoice inv', 'inv.inv_id=us.expiryinvoiceid', 'inner');
		$this->db->join('coc_orders coc', 'coc.inv_id=inv.inv_id', 'inner');
		$this->db->where(['us.type' => '3', 'us.status' => '1', 'us.expirystatus' => '2', 'DATE_ADD(DATE(us.expirydate), INTERVAL 1 DAY) <=' => date('Y-m-d'), 'inv.inv_type' => '3', 'inv.status' => '0']);
		$this->db->group_by('us.id');	
		$result = $this->db->get()->result_array();	
		
		return $result;
	}

	public function getUserids_alert4()
	{
		$this->db->select('us.id, us.email, us.expirydate, up.designation, inv.inv_id, ud.name, ud.surname, ud.mobile_phone');	
		$this->db->from('users us');
		$this->db->join('users_plumber as up', 'up.user_id=us.id', 'inner');
		$this->db->join('users_detail as ud', 'ud.user_id=us.id', 'inner');
		$this->db->join('invoice inv', 'inv.inv_id=us.expiryinvoiceid', 'inner');
		$this->db->where(['us.type' => '3', 'us.status' => '1', 'us.expirystatus' => '3', 'inv.inv_type' => '4', 'inv.status' => '0']);
		$result = $this->db->get()->result_array();		 	
		
		return $result;
	}


	public function checkinv($userid)
	{
		$this->db->select('*');		
		$this->db->from('invoice');			
		$this->db->where(['user_id' => $userid, 'status' => '0']);	
		$this->db->where_in('inv_type', ['2','3','4']);	
		$result = $this->db->get()->result_array();
		return $result;
	}

	public function get_lateamount()
	{
		$this->db->select('amount');
		$this->db->from('rates');
		$this->db->where('id', '10');
		$lateamount_result = $this->db->get()->row_array();
		return $lateamount_result;
	}

	public function updatedata($userid,$designation,$inv_type,$invoice_id='',$cocid='',$otherfee=[])
	{
		$currentdate = date('Y-m-d H:i:s');	
		
		$this->db->select('amount');
		$this->db->from('rates');
		if($designation == '1')		$this->db->where('id', $this->config->item('learner'));
		elseif($designation == '2')	$this->db->where('id', $this->config->item('assistant'));
		elseif($designation == '3')	$this->db->where('id', $this->config->item('operator'));
		elseif($designation == '4')	$this->db->where('id', $this->config->item('licensed'));
		elseif($designation == '5')	$this->db->where('id', $this->config->item('qualified'));
		elseif($designation == '6')	$this->db->where('id', $this->config->item('master'));
		else 						$this->db->where('supplyitem', 'Registration Rates');
		$rates = $this->db->get()->row_array(); 
		$rate = $rates['amount'];

		$this->db->select('vat_percentage');
		$this->db->from('settings_details');
		$this->db->where('id', '1');
		$vats = $this->db->get()->row_array();
		$vat = $vats['vat_percentage'];

		if($inv_type == '4'){
			$this->db->select('amount');
			$this->db->from('rates');
			$this->db->where('id', '10');
			$lateamount_result = $this->db->get()->row_array();
			$lateamount = $lateamount_result['amount'];		

			$rate1 = $rate + $lateamount;
			$vat_amount1 = $rate1 * $vat / 100;
			$vat_amount1 = round($vat_amount1,2);

			$vat_lateamount = $lateamount * $vat / 100;
			$vat_lateamount = round($vat_lateamount,2);
			$total_lateamount = $vat_lateamount + $lateamount;
		}

		$vat_amount = $rate * $vat / 100;
		$vat_amount = round($vat_amount,2);
		$total = $vat_amount + $rate;

		$result['invoice_id'] 	= $invoice_id;
		$result['cocorder_id']  = $cocid;	
		
		$request['description'] 	= 'Renewal Fee';
		$request['user_id'] 		= $userid;
		$request['status'] 			= '0';
		$request['inv_type'] 		= $inv_type;
		$request['coc_type'] 		= '0';
		$request['delivery_type'] 	= '2';		
		$request['created_at'] 		= $currentdate;			
		$request['total_cost'] 		= $rate;
		$request['vat'] 			= $vat_amount;

		if($inv_type == '2'){
			$this->db->insert('invoice', $request);
			$result['invoice_id'] = $this->db->insert_id();
		}elseif($inv_type == '4'){			
			$request['total_cost'] = $rate1;
			$request['vat'] = $vat_amount1;
		}
		
		$invoice_id = $result['invoice_id'];
		$this->db->update('invoice', $request, ['inv_id' => $invoice_id]);
		$this->db->update('users', ['expiryinvoiceid' => $invoice_id, 'expirystatus' => ($inv_type-1)], ['id' => $userid]);
		
		$request1['description'] 	= 'Renewal Fee';
		$request1['user_id'] 		= $userid;
		$request1['quantity'] 		= '1';
		$request1['status'] 		= '0';
		$request1['cost_value'] 	= $rate;
		$request1['coc_type'] 		= '0';
		$request1['delivery_type'] 	= '2';
		$request1['total_due'] 		= $total;	
		$request1['vat'] 			= $vat_amount;
		$request1['inv_id'] 		= $invoice_id;			
		$request1['created_at'] 	= $currentdate;
		$request1['created_by'] 	= $userid;
		
		if($inv_type == '2'){
			$this->db->insert('coc_orders', $request1);
			$result['cocorder_id']  = $this->db->insert_id();
		}
		
		$cocid = $result['cocorder_id'];
		$this->db->update('coc_orders', $request1, ['id' => $cocid]);
		
		if($inv_type == '4'){
			$request1['description'] 	= 'Late Penalty Fee';
			$request1['cost_value'] 	= $lateamount;
			$request1['total_due'] 		= $total_lateamount;
			$request1['vat'] 			= $vat_lateamount;
			
			$this->db->insert('coc_orders', $request1);
			$result['cocorder_id2']  = $this->db->insert_id();
		}
		
		if(count($otherfee) > 0){
			$this->db->delete('coc_orders', array('inv_id' => $invoice_id, 'otherfee' => '1'));
			
			for($i=0; $i<3; $i++){
				if($i==0 && isset($otherfee['cardfee'])){
					$description 	= 'Plumber ID Card';
					$otherrate		= $otherfee['cardfee'];
					$otherqty 		= '1';
				}elseif($i==1 && isset($otherfee['deliveryfee'])){
					$description 	= ($otherfee['deliverycard']=='1') ? 'Delivery By Registered Post' : 'Delivery by Courier';
					$otherrate		= $otherfee['deliveryfee'];
					$otherqty 		= '0';
				}elseif($i==2 && isset($otherfee['specialisationsfee'])){
					$description 	= 'Specialization Rate';
					$otherrate		= $otherfee['specialisationsqty']*$otherfee['specialisationsfee'];
					$otherqty		= $otherfee['specialisationsqty'];
				}else{
					continue;
				}
				
				$vatotherrate 	= ($otherrate * $vat) / 100;
				$otherratevat 	= round($vatotherrate,2);
				
				$othertotal		= $otherrate + $otherratevat;
				
				$otherfeedata['description'] = $description;
				$otherfeedata['user_id'] = $userid;
				$otherfeedata['quantity'] = $otherqty;
				$otherfeedata['status'] = '0';
				$otherfeedata['cost_value'] = $otherrate;
				$otherfeedata['coc_type'] = '0';
				$otherfeedata['delivery_type'] = '0';
				$otherfeedata['total_due'] = $othertotal;	
				$otherfeedata['vat'] = $otherratevat;
				$otherfeedata['inv_id'] = $invoice_id;			
				$otherfeedata['created_at'] = $currentdate;
				$otherfeedata['created_by'] = $userid;
				$otherfeedata['otherfee'] = '1';
				
				$this->db->insert('coc_orders', $otherfeedata);
			}			
		}
		
		$sumotherfee = $this->db->select('sum(cost_value) as cost, sum(vat) as vat, sum(total_due) as total')->from('coc_orders')->where(['inv_id' => $invoice_id, 'otherfee' => '1'])->get()->row_array();
		$originalfee = $this->db->select('total_cost, vat')->from('invoice')->where(['inv_id' => $invoice_id])->get()->row_array();
		$this->db->update('invoice', ['total_cost' => $originalfee['total_cost']+$sumotherfee['cost'], 'vat' => $originalfee['vat']+$sumotherfee['vat']], ['inv_id' => $invoice_id]);
		
		
		return $result;
	}
	
	public function getPermissions($type1)
	{ 
		$this->db->select('*');
		$this->db->from('settings_details');
		
      if($type1=='count'){
			$result = $this->db->count_all_results();
			
		}else{
			$query = $this->db->get();
			
			
			if($type1=='all') 		$result = $query->result_array();
			elseif($type1=='row') 	$result = $query->row_array();
		}
		
		return $result;
	}

	public function deleteid($id)
	{ 
		$url = FCPATH."assets/inv_pdf/".$id.".pdf";
		unlink($url);
			
		$this->db->where('inv_id', $id);		
		$result = $this->db->delete('invoice');

		$this->db->where('inv_id', $id);		
		$result1 = $this->db->delete('coc_orders');		

		return $result;

	}


	public function getPermissions1($type2)
	{ 
		$this->db->select('*');
		$this->db->from('settings_address');
		
      if($type2=='count'){
			$result = $this->db->count_all_results();
			
		}else{
			$query = $this->db->get();
			
			
			if($type2=='all') 		$result = $query->result_array();
			elseif($type2=='row') 	$result = $query->row_array();
		}
		
		return $result;
	}
	
	public function changestatus($data)
	{
		$userid		= 	$this->getUserID();
		$id			= 	$data['id'];
		$status		= 	$data['status'];
		$datetime	= 	date('Y-m-d H:i:s');
		
		$this->db->trans_begin();
		
		$delete 	= 	$this->db->update(
			'installationtype', 
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