<?php

class Company_Model extends CC_Model
{
	public function getList($type, $requestdata=[], $querydata=[])
	{
		$select = [];
		
		if(in_array('users', $querydata)){
			$users 			= 	[ 
									'u.id','u.email','u.formstatus','u.expirydate','u.type','u.status','u.created_at' 
								];
								
			$select[] 		= 	implode(',', $users);
		}
		
		if(in_array('usersdetail', $querydata)){
			$usersdetail 	= 	[ 
									'ud.id as usersdetailid','ud.company','ud.reg_no','ud.vat_no','ud.contact_person','ud.work_phone','ud.mobile_phone','ud.specialisations','ud.email2','ud.mobile_phone2','ud.home_phone','ud.status as companystatus', 'ud.file1 as file1','ud.billing_email','ud.billing_contact', 'ud.vat_vendor', 'ud.coc_purchase_limit, ud.company_name'
								];
			
			$select[] 		= 	implode(',', $usersdetail);
		}
		
		if(in_array('userscompany', $querydata)){
			$userscompany 	= 	[ 
									'uc.id as userscompanyid','uc.work_type','uc.message','uc.approval_status','uc.reject_reason','uc.reject_reason_other', 'uc.includeprofile as includeprofile', 'uc.company_description as companydescription', 'uc.websiteurl'
								];
			
			$select[] 		= 	implode(',', $userscompany);
		}	
		
		if(in_array('physicaladdress', $querydata)){
			$select[] 		= 	'concat_ws("@-@", ua1.id, ua1.user_id, ua1.address, ua1.suburb, ua1.city, ua1.province, ua1.postal_code, ua1.type)  as physicaladdress';
		}
		
		if(in_array('postaladdress', $querydata)){
			$select[]		= 	'concat_ws("@-@", ua2.id, ua2.user_id, ua2.address, ua2.suburb, ua2.city, ua2.province, ua2.postal_code, ua2.type)  as postaladdress';
		}

		if(in_array('billingaddress', $querydata)){
			$select[]		= 	'concat_ws("@-@", ua3.id, ua3.user_id, ua3.address, ua3.suburb, ua3.city, ua3.province, ua3.postal_code, ua3.type)  as billingaddress';
		}
		
		if(in_array('lttqcount', $querydata)){
			$select[]		= 	'
									(
										SELECT
										count(lttq.id)
										FROM users_plumber lttq
										WHERE lttq.company_details = u.id and (lttq.designation="1" or lttq.designation="2" or lttq.designation="3" or lttq.designation="5")
									) as lttqcount
								';
			$sortlttq 		= 	'lttqcount';
		}else{
			$sortlttq 		= 	'';
		}
		
		if(in_array('lmcount', $querydata)){
			$select[]		= 	'
									(
										SELECT
										count(lm.id)
										FROM users_plumber lm
										WHERE lm.company_details = u.id and (lm.designation="4" or lm.designation="6")
									) as lmcount
								';
		
			$sortlm 		= 	'lmcount';
		}else{
			$sortlm 		= 	'';
		}
		
		$this->db->select(implode(',', $select));
		$this->db->from('users u');		
		if(in_array('usersdetail', $querydata)) 		$this->db->join('users_detail ud', 'ud.user_id=u.id', 'left');
		if(in_array('userscompany', $querydata))		$this->db->join('users_company uc', 'uc.user_id=u.id', 'left');
		if(in_array('physicaladdress', $querydata))		$this->db->join('users_address ua1', 'ua1.user_id=u.id and ua1.type="1"', 'left');
		if(in_array('postaladdress', $querydata))		$this->db->join('users_address ua2', 'ua2.user_id=u.id and ua2.type="2"', 'left');
		if(in_array('billingaddress', $querydata)) 		$this->db->join('users_address ua3', 'ua3.user_id=u.id and ua3.type="3"', 'left');
			
		if(isset($requestdata['id'])) 					$this->db->where('u.id', $requestdata['id']);
		if(isset($requestdata['type'])) 				$this->db->where('u.type', $requestdata['type']);
		if(isset($requestdata['formstatus']))			$this->db->where_in('u.formstatus', $requestdata['formstatus'][0]);
		if(isset($requestdata['status']))				$this->db->where_in('u.status', $requestdata['status']);
		if(isset($requestdata['companystatus']))		$this->db->where_in('ud.status', $requestdata['companystatus']);
		if(isset($requestdata['approvalstatus']))		$this->db->where_in('uc.approval_status', $requestdata['approvalstatus']);
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$this->db->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			$column = ['u.id', 'ud.company', 'u.status', $sortlm, $sortlttq];
			$this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
			$this->db->group_start(); // Open bracket
			$this->db->like('u.id', $searchvalue);
			$this->db->or_like('ud.company', $searchvalue);
			$this->db->or_like('u.status', $searchvalue);
			$this->db->group_end(); // Open bracket
		}
		
		$this->db->group_by('u.id');
		
		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		return $result;
	}

	// COMPANY EmpList
	public function getEmpList($type, $requestdata=[])
	{
		 $this->db->select('t1.registration_no,t1.designation,t1.id,t1.user_id,t2.name,t2.surname,t2.mobile_phone,t3.email,t3.status,t2.file2,t2.specialisations, t1.coc_electronic');
		 $this->db->from('users t3');
        $this->db->join('users_plumber t1', 't3.id = t1.user_id', 'LEFT');
        $this->db->join('users_detail t2', 't2.user_id = t1.user_id', 'LEFT');        
        //$this->db->join('cpd_activity_form t4', 't4.user_id = t1.user_id AND t4.status="1"', 'LEFT');
        if($type=='employee'){
            //print_r
            $this->db->where('t1.id', $requestdata['comp_id']);
        }else{
            $this->db->where('t1.company_details', $requestdata['comp_id']);
        }
        

        // if(isset($requestdata['id']))        $this->db->where('id', $requestdata['id']);
        // //if(isset($requestdata['status']))  $this->db->where_in('status', $requestdata['status']);
        
        if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
            $this->db->limit($requestdata['length'], $requestdata['start']);
        }
        // if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
        //  $column = ['id', 'company_name'];
        //  $this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
        // }
        if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
            $searchvalue = $requestdata['search']['value'];
            $this->db->like('t2.name', $searchvalue);
        }

        
        if($type=='count'){
            $result = $this->db->count_all_results();
        }
        elseif($type=='employee'){
            $query = $this->db->get();
            
            $result = $query->result_array();
        }
        else{
            $query = $this->db->get();
            
            if($type=='all')        $result = $query->result_array();
            elseif($type=='row')    $result = $query->row_array();
        }
        
        return $result;
	}

	public function getdesignationCount($pageData){
		$this->db->select('t0.formstatus,t1.designation, count(t1.designation) as desigcount, t1.approval_status,');
		$this->db->from('users t0');
		$this->db->join('users_plumber t1', 't1.user_id = t0.id', 'LEFT');
        $this->db->join('users_detail t2', 't2.user_id = t1.user_id', 'LEFT');
        if(isset($pageData['comID'])) $this->db->where('t1.company_details', $pageData['comID']);
        if(isset($pageData['designation'])) $this->db->where('t1.designation', $pageData['designation']);
        $this->db->where('t0.formstatus', '1');
        $this->db->where('t1.approval_status', '1');
        $result = $this->db->join('users t3', 't3.id = t1.user_id', 'LEFT')->get()->result_array();
        return $result;
	}

	public function getauditPoints($id){
		$this->db->select('id, plumber_id, sum(point) as performance');
		$this->db->from('auditor_statement');
		$result = $this->db->where('plumber_id',$id)->get()->result_array();
		return $result;
	}
	public function cpdPoints($id){
		$this->db->select('id, user_id, status, sum(points) as cpd');
		$this->db->from('cpd_activity_form');
		$this->db->where('status', '1');
		$result = $this->db->where('user_id',$id)->get()->result_array();
		return $result;
	}
	
	public function action($data)
	{
		$this->db->trans_begin();
		
		$userid			= 	$this->getUserID();
		$datetime		= 	date('Y-m-d H:i:s');
		$idarray 		= 	[];
				
		if(isset($data['name'])) 				$request1['company'] 			= $data['name'];
		if(isset($data['reg_no'])) 				$request1['reg_no'] 			= $data['reg_no'];
		if(isset($data['vat_no'])) 				$request1['vat_no'] 			= $data['vat_no'];
		if(isset($data['contact_person'])) 		$request1['contact_person'] 	= $data['contact_person'];
		if(isset($data['work_phone'])) 			$request1['work_phone'] 		= $data['work_phone'];
		if(isset($data['mobile_phone'])) 		$request1['mobile_phone'] 		= $data['mobile_phone'];
		if(isset($data['image2'])) 				$request1['file1'] 				= $data['image2'];
		if(isset($data['billing_email'])) 		$request1['billing_email'] 		= $data['billing_email'];
		if(isset($data['billing_contact'])) 	$request1['billing_contact'] 	= $data['billing_contact'];
		// if(isset($data['company_name'])) 		$request1['company'] 			= $data['company_name'];
		if(isset($data['company_name'])) 		$request1['company_name'] 		= $data['company_name'];
		if(isset($data['reg_no1'])) 			$request1['reg_no'] 			= $data['reg_no1'];
		// if(isset($data['vat_no'])) 				$request1['vat_no'] 			= $data['vat_no'];
		if (isset($data['vatvendor']) && $data['vatvendor'] !='') {
			$request1['vat_vendor'] 		= '1';
		}else{
			$request1['vat_vendor'] 		= '0';
		}
		
		if(isset($data['coc_purchase_limit'])) 	$request1['coc_purchase_limit']	= $data['coc_purchase_limit'];

		if(isset($data['home_phone'])) 			$request1['home_phone'] 		= $data['home_phone'];
		if(isset($data['secondary_phone'])) 	$request1['mobile_phone2'] 		= $data['secondary_phone'];
		if(isset($data['email2'])) 				$request1['email2'] 			= $data['email2'];

		if(isset($data['specilisations'])) 		$request1['specialisations']	= implode(',', $data['specilisations']);
		if(isset($data['companystatus'])) 		$request1['status'] 			= $data['companystatus'];
		if(isset($data['approval_status']) && $data['approval_status']=='1'){
			$request1['status'] 	= '1';
		}

		if (isset($data['roletype'])) {
			$user_id = $data['user_id'];
			$u_request['email'] = $data['email'];
			$usersdata = $this->db->update('users', $u_request, ['id' => $user_id]);
		}
		
		if(isset($request1)){
			$usersdetailid	= 	$data['usersdetailid'];
			if(isset($data['user_id'])) $request1['user_id'] = $data['user_id'];
			
			if($usersdetailid==''){
				$usersdetail = $this->db->insert('users_detail', $request1);
				$usersdetailinsertid = $this->db->insert_id();
			}else{
				$usersdetail = $this->db->update('users_detail', $request1, ['id' => $usersdetailid]);
				$usersdetailinsertid = $usersdetailid;
			}
			$idarray['usersdetailinsertid'] = $usersdetailinsertid;
		}
		
		if(isset($data['address']) && count($data['address'])){
			$usersaddressinsertids = [];
			foreach($data['address'] as $key => $request2){
				if(isset($data['user_id'])) $request2['user_id'] = $data['user_id'];
				if($request2['id']==''){
					$usersaddress = $this->db->insert('users_address', $request2);
					$usersaddressinsertids[$request2['type']] = $this->db->insert_id();
				}else{
					$usersaddress = $this->db->update('users_address', $request2, ['id' => $request2['id']]);
					$usersaddressinsertids[$request2['type']] = $request2['id'];
				}
			}
			$idarray['usersaddressinsertids'] = $usersaddressinsertids;
		}
		
		if(isset($data['worktype'])) 				$request3['work_type'] 				= implode(',', $data['worktype']);
		if(isset($data['includeprofile'])) 			$request3['includeprofile'] 		= $data['includeprofile'];
		if(isset($data['companydescription'])) 		$request3['company_description'] 	= $data['companydescription'];
		if(isset($data['websiteurl'])) 				$request3['websiteurl'] 			= $data['websiteurl'];
		if(isset($data['approval_status']))			$request3['approval_status'] 		= $data['approval_status'];
		if(isset($data['message'])) 				$request3['message'] 				= $data['message'];
		if(isset($data['approval_status'])) 		$request3['approval_status'] 		= $data['approval_status'];
		if(isset($data['reject_reason'])) 			$request3['reject_reason'] 			= implode(',', $data['reject_reason']);
		if(isset($data['reject_reason_other'])) 	$request3['reject_reason_other'] 	= $data['reject_reason_other'];
		
		if(isset($request3)){
			$userscompanyid	= 	$data['userscompanyid'];
			if(isset($data['user_id'])) $request3['user_id'] = $data['user_id'];
			
			if($userscompanyid==''){
				$usersdetail = $this->db->insert('users_company', $request3);
				$userscompanyinsertids = $this->db->insert_id();
			}else{
				$usersdetail = $this->db->update('users_company', $request3, ['id' => $userscompanyid]);
				$userscompanyinsertids = $userscompanyid;
			}
			$idarray['userscompanyinsertid'] = $userscompanyinsertids;
		}
		
		if(isset($data['formstatus'])) 		$request4['formstatus'] 	= $data['formstatus'];
		if(isset($data['migrateid'])) 		$request4['migrateid'] 		= $data['migrateid'];
		//if(isset($data['companystatus'])) 	$request4['status']			= $data['companystatus'];
		if(isset($data['approval_status'])) $request4['status']			= '1';
		if(isset($data['companystatus']) && $data['companystatus']=='2') 	$request4['status'] 		= '2';
		if(isset($data['companystatus']) && $data['companystatus']=='1') 	$request4['status'] 		= '1';
		if(isset($data['companystatus']) && $data['companystatus']=='3') 	$request4['status'] 		= '2';
		if(isset($data['companystatus']) && $data['companystatus']=='4') 	$request4['status'] 		= '2';
		if(isset($request4)){
			if(isset($data['user_id'])){
				$userid = $data['user_id'];	
				$users = $this->db->update('users', $request4, ['id' => $userid]);
			}
		}
		
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return $idarray;
		}
	}

		public function ajaxOTP($requestdata){
		$query = $this->db->get_where('otp', array('user_id' => $requestdata['user_id']) );
		$count = $query->num_rows();
		if ($count == 1) {
			$this->db->set('otp',$requestdata['otp']);
			$this->db->where('user_id', $requestdata['user_id']);
			$this->db->update('otp');
		}else{
			$result = $this->db->insert('otp',$requestdata);
		}

	}

	public function OTPVerification($requestdata){
		$result = $this->db->select('*')
		->from('otp')
		->where('user_id',$requestdata['user_id'])
		->where('otp',$requestdata['otp'])
		->order_by('id', 'DESC')
		->limit(1)
		->get()
		->row_array();
		if ($result) {
			return '1';
		}else{
			return '0';
		}
	}

	public function companydiary($data){

		$created_by = $this->getuserID();
		$datetime 	= date('Y-m-d H:i:s');


		if(isset($data['comments'])) 		$request1['comments'] 		= $data['comments'];
		if(isset($data['user_id'])) 		$request1['user_id'] 		= $data['user_id'];
		if(isset($created_by)) 				$request1['created_by'] 	= $created_by;
		if(isset($datetime)) 				$request1['created_at'] 	= $datetime;
		
		$result = $this->db->insert('users_comment',$request1);
		if ($result) {
			return true;
		}else{
			return false;
		}

	}

	public function getInvoiceList($type, $requestdata=[])
	{		
        $query=$this->db->select('
			t1.*,
        	t2.inv_id as inv_id2, sum(t2.total_due) as total_due, sum(t2.quantity) as quantity, sum(t2.cost_value) as cost_value, sum(t2.delivery_cost) as delivery_cost,
			t3.reg_no, t3.id, t3.name name, t3.surname surname, t3.company company, t3.company_name company_name,t3.vat_no vat_no, t3.email2, t3.home_phone,
			t4.type,t4.address,t4.province, t4.suburb, t4.city,
			c1.name as orderstatusname
		');
        $this->db->from('invoice t1');
        $this->db->join('coc_orders t2','t2.inv_id = t1.inv_id', 'left');
        $this->db->join('users_detail t3', 't3.user_id = t1.user_id', 'left');
        $this->db->join('users_address t4', 't4.user_id = t1.user_id AND t4.type=1', 'left');
		$this->db->join('users_company t5', 't5.user_id = t1.user_id', 'left');
		$this->db->join('users u', 'u.id=t1.user_id', 'inner');
		$this->db->join('custom c1', 'c1.c_id=t1.order_status and c1.type="7"', 'left');
		$this->db->where('u.type', '4');
		
		if(isset($requestdata['id'])) 		$this->db->where('t1.inv_id', $requestdata['id']);
		if(isset($requestdata['user_id'])) 	$this->db->where('t1.user_id', $requestdata['user_id']);
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$this->db->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				if($page=='companyaccount'){
					$column = ['t1.description', 't1.inv_id', 't1.created_at', 't2.total_due', 't1.status'];
				}
			}else{
				$column = ['inv_id', 'created_at', 'company_name', 'reg_no', 'description', 'total_cost', 'internal_inv'];
			}			
			
			$this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
		}

		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = strtolower(trim($requestdata['search']['value']));
			$this->db->group_start();			
			if($searchvalue=='paid'){
				$this->db->where('t1.status', '1');
			}
			else if($searchvalue=='not paid' || $searchvalue=='unpaid'){
				$this->db->where('t1.status', '0');
			}
			else{					
				if(isset($requestdata['page'])){
					$page = $requestdata['page'];
					if($page=='companyaccount'){
						$this->db->like('t1.description', $searchvalue);
						$this->db->or_like('t1.inv_id', $searchvalue);
						$this->db->or_like('DATE_FORMAT(t1.created_at,"%d-%m-%Y")', $searchvalue);
						$this->db->or_like('t2.total_due', $searchvalue);
						$this->db->or_like('t1.status', $searchvalue);
					}
				}else{
					$this->db->like('t1.inv_id', $searchvalue);
					$this->db->or_like('t1.description', $searchvalue);
					$this->db->or_like('DATE_FORMAT(t1.created_at,"%d-%m-%Y")', $searchvalue);
					$this->db->or_like('t1.total_cost', $searchvalue);
					$this->db->or_like('t1.internal_inv', $searchvalue);
					$this->db->or_like('t3.company_name', $searchvalue);
					$this->db->or_like('t3.company', $searchvalue);
					$this->db->or_like('t3.reg_no', $searchvalue);
				}			
			}
			$this->db->group_end();
		}
		
		$this->db->group_by('t1.inv_id');
		
		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
   
		return $result;
	}

	public function getstockList($type, $requestdata=[]){ 		

		$this->db->select('sm.*,ud.name as name,ud.surname as surname,up.registration_no as registration_no,pa.invoiceno as invoiceno,pd.company as company,cl.address cl_address,cl.name cl_name,cl.street cl_street,s.name as cl_suburb_name,c.name as cl_city_name,p.name as cl_province_name');
		$this->db->from('stock_management sm');
		$this->db->join('plumberallocate_company pa', 'pa.coc_id=sm.id','left');
		$this->db->join('users_detail ud', 'ud.user_id=sm.user_id','left');
		$this->db->join('users_plumber up', 'up.user_id=sm.user_id','left');
		$this->db->join('users_detail pd', 'pd.user_id=pa.company_details', 'left');
		$this->db->join('coc_log cl', 'cl.coc_id=sm.id', 'left'); // Coc Log		
		$this->db->join('province p', 'p.id=cl.province', 'left'); // Coc Log Province
		$this->db->join('city c', 'c.id=cl.city', 'left'); // Coc Log City
		$this->db->join('suburb s', 's.id=cl.suburb', 'left'); // Coc Log Suburb
		$this->db->join('users_detail cd1', 'cd1.user_id=cl.company_details', 'left'); // 

		if((isset($requestdata['search']['value']) && $requestdata['search']['value']!='') || (isset($requestdata['order']['0']['column']) && $requestdata['order']['0']['column']!='' && isset($requestdata['order']['0']['dir']) && $requestdata['order']['0']['dir']!='')){
			$this->db->join('custom c1', 'c1.c_id=sm.coc_status and c1.type="1"', 'left');
			$this->db->join('custom c2', 'c2.c_id=sm.audit_status and c2.type="2"', 'left');
			$this->db->join('custom c3', 'c3.c_id=sm.type and c3.type="3"', 'left');
		}

		$this->db->where('sm.type', '1');
		$this->db->where('sm.coc_status', '9');

		if(isset($requestdata['roletype']) && $requestdata['roletype']=='6'){
			$this->db->where('sm.user_id',$requestdata['user_id']);
			$this->db->or_where('sm.allocatedby',$requestdata['user_id']);
		}

		if(isset($requestdata['roletype']) && $requestdata['roletype']=='8'){
			$this->db->where('sm.user_id',$requestdata['user_id']);
			$this->db->where('sm.allocatedby IS NULL');
		}				

		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = strtolower((trim($requestdata['search']['value'])));			

			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				$this->db->group_start();
					if($page=='companycocstatement'){					
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('c1.name', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(sm.allocation_date,"%d-%m-%Y")', $searchvalue, 'both');	
						$this->db->or_like('ud.name', $searchvalue, 'both');											
						$this->db->or_like('cl.name', $searchvalue, 'both');
						$this->db->or_like('cl.address', $searchvalue, 'both');																
					}
				$this->db->group_end();
			}
			else
			{
				if($searchvalue === 'allocated'){
					$this->db->where('sm.allocatedby',$requestdata['user_id']);
				}
				elseif($searchvalue === 'in stock'){
					$this->db->where('sm.user_id',$requestdata['user_id']);
				}
				else{
					$this->db->group_start();			
						$this->db->like('sm.id', $searchvalue);
						$this->db->or_like('pa.invoiceno', $searchvalue);
						$this->db->or_like('ud.name', $searchvalue);
						$this->db->or_like('ud.surname', $searchvalue);
						$this->db->or_like('up.registration_no', $searchvalue);
						$this->db->or_like('pd.company_name', $searchvalue);
					$this->db->group_end();
				}
			}
		}			

		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){		
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];				
				if($page=='companycocstatement'){
					$column = ['sm.id', 'c1.name', 'sm.allocation_date', 'ud.name', 'cl.name', 'cl.address'];
				}
			}else
			{
				$column = ['sm.id','sm.id','sm.id','sm.id','sm.id','sm.id'];
			}
			$this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
		}	

		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$this->db->limit($requestdata['length'], $requestdata['start']);
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
}